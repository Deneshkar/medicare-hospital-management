<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AiChatRequest;
use App\Models\AiConversation;
use App\Services\GroqService;
use Illuminate\Http\Request;

class AiAssistantController extends Controller
{
    public function __construct(protected GroqService $groqService) {}

    public function chat(AiChatRequest $request)
    {
        $patient = $request->user()->patient;

        AiConversation::create([
            'patient_id' => $patient->id,
            'role' => 'user',
            'message' => $request->message,
        ]);

        $history = AiConversation::where('patient_id', $patient->id)
            ->latest('id')
            ->take(20)
            ->get()
            ->reverse()
            ->map(fn ($m) => ['role' => $m->role, 'content' => $m->message])
            ->values()
            ->toArray();

        try {
            $reply = $this->groqService->chat($history, $patient);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 503);
        }

        AiConversation::create([
            'patient_id' => $patient->id,
            'role' => 'assistant',
            'message' => $reply,
        ]);

        return response()->json([
            'success' => true,
            'data' => ['reply' => $reply],
        ]);
    }

    public function history(Request $request)
    {
        abort_if($request->user()->role !== 'patient', 403);

        $patient = $request->user()->patient;

        $history = AiConversation::where('patient_id', $patient->id)
            ->oldest('id')
            ->get(['role', 'message', 'created_at']);

        return response()->json(['success' => true, 'data' => $history]);
    }

    public function clear(Request $request)
    {
        abort_if($request->user()->role !== 'patient', 403);

        AiConversation::where('patient_id', $request->user()->patient->id)->delete();

        return response()->json(['success' => true, 'message' => 'Chat history cleared.']);
    }
}

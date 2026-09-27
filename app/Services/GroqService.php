<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqService
{
    protected string $apiKey;

    protected string $model = 'openai/gpt-oss-20b';

    protected string $baseUrl = 'https://api.groq.com/openai/v1/chat/completions';

    public function __construct()
    {
        $this->apiKey = config('services.groq.api_key');
    }

    public function chat(array $conversationHistory): string
    {
        $systemPrompt = $this->systemPrompt();

        $messages = array_merge(
            [['role' => 'system', 'content' => $systemPrompt]],
            $conversationHistory
        );

        $response = Http::withToken($this->apiKey)
            ->timeout(30)
            ->post($this->baseUrl, [
                'model' => $this->model,
                'messages' => $messages,
                'temperature' => 0.4,
                'max_tokens' => 500,
            ]);

        if ($response->failed()) {
            Log::error('Groq API error', ['status' => $response->status(), 'body' => $response->body()]);

            throw new \RuntimeException('The AI assistant is currently unavailable. Please try again later.');
        }

        return $response->json('choices.0.message.content');
    }

    protected function systemPrompt(): string
    {
        return <<<'PROMPT'
You are the MediCare AI Patient Assistant, a helpful assistant for a hospital's patient portal.

You can help with:
- General health information and understanding common medical terminology
- Explaining hospital services and departments
- Helping patients navigate the appointment booking process
- Explaining how to access their medical records, prescriptions, and lab reports on this platform

STRICT RULES YOU MUST ALWAYS FOLLOW:
- You must NEVER attempt to diagnose a disease or medical condition.
- You must NEVER replace a doctor's judgment or tell a patient what treatment to take.
- If a patient describes symptoms that could be serious or urgent (e.g. chest pain, difficulty breathing, severe bleeding, loss of consciousness, signs of stroke), you must clearly and immediately advise them to seek emergency medical care or contact a doctor right away, rather than continuing to discuss the symptoms casually.
- Keep answers concise, warm, and easy to understand for a non-medical audience.
- If you are unsure or a question falls outside hospital-navigation or general health education, say so honestly and suggest the patient consult hospital staff or their doctor.
PROMPT;
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLabReportRequest;
use App\Http\Requests\UpdateLabReportRequest;
use App\Http\Resources\LabReportResource;
use App\Models\LabReport;
use App\Notifications\LabReportAvailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LabReportController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', LabReport::class);

        $query = LabReport::with(['patient.user', 'doctor.user']);

        $query = match ($request->user()->role) {
            'patient' => $query->where('patient_id', $request->user()->patient?->id),
            'doctor' => $query->where('doctor_id', $request->user()->doctor?->id),
            default => $query,
        };

        $labReports = $query->latest()->paginate(10);

        return LabReportResource::collection($labReports)
            ->additional(['success' => true]);
    }

    public function store(StoreLabReportRequest $request)
    {
        $this->authorize('create', LabReport::class);

        $labReport = LabReport::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->user()->doctor->id,
            'test_name' => $request->test_name,
            'status' => 'requested',
        ]);

        return (new LabReportResource($labReport->load(['patient.user', 'doctor.user'])))
            ->additional(['success' => true, 'message' => 'Lab test requested successfully'])
            ->response()
            ->setStatusCode(201);
    }

    public function show(LabReport $labReport)
    {
        $this->authorize('view', $labReport);

        return (new LabReportResource($labReport->load(['patient.user', 'doctor.user'])))
            ->additional(['success' => true]);
    }

    public function update(UpdateLabReportRequest $request, LabReport $labReport)
    {
        $this->authorize('update', $labReport);

        $data = $request->validated();
        unset($data['report_file']);

        if ($request->hasFile('report_file')) {
            if ($labReport->report_file) {
                Storage::disk('local')->delete($labReport->report_file);
            }

            $data['report_file'] = $request->file('report_file')
                ->store("lab-reports/{$labReport->patient_id}", 'local');
        }

        $labReport->update($data);

        if ($labReport->wasChanged('status') && $labReport->status === 'completed') {
            $labReport->patient->user->notify(new LabReportAvailable($labReport));
        }

        return (new LabReportResource($labReport->load(['patient.user', 'doctor.user'])))
            ->additional(['success' => true, 'message' => 'Lab report updated successfully']);
    }

    public function download(LabReport $labReport)
    {
        $this->authorize('view', $labReport);

        if (! $labReport->report_file || ! Storage::disk('local')->exists($labReport->report_file)) {
            return response()->json(['success' => false, 'message' => 'No report file available'], 404);
        }

        return Storage::disk('local')->download($labReport->report_file);
    }
}

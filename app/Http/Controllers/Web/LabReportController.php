<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLabReportRequest;
use App\Http\Requests\UpdateLabReportRequest;
use App\Models\LabReport;
use App\Models\Patient;
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

        return view('lab-reports.index', compact('labReports'));
    }

    public function create()
    {
        $this->authorize('create', LabReport::class);

        $patients = Patient::with('user')->orderBy('id')->get();

        return view('lab-reports.create', compact('patients'));
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

        return redirect()->route('web.lab-reports.show', $labReport)
            ->with('success', 'Lab test requested successfully.');
    }

    public function show(LabReport $labReport)
    {
        $this->authorize('view', $labReport);

        $labReport->load(['patient.user', 'doctor.user']);

        return view('lab-reports.show', compact('labReport'));
    }

    public function edit(LabReport $labReport)
    {
        $this->authorize('update', $labReport);

        $labReport->load(['patient.user']);

        return view('lab-reports.edit', compact('labReport'));
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

        return redirect()->route('web.lab-reports.show', $labReport)
            ->with('success', 'Lab report updated successfully.');
    }

    public function download(LabReport $labReport)
    {
        $this->authorize('view', $labReport);

        if (! $labReport->report_file || ! Storage::disk('local')->exists($labReport->report_file)) {
            return redirect()->route('web.lab-reports.show', $labReport)
                ->with('error', 'No report file available.');
        }

        return Storage::disk('local')->download($labReport->report_file);
    }
}

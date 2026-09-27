<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LabReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'patient' => [
                'id' => $this->patient->id,
                'name' => $this->patient->user->name,
            ],
            'doctor' => [
                'id' => $this->doctor->id,
                'name' => $this->doctor->user->name,
            ],
            'test_name' => $this->test_name,
            'status' => $this->status,
            'result' => $this->result,
            'has_file' => ! is_null($this->report_file),
            'doctor_notes' => $this->doctor_notes,
            'created_at' => $this->created_at,
        ];
    }
}

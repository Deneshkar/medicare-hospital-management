<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicalRecordResource extends JsonResource
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
                'specialization' => $this->doctor->specialization,
            ],
            'appointment_id' => $this->appointment_id,
            'symptoms' => $this->symptoms,
            'diagnosis' => $this->diagnosis,
            'treatment' => $this->treatment,
            'doctor_notes' => $this->doctor_notes,
            'follow_up_date' => $this->follow_up_date?->format('Y-m-d'),
            'created_at' => $this->created_at,
        ];
    }
}

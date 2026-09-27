<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VitalSignResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'patient' => [
                'id' => $this->patient->id,
                'name' => $this->patient->user->name,
            ],
            'recorded_by' => $this->recordedBy->name,
            'temperature' => $this->temperature,
            'blood_pressure' => $this->blood_pressure,
            'heart_rate' => $this->heart_rate,
            'respiratory_rate' => $this->respiratory_rate,
            'oxygen_saturation' => $this->oxygen_saturation,
            'weight' => $this->weight,
            'height' => $this->height,
            'recorded_at' => $this->recorded_at,
        ];
    }
}

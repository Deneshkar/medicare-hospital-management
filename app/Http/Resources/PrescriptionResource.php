<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionResource extends JsonResource
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
            'general_instructions' => $this->general_instructions,
            'items' => $this->items->map(fn ($item) => [
                'id' => $item->id,
                'medicine_name' => $item->medicine_name,
                'dosage' => $item->dosage,
                'frequency' => $item->frequency,
                'duration' => $item->duration,
                'instructions' => $item->instructions,
            ]),
            'created_at' => $this->created_at,
        ];
    }
}

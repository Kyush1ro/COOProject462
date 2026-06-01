<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'course_id' => $this->course_id,
            'percentage' => $this->percentage,
            'last_updated' => $this->updated_at->toDateTimeString(),
            'status' => $this->percentage >= 100 ? 'Completed' : 'In Progress',
        ];
    }
}
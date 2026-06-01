<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentPerformanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // We expect $this->resource to be an array of stats we calculated
        return [
            'student_name' => $this->resource['student']->name,
            'statistics' => [
                'total_courses_enrolled' => $this->resource['enrolled_count'],
                'assignments_submitted' => $this->resource['submissions_count'],
                'average_progress' => $this->resource['average_progress'] . '%',
            ],
            'generated_at' => now()->toDateTimeString(),
        ];
    }
}
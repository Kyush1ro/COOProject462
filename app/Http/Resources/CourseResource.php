<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'code' => $this->course_code,
            'description' => $this->description,
            // Check if the 'instructor' relationship is loaded to avoid N+1 errors
            'instructor' => $this->whenLoaded('instructor', function () {
                return $this->instructor->name;
            }),
            // Check if the 'students' count is available
            'students_count' => $this->whenCounted('students'),
        ];
    }
}
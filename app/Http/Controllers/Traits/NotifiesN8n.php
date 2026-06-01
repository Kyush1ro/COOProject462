<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait NotifiesN8n
{
    /**
     * Send data to n8n Webhook
     */
    protected function sendToN8n(string $type, $data, $recipients)
    {
        // ⚠️ PASTE YOUR N8N WEBHOOK URL HERE
        $url = env('N8N_WEBHOOK_URL');
        if (!$url) {
            \Illuminate\Support\Facades\Log::error('N8N Webhook URL is missing in .env');
            return;
        }
        try {
            $payload = [
                'type' => $type,
                'timestamp' => now()->toDateTimeString(),
            ];

            if ($type === 'assignment') {
                $payload['subject'] = "New Assignment: " . $data->title;
                $payload['course'] = $data->course->title;
                $payload['title'] = $data->title;
                $payload['description'] = $data->description;
                $payload['due_date'] = $data->due_date;
                $payload['student_email'] = $recipients; // Array of emails
            } elseif ($type === 'grade') {
                $payload['subject'] = "Grade Posted: " . $data->assignment->title;
                $payload['course'] = $data->assignment->course->title;
                $payload['title'] = $data->assignment->title;
                $payload['grade'] = $data->grade;
                $payload['max_score'] = $data->assignment->max_score;
                $payload['feedback'] = $data->feedback;
                $payload['student_email'] = $recipients; // Single email
            } elseif ($type === 'announcement') {
                $payload['subject'] = "New Announcement: " . $data->title;
                $payload['course'] = $data->course->title;
                $payload['title'] = $data->title;
                $payload['message'] = $data->content;
                $payload['student_email'] = $recipients; // Array of emails
            } elseif ($type === 'notice') {
                $payload['subject'] = $data['subject'];
                $payload['message'] = $data['message'];
                $payload['sender_name'] = $data['sender_name'];
                $payload['student_email'] = $recipients; // Array of emails
            } elseif ($type === 'material') {
                $payload['subject'] = "New Material: " . $data['title'];
                $payload['course'] = $data['course'];
                $payload['title'] = $data['title'];
                $payload['file_type'] = $data['file_type'];
                $payload['instructor_name'] = $data['instructor_name'];
                $payload['student_email'] = $recipients;
            }


            // Send without waiting (timeout 2s) so the user doesn't wait for n8n
            Http::withOptions(['verify' => false])->timeout(2)->post($url, $payload);
        } catch (\Exception $e) {
            Log::error("Failed to notify n8n: " . $e->getMessage());
        }
    }
}

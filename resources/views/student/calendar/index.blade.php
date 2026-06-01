@extends('layouts.dashboard')

@section('page-title', __('messages.my_calendar'))

@section('styles')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <style>
        #calendar {
            max-width: 1100px;
            margin: 0 auto;
            min-height: 600px;
        }
        .fc-event {
            cursor: pointer;
        }
        /* Fix for CoreUI conflicts if any */
        .fc-toolbar-title {
            font-size: 1.5rem !important;
        }
        .fc-button-primary {
            background-color: #3b82f6 !important;
            border-color: #2563eb !important;
        }
        .fc-button-primary:hover {
            background-color: #2563eb !important;
            border-color: #1d4ed8 !important;
        }
    </style>
@endsection

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <div class="d-flex align-items-center">
                <i class="fas fa-calendar-alt text-primary me-2"></i>
                <h5 class="mb-0">{{ __('messages.academic_calendar') }}</h5>
            </div>
        </div>
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: @json($events),
                eventClick: function(info) {
                    if (info.event.url) {
                        window.location.href = info.event.url;
                        info.jsEvent.preventDefault();
                    }
                },
                eventTimeFormat: { // like '14:30:00'
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false
                }
            });
            calendar.render();
        });
    </script>
@endsection

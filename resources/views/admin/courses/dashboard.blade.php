{{-- dashboard/admin/home.blade.php --}}
@extends('layouts.dashboard')
@section('title', __('messages.admin_dashboard'))

@section('content')
<div class="row">
    <div class="col-md-3"><div class="card text-white bg-primary mb-3"><div class="card-body"><h5>{{ __('messages.total_users') }}</h5><p>{{ $usersCount ?? 0 }}</p></div></div></div>
    <div class="col-md-3"><div class="card text-white bg-success mb-3"><div class="card-body"><h5>{{ __('messages.students') }}</h5><p>{{ $studentsCount ?? 0 }}</p></div></div></div>
    <div class="col-md-3"><div class="card text-white bg-warning mb-3"><div class="card-body"><h5>{{ __('messages.teachers') }}</h5><p>{{ $teachersCount ?? 0 }}</p></div></div></div>
    <div class="col-md-3"><div class="card text-white bg-danger mb-3"><div class="card-body"><h5>{{ __('messages.courses') }}</h5><p>{{ $coursesCount ?? 0 }}</p></div></div></div>
</div>

{{-- Chart Example --}}
<div class="card mt-4">
    <div class="card-header">{{ __('messages.user_growth') }}</div>
    <div class="card-body">
        <canvas id="usersChart" height="100"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('usersChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($userGrowth['months'] ?? []) !!},
        datasets: [{
            label: '{{ __('messages.new_users') }}',
            data: {!! json_encode($userGrowth['counts'] ?? []) !!},
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            fill: true,
            tension: 0.3
        }]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});
</script>
@endsection

@extends('layouts.dashboard')

@section('title', __('messages.admin_reports'))
@section('page-title', __('messages.system_statistics_reports'))

@section('content')
<div class="container-fluid p-0">
    
    {{-- 1. KPI Summary Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary h-100 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1 small opacity-75">{{ __('messages.total_users') }}</h6>
                        <h2 class="mb-0 fw-bold">{{ $totalUsers }}</h2>
                    </div>
                    <i class="fas fa-users fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success h-100 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1 small opacity-75">{{ __('messages.courses') }}</h6>
                        <h2 class="mb-0 fw-bold">{{ $totalCourses }}</h2>
                    </div>
                    <i class="fas fa-book-open fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info h-100 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1 small opacity-75">{{ __('messages.enrollments') }}</h6>
                        <h2 class="mb-0 fw-bold">{{ $totalEnrollments }}</h2>
                    </div>
                    <i class="fas fa-graduation-cap fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning h-100 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1 small opacity-75">{{ __('messages.assignments') }}</h6>
                        <h2 class="mb-0 fw-bold">{{ $totalAssignments }}</h2>
                    </div>
                    <i class="fas fa-tasks fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- 2. User Roles Chart (Pie) --}}
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header fw-bold">
                    <i class="fas fa-chart-pie me-1 text-primary"></i> {{ __('messages.user_distribution') }}
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div style="width: 100%; max-width: 300px;">
                        <canvas id="rolesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Popular Courses Chart (Bar) --}}
        <div class="col-md-8">
            <div class="card h-100 shadow-sm">
                <div class="card-header fw-bold">
                    <i class="fas fa-chart-bar me-1 text-success"></i> {{ __('messages.top_popular_courses') }}
                </div>
                <div class="card-body">
                    <canvas id="coursesChart"></canvas>
                </div>
            </div>
        </div>

        {{-- 4. Growth Chart (Line) --}}
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">
                    <i class="fas fa-chart-line me-1 text-info"></i> {{ __('messages.enrollment_growth') }}
                </div>
                <div class="card-body">
                    <canvas id="growthChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- Load Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // --- Data from Controller ---
    const rolesData = {!! json_encode($rolesData) !!};
    const popularCourses = {!! json_encode($popularCourses) !!};
    const growthData = {!! json_encode($enrollmentGrowth) !!};

    // --- 1. PIE CHART: Roles ---
    const rolesCtx = document.getElementById('rolesChart').getContext('2d');
    new Chart(rolesCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(rolesData).map(str => str.charAt(0).toUpperCase() + str.slice(1)),
            datasets: [{
                data: Object.values(rolesData),
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // --- 2. BAR CHART: Popular Courses ---
    const coursesCtx = document.getElementById('coursesChart').getContext('2d');
    new Chart(coursesCtx, {
        type: 'bar',
        data: {
            labels: popularCourses.map(c => c.course_code),
            datasets: [{
                label: '{{ __('messages.enrolled_students') }}',
                data: popularCourses.map(c => c.students_count),
                backgroundColor: '#1cc88a',
                borderRadius: 4,
                barThickness: 40
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    // --- 3. LINE CHART: Growth ---
    const growthCtx = document.getElementById('growthChart').getContext('2d');
    new Chart(growthCtx, {
        type: 'line',
        data: {
            labels: growthData.map(d => d.month_label),
            datasets: [{
                label: '{{ __('messages.new_enrollments') }}',
                data: growthData.map(d => d.count),
                borderColor: '#36b9cc',
                backgroundColor: 'rgba(54, 185, 204, 0.1)',
                fill: true,
                tension: 0.3,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
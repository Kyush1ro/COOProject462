@extends('layouts.dashboard')

@section('title', 'Admin Reports')
@section('page-title', 'System Statistics & Reports')

@section('content')
<div class="row g-4 mb-4">
    <!-- Summary Card 1 -->
    <div class="col-md-4">
        <div class="card bg-primary text-white h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-1">Total Users</h6>
                    <h2 class="mb-0">{{ $totalUsers }}</h2>
                </div>
                <i class="fas fa-users fa-3x opacity-50"></i>
            </div>
        </div>
    </div>
    <!-- Summary Card 2 -->
    <div class="col-md-4">
        <div class="card bg-success text-white h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-1">Active Courses</h6>
                    <h2 class="mb-0">{{ $totalCourses }}</h2>
                </div>
                <i class="fas fa-book-open fa-3x opacity-50"></i>
            </div>
        </div>
    </div>
    <!-- Summary Card 3 -->
    <div class="col-md-4">
        <div class="card bg-info text-white h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-1">Total Enrollments</h6>
                    <h2 class="mb-0">{{ $totalEnrollments }}</h2>
                </div>
                <i class="fas fa-user-graduate fa-3x opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Chart 1: User Distribution -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white font-weight-bold">
                User Distribution
            </div>
            <div class="card-body">
                <canvas id="rolesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart 2: Popular Courses -->
    <div class="col-md-6 col-lg-8">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white font-weight-bold">
                Top 5 Most Popular Courses
            </div>
            <div class="card-body">
                <canvas id="coursesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart 3: Growth -->
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white font-weight-bold">
                Enrollment Trends (Last 6 Months)
            </div>
            <div class="card-body">
                <canvas id="growthChart" height="80"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Load Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // --- 1. ROLES PIE CHART ---
    const rolesCtx = document.getElementById('rolesChart').getContext('2d');
    const rolesData = {!! json_encode($rolesData) !!}; // Pass PHP data to JS
    
    new Chart(rolesCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(rolesData).map(k => k.charAt(0).toUpperCase() + k.slice(1)), // Capitalize
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

    // --- 2. POPULAR COURSES BAR CHART ---
    const coursesCtx = document.getElementById('coursesChart').getContext('2d');
    const courses = {!! json_encode($popularCourses) !!};
    
    new Chart(coursesCtx, {
        type: 'bar',
        data: {
            labels: courses.map(c => c.course_code),
            datasets: [{
                label: 'Enrolled Students',
                data: courses.map(c => c.students_count),
                backgroundColor: '#36b9cc',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    // --- 3. GROWTH LINE CHART ---
    const growthCtx = document.getElementById('growthChart').getContext('2d');
    const growthData = {!! json_encode($enrollmentGrowth) !!};

    new Chart(growthCtx, {
        type: 'line',
        data: {
            labels: growthData.map(d => d.month_name),
            datasets: [{
                label: 'New Enrollments',
                data: growthData.map(d => d.count),
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
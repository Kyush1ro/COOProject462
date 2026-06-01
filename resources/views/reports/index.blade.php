@extends('layouts.dashboard')

@section('title', 'System Reports')
@section('page-title', 'System Reports & Analytics')

@section('content')
    {{-- 1. Summary Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary shadow-sm h-100">
                <div class="card-body">
                    <div class="text-white-50 small text-uppercase fw-bold">Total Students</div>
                    <div class="fs-2 fw-bold">{{ $totalStudents }}</div>
                    <i class="fas fa-user-graduate position-absolute top-0 end-0 p-3 opacity-25 fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info shadow-sm h-100">
                <div class="card-body">
                    <div class="text-white-50 small text-uppercase fw-bold">Total Instructors</div>
                    <div class="fs-2 fw-bold">{{ $totalInstructors }}</div>
                    <i class="fas fa-chalkboard-teacher position-absolute top-0 end-0 p-3 opacity-25 fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success shadow-sm h-100">
                <div class="card-body">
                    <div class="text-white-50 small text-uppercase fw-bold">Active Courses</div>
                    <div class="fs-2 fw-bold">{{ $activeCourses }}</div>
                    <div class="small text-white-50">out of {{ $totalCourses }} total</div>
                    <i class="fas fa-book-open position-absolute top-0 end-0 p-3 opacity-25 fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow-sm h-100">
                <div class="card-body">
                    <div class="text-white-50 small text-uppercase fw-bold">Active Semester</div>
                    <div class="fs-2 fw-bold">{{ \App\Models\Semester::getActive()->id ?? 'N/A' }}</div>
                    <div class="small text-white-50">{{ \App\Models\Semester::getActive()->name ?? 'None' }}</div>
                    <i class="fas fa-calendar-alt position-absolute top-0 end-0 p-3 opacity-25 fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- 2. Enrollments per Department --}}
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-bold">
                    <i class="fas fa-chart-pie me-2"></i> Enrollments by Department
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Department</th>
                                    <th class="text-end">Enrollments</th>
                                    <th class="text-end">Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalEnrollments = $enrollmentsByDept->sum('total_enrollments'); @endphp
                                @foreach($enrollmentsByDept as $dept)
                                    <tr>
                                        <td>
                                            <span class="badge bg-secondary me-1">{{ $dept->code }}</span>
                                            {{ $dept->name }}
                                        </td>
                                        <td class="text-end fw-bold">{{ $dept->total_enrollments }}</td>
                                        <td class="text-end text-muted">
                                            {{ $totalEnrollments > 0 ? round(($dept->total_enrollments / $totalEnrollments) * 100, 1) : 0 }}%
                                        </td>
                                    </tr>
                                @endforeach
                                @if($enrollmentsByDept->isEmpty())
                                    <tr><td colspan="3" class="text-center text-muted">No enrollment data available.</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Recent System Activity --}}
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-bold">
                    <i class="fas fa-chart-line me-2"></i> System Activity (Last 7 Days)
                </div>
                <div class="card-body">
                    <canvas id="activityChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart.js Script --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('activityChart').getContext('2d');
            
            // Prepare Data
            const labels = @json($activityData->pluck('date'));
            const data = @json($activityData->pluck('count'));

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Actions Performed',
                        data: data,
                        borderColor: '#321fdb',
                        backgroundColor: 'rgba(50, 31, 219, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    }
                }
            });
        });
    </script>
@endsection

@extends('layouts.dashboard')

@section('title', __('messages.admin_dashboard'))
@section('page-title', __('messages.admin_dashboard'))

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white h-100 shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-users fa-3x mb-3 opacity-50"></i>
                <h5 class="card-title">{{ __('messages.total_users') }}</h5>
                <h2 class="mb-0">{{ $usersCount }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white h-100 shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-book-open fa-3x mb-3 opacity-50"></i>
                <h5 class="card-title">{{ __('messages.total_courses') }}</h5>
                <h2 class="mb-0">{{ $coursesCount }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white h-100 shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-pencil-alt fa-3x mb-3 opacity-50"></i>
                <h5 class="card-title">{{ __('messages.assignments') }}</h5>
                <h2 class="mb-0">{{ $assignmentsCount }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark h-100 shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                <h5 class="card-title">{{ __('messages.submissions') }}</h5>
                <h2 class="mb-0">{{ $submissionsCount }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header py-3">
                <h5 class="mb-0">{{ __('messages.quick_actions') }}</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="{{ route('users.create') }}" class="btn btn-outline-primary w-100 py-3">
                            <i class="fas fa-user-plus fa-2x mb-2 d-block"></i>
                            {{ __('messages.add_user') }}
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.courses.create') }}" class="btn btn-outline-success w-100 py-3">
                            <i class="fas fa-plus-circle fa-2x mb-2 d-block"></i>
                            {{ __('messages.add_course') }}
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-info w-100 py-3">
                            <i class="fas fa-chart-bar fa-2x mb-2 d-block"></i>
                            {{ __('messages.view_reports') }}
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-secondary w-100 py-3">
                            <i class="fas fa-cog fa-2x mb-2 d-block"></i>
                            {{ __('messages.settings') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

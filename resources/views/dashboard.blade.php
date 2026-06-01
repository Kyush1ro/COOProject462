@extends('layouts.dashboard')

@section('page-title', __('messages.dashboard'))

@section('breadcrumb')
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ __('messages.dashboard') }}</li>
    </ol>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <div class="text-muted small">{{ __('messages.courses') }}</div>
                            <div class="h4 mb-0">{{ $coursesCount ?? 8 }}</div>
                        </div>
                        <div class="ms-auto">
                            <span class="badge bg-primary rounded-pill">{{ __('messages.view') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <div class="text-muted small">{{ __('messages.assignments') }}</div>
                            <div class="h4 mb-0">{{ $assignmentsCount ?? 27 }}</div>
                        </div>
                        <div class="ms-auto">
                            <span class="badge bg-warning text-dark rounded-pill">{{ __('messages.due') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <div class="text-muted small">{{ __('messages.submissions') }}</div>
                            <div class="h4 mb-0">{{ $submissionsCount ?? 142 }}</div>
                        </div>
                        <div class="ms-auto">
                            <span class="badge bg-success rounded-pill">{{ __('messages.recent') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <div class="text-muted small">{{ __('messages.users') }}</div>
                            <div class="h4 mb-0">{{ $usersCount ?? 56 }}</div>
                        </div>
                        <div class="ms-auto">
                            <span class="badge bg-info text-dark rounded-pill">{{ __('messages.members') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">{{ __('messages.recent_activity') }}</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.when') }}</th>
                                    <th>{{ __('messages.user') }}</th>
                                    <th>{{ __('messages.action') }}</th>
                                    <th>{{ __('messages.context') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentActivity ?? [
                                    ['when' => '2m ago','user'=>'Alice','action'=>'Submitted','context'=>'Assignment 3'],
                                    ['when' => '1h ago','user'=>'Bob','action'=>'Enrolled','context'=>'CS101'],
                                    ['when' => 'Yesterday','user'=>'Carol','action'=>'Uploaded','context'=>'Lecture 5 Material']
                                ] as $act)
                                    <tr>
                                        <td class="align-middle">{{ $act['when'] }}</td>
                                        <td class="align-middle">{{ $act['user'] }}</td>
                                        <td class="align-middle">{{ $act['action'] }}</td>
                                        <td class="align-middle">{{ $act['context'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header">{{ __('messages.quick_links') }}</div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('courses.index') }}" class="list-group-item list-group-item-action">{{ __('messages.all_courses') }}</a>
                    <a href="{{ route('assignments.index') }}" class="list-group-item list-group-item-action">{{ __('messages.all_assignments') }}</a>
                    <a href="{{ route('submissions.index') }}" class="list-group-item list-group-item-action">{{ __('messages.submissions') }}</a>
                    <a href="{{ route('materials.index') }}" class="list-group-item list-group-item-action">{{ __('messages.materials') }}</a>
                </div>
            </div>
        </div>
    </div>

@endsection

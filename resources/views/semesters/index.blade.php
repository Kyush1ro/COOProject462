@extends('layouts.dashboard')

@section('title', __('messages.semesters'))
@section('page-title', __('messages.semester_management'))

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <span><i class="fas fa-calendar-alt me-2"></i> {{ __('messages.all_semesters') }}</span>
            <a href="{{ route('semesters.create') }}" class="btn btn-light btn-sm text-primary fw-bold">
                <i class="fas fa-plus"></i> {{ __('messages.add_semester') }}
            </a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>{{ __('messages.id') }}</th>
                        <th>{{ __('messages.name') }}</th>
                        <th>{{ __('messages.start_date') }}</th>
                        <th>{{ __('messages.end_date') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th class="text-center">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($semesters as $semester)
                        <tr>
                            <td><strong>{{ $semester->id }}</strong></td>
                            <td>{{ $semester->name }}</td>
                            <td>{{ $semester->start_date->format('M d, Y') }}</td>
                            <td>{{ $semester->end_date->format('M d, Y') }}</td>
                            <td>
                                @if($semester->is_active)
                                    <span class="badge bg-success">{{ __('messages.active') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('messages.inactive') }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('semesters.edit', $semester->id) }}" class="btn btn-sm btn-warning text-dark me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('semesters.destroy', $semester->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('{{ __('messages.confirm_delete_semester') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger text-white">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">{{ __('messages.no_semesters_found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@extends('layouts.dashboard')

@section('title', __('messages.departments'))
@section('page-title', __('messages.manage_departments'))

@section('page-actions')
    <a href="{{ route('departments.create') }}" class="btn btn-primary text-white">
        <i class="fas fa-plus"></i> {{ __('messages.add_department') }}
    </a>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-building me-2"></i> {{ __('messages.departments_list') }}
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>{{ __('messages.code') }}</th>
                            <th>{{ __('messages.name') }}</th>
                            <th>{{ __('messages.courses_count') }}</th>
                            <th class="text-center">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departments as $dept)
                            <tr>
                                <td class="fw-bold text-primary">{{ $dept->code }}</td>
                                <td class="fw-bold">{{ $dept->name }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $dept->courses_count }} {{ __('messages.courses') }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('departments.edit', $dept->id) }}"
                                            class="btn btn-sm btn-primary text-white">
                                            <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                                        </a>

                                        <form action="{{ route('departments.destroy', $dept->id) }}" method="POST"
                                            onsubmit="return confirm('{{ __('messages.confirm_delete_department') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger text-white">
                                                <i class="fas fa-trash"></i> {{ __('messages.delete') }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">{{ __('messages.no_departments_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.dashboard')

@section('title', __('messages.audit_logs'))
@section('page-title', __('messages.system_audit_logs'))

@section('page-actions')
    <a href="{{ route('logs.destroy', 0) }}"
        onclick="event.preventDefault(); document.getElementById('clear-logs-form').submit();"
        class="btn btn-danger text-white">
        <i class="fas fa-trash-alt me-1"></i> {{ __('messages.clear_all_logs') }}
    </a>
    <form id="clear-logs-form" action="{{ route('logs.destroy', 0) }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-history me-2"></i> {{ __('messages.recent_activity') }}
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>{{ __('messages.time') }}</th>
                            <th>{{ __('messages.user') }}</th>
                            <th>{{ __('messages.action') }}</th>
                            <th>{{ __('messages.entity') }}</th>
                            <th>{{ __('messages.ip') }}</th>
                            <th>{{ __('messages.details') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('M d, H:i') }}</td>
                                <td>{{ $log->user->name }}</td>
                                <td>
                                    @php
                                        $actionClass =
                                            [
                                                'created' => 'primary',
                                                'updated' => 'warning text-dark',
                                                'deleted' => 'danger',
                                                'enrolled' => 'success',
                                                'downloaded' => 'info',
                                            ][$log->action] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $actionClass }}">{{ ucfirst(__("messages." . $log->action)) }}</span>
                                </td>
                                <td>
                                    {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                                </td>
                                <td>
                                    {{ $log->ip_address === '::1' ? '127.0.0.1' : $log->ip_address }}
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-link text-decoration-none" type="button" data-coreui-toggle="collapse" data-coreui-target="#logDetails{{ $log->id }}" aria-expanded="false">
                                        {{ __('messages.view_details') }}
                                    </button>
                                    <div class="collapse" id="logDetails{{ $log->id }}">
                                        <div class="card card-body bg-body-tertiary p-2 mt-1 small">
                                            <pre class="mb-0" style="white-space: pre-wrap;">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    {{ __('messages.no_audit_records') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- Pagination links go here --}}
    <div class="mt-3">
        {{ $logs->links() }}
    </div>
@endsection

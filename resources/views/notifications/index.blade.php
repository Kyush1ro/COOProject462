@extends('layouts.dashboard')

@section('title', __('messages.my_notifications'))
@section('page-title', __('messages.notifications'))

@section('page-actions')
    @if($notifications->count() > 0)
        <form action="{{ route('notifications.readAll') }}" method="POST">
            @csrf
            <button class="btn btn-outline-primary btn-sm">
                <i class="fas fa-check-double me-1"></i> {{ __('messages.mark_all_read') }}
            </button>
        </form>
    @endif
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="list-group list-group-flush">
                    @forelse($notifications as $notification)
                        <div class="list-group-item list-group-item-action {{ $notification->read_at ? 'bg-body-secondary' : '' }}">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <h5 class="mb-1 {{ $notification->read_at ? 'text-muted' : 'text-primary fw-bold' }}">
                                    {{ $notification->data['subject'] ?? __('messages.no_subject') }}
                                </h5>
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1">{{ $notification->data['message'] ?? '' }}</p>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="text-muted">{{ __('messages.from') }}: {{ $notification->data['sender'] ?? __('messages.system') }}</small>
                                @if(!$notification->read_at)
                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-link text-decoration-none">{{ __('messages.mark_as_read') }}</button>
                                    </form>
                                @else
                                    <span class="badge bg-secondary">{{ __('messages.read') }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-bell-slash fa-3x mb-3"></i>
                            <p>{{ __('messages.no_notifications') }}</p>
                        </div>
                    @endforelse
                </div>
                
                @if($notifications->hasPages())
                    <div class="card-footer">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

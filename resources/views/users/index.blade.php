@extends('layouts.dashboard')

@section('title', __('messages.all_users'))
@section('page-title', __('messages.user_management'))

@section('page-actions')
    <a href="{{ route('users.create') }}" class="btn btn-primary text-white">
        <i class="fas fa-user-plus"></i> {{ __('messages.add_new_user') }}
    </a>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-users me-2"></i> {{ __('messages.users_list') }}
        </div>
        <div class="card-body">
            {{-- Search & Filter Form --}}
            <form action="{{ route('users.index') }}" method="GET" class="row g-3 mb-4">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="{{ __('messages.search_placeholder') }}" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select">
                        <option value="">{{ __('messages.all_roles') }}</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>{{ __('messages.admin') }}</option>
                        <option value="instructor" {{ request('role') == 'instructor' ? 'selected' : '' }}>{{ __('messages.instructor') }}</option>
                        <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>{{ __('messages.student') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> {{ __('messages.filter') }}
                    </button>
                </div>
                @if(request()->anyFilled(['search', 'role']))
                    <div class="col-md-2">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-times"></i> {{ __('messages.clear') }}
                        </a>
                    </div>
                @endif
            </form>

            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>{{ __('messages.id') }}</th>
                            <th>{{ __('messages.name') }}</th>
                            <th>{{ __('messages.email') }}</th>
                            <th>{{ __('messages.role') }}</th>
                            <th>{{ __('messages.joined') }}</th>
                            {{-- Fixed width to prevent button stacking --}}
                            <th class="text-center" style="width: 200px;">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="fw-bold">{{ $user->Academic_ID }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if ($user->role === 'admin')
                                        <span class="badge bg-danger">{{ __('messages.admin') }}</span>
                                    @elseif($user->role === 'instructor')
                                        <span class="badge bg-warning text-dark">{{ __('messages.instructor') }}</span>
                                    @else
                                        <span class="badge bg-success">{{ __('messages.student') }}</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        
                                        {{-- Edit Button (Blue) --}}
                                        <a href="{{ route('users.edit', $user->Academic_ID) }}" 
                                           class="btn btn-sm btn-primary text-white">
                                            <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                                        </a>

                                        {{-- Delete Button --}}
                                        @if (Auth::user()->Academic_ID !== $user->Academic_ID)
                                            <form action="{{ route('users.destroy', $user->Academic_ID) }}" method="POST" 
                                                  onsubmit="return confirm('{{ __('messages.confirm_delete_user', ['name' => $user->name]) }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger text-white">
                                                    <i class="fas fa-trash"></i> {{ __('messages.delete') }}
                                                </button>
                                            </form>
                                        @else
                                            {{-- Disabled button for self --}}
                                            <button class="btn btn-sm btn-secondary disabled" title="{{ __('messages.cannot_delete_self') }}">
                                                <i class="fas fa-user-lock"></i>
                                            </button>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
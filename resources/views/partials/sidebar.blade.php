@php
    $role = auth()->user()->role ?? 'operations';
@endphp

<div class="sidebar border-end" id="sidebar">

    {{-- HEADER --}}
    <div class="sidebar-header border-bottom">
        <div class="sidebar-brand d-flex align-items-center gap-2">
            <i class="fas fa-industry fa-xl text-primary"></i>
            <span class="fw-bold">Petrochemical ERP</span>
        </div>
    </div>

    {{-- NAVIGATION --}}
    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>

        {{-- Dashboard --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
               href="{{ route('dashboard') }}">
                <i class="nav-icon fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>

        {{-- Notifications --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('notifications.index') ? 'active' : '' }}"
               href="{{ route('notifications.index') }}">
                <i class="nav-icon fas fa-bell"></i>
                <span>Notifications</span>
            </a>
        </li>

        {{-- PM MODULE --}}
        <li class="nav-title">
            <i class="fas fa-tools me-2"></i>PM Module
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('equipment.*') ? 'active' : '' }}"
               href="{{ route('equipment.index') }}">
                <i class="nav-icon fas fa-cogs"></i>
                <span>Equipment</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('maintenance-requests.*') ? 'active' : '' }}"
               href="{{ route('maintenance-requests.index') }}">
                <i class="nav-icon fas fa-screwdriver-wrench"></i>
                <span>Maintenance Requests</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('work-orders.*') ? 'active' : '' }}"
               href="{{ route('work-orders.index') }}">
                <i class="nav-icon fas fa-clipboard-list"></i>
                <span>Work Orders</span>
            </a>
        </li>

        {{-- MM MODULE --}}
        <li class="nav-title">
            <i class="fas fa-boxes-stacked me-2"></i>MM Module
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('spare-parts.*') ? 'active' : '' }}"
               href="{{ route('spare-parts.index') }}">
                <i class="nav-icon fas fa-boxes"></i>
                <span>Spare Parts</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('purchase-requisitions.*') ? 'active' : '' }}"
               href="{{ route('purchase-requisitions.index') }}">
                <i class="nav-icon fas fa-file-invoice"></i>
                <span>Purchase Requisitions</span>
            </a>
        </li>

        {{-- HCM MODULE --}}
        <li class="nav-title">
            <i class="fas fa-users me-2"></i>HCM Module
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('leave-requests.*') ? 'active' : '' }}"
               href="{{ route('leave-requests.index') }}">
                <i class="nav-icon fas fa-calendar-alt"></i>
                <span>Leave Requests</span>
            </a>
        </li>

        {{-- ADMIN AREA --}}
        @if ($role === 'admin')
            <li class="nav-title">
                <i class="fas fa-shield-alt me-2"></i>Administration
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                   href="{{ route('users.index') }}">
                    <i class="nav-icon fas fa-users"></i>
                    <span>Users</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}"
                   href="{{ route('departments.index') }}">
                    <i class="nav-icon fas fa-building"></i>
                    <span>Departments</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}"
                   href="{{ route('admin.logs.index') }}">
                    <i class="nav-icon fas fa-history"></i>
                    <span>Audit Logs</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
                   href="{{ route('admin.settings.index') }}">
                    <i class="nav-icon fas fa-gear"></i>
                    <span>Settings</span>
                </a>
            </li>
        @endif

    </ul>
</div>
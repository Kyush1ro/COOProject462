<header class="header header-sticky p-0 mb-4 bg-body">
    <div class="container-fluid border-bottom px-4 py-2">
        {{-- Sidebar Toggler --}}
        <button class="header-toggler btn btn-ghost-primary rounded-circle d-flex align-items-center justify-content-center border-0 d-lg-none" 
            type="button" 
            id="header-toggler"
            aria-label="Toggle sidebar"
            style="width: 40px; height: 40px; transition: background-color 0.2s;">
            <i class="fas fa-bars fa-lg"></i>
        </button>

        {{-- Navigation --}}
        <ul class="header-nav d-none d-md-flex ms-3">
            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
        </ul>

        {{-- Right Side Icons --}}
        <ul class="header-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link position-relative" href="{{ route('notifications.index') }}">
                    <i class="far fa-bell fa-lg"></i>
                    @php
                        $unreadCount = auth()->user()->unreadNotifications->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                            {{ $unreadCount }}
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    @endif
                </a>
            </li>
            <li class="nav-item ms-3">
                <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
            </li>

            {{-- Theme Switcher --}}
            <li class="nav-item dropdown">
                <button class="nav-link py-0" data-coreui-toggle="dropdown" type="button" aria-expanded="false">
                    <i class="fas fa-adjust theme-icon-active"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end pt-0">
                    <li>
                        <button class="dropdown-item d-flex align-items-center" type="button" data-coreui-theme-value="light">
                            <i class="fas fa-sun me-2"></i> {{ __('messages.light') }}
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item d-flex align-items-center" type="button" data-coreui-theme-value="dark">
                            <i class="fas fa-moon me-2"></i> {{ __('messages.dark') }}
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item d-flex align-items-center" type="button" data-coreui-theme-value="auto">
                            <i class="fas fa-circle-half-stroke me-2"></i> {{ __('messages.auto') }}
                        </button>
                    </li>
                </ul>
            </li>
            <li class="nav-item ms-3">
                <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
            </li>

            {{-- Language Switcher --}}
            <li class="nav-item dropdown">
                <a class="nav-link py-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-globe me-2"></i>
                        <span class="d-none d-md-inline">{{ app()->getLocale() == 'ar' ? 'العربية' : 'English' }}</span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end pt-0">
                    <a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">English</a>
                    <a class="dropdown-item" href="{{ route('lang.switch', 'ar') }}">العربية</a>
                </div>
            </li>
            
            <li class="nav-item ms-3">
                <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
            </li>
            
            {{-- User Dropdown --}}
            <li class="nav-item dropdown">
                <a class="nav-link py-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <div class="avatar avatar-md bg-secondary text-white d-flex align-items-center justify-content-center">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end pt-0">
                    <div class="dropdown-header bg-body-secondary py-2">
                        <div class="fw-semibold">{{ __('messages.account') }}</div>
                    </div>
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="fas fa-user me-2"></i> {{ __('messages.profile') }}
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i> {{ __('messages.log_out') }}
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</header>

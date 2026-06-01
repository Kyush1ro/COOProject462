<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LMS Dashboard')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- CoreUI must be loaded AFTER Tailwind (app.css) to prevent style overrides --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.3.1/dist/css/coreui.min.css">

    @vite('resources/js/app.js')

    <script>
        (function() {
            const THEME_STORAGE_KEY = 'coreui-free-vue-admin-template-theme';
            const storedTheme = localStorage.getItem(THEME_STORAGE_KEY);
            const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = storedTheme || 'auto';

            if (theme === 'dark' || (theme === 'auto' && systemDark)) {
                document.documentElement.setAttribute('data-coreui-theme', 'dark');
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.setAttribute('data-coreui-theme', 'light');
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    @yield('styles')
</head>
<body class="d-flex">
    
    @include('partials.sidebar')

<div class="wrapper d-flex flex-column min-vh-100 bg-body flex-grow-1">
    
        @include('partials.header')

        <div class="body flex-grow-1 px-3">
            <div class="container-fluid">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="h4 mb-0 text-body-emphasis d-flex align-items-center gap-2">
                            @yield('page-icon')
                            @yield('page-title', 'Dashboard')
                        </h2>
                        <div class="small text-muted mt-1">
                            @yield('page-description')
                        </div>
                    </div>
                    <div class="d-none d-md-block">
                        @yield('page-actions')
                    </div>
                </div>

                <nav aria-label="breadcrumb" class="mb-4">
                     @yield('breadcrumb')
                </nav>

                <div class="mb-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>Validation Error!</strong>
                            </div>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
                            <button type="button" class="btn-close" data-coreui-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>

                <div class="mb-4">
                    @yield('content')
                </div>

                @yield('extra-content')
            </div>
        </div>

        @include('partials.footer')
        
        @include('partials.accessibility')
        @include('partials.chatbot')

    </div>

    <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.3.1/dist/js/coreui.bundle.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/simplebar@latest/dist/simplebar.min.js"></script>

    @yield('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const headerToggler = document.getElementById('header-toggler');
            const sidebarElement = document.getElementById('sidebar');
            
            if (headerToggler && sidebarElement) {
                headerToggler.addEventListener('click', function(event) {
                    event.preventDefault();
                    console.log('Sidebar toggler clicked');
                    
                    if (typeof coreui !== 'undefined') {
                        // Use CoreUI API
                        const sidebar = coreui.Sidebar.getOrCreateInstance(sidebarElement);
                        sidebar.toggle();
                        console.log('CoreUI sidebar toggled');
                    } else {
                        // Fallback if CoreUI is not loaded
                        console.warn('CoreUI not defined, using fallback');
                        if (window.getComputedStyle(sidebarElement).display === 'none') {
                            sidebarElement.classList.add('show'); // Mobile
                            sidebarElement.classList.remove('hide'); // Desktop
                        } else {
                            sidebarElement.classList.remove('show'); // Mobile
                            sidebarElement.classList.add('hide'); // Desktop
                        }
                    }
                });
            } else {
                console.error('Sidebar or toggler not found');
            }
        });
    </script>
</body>
</html>
@php $role = auth()->user()->role ?? 'student'; @endphp

<div class="sidebar border-end" id="sidebar">

    {{-- 1. HEADER --}}
    <div class="sidebar-header border-bottom">
        <div class="sidebar-brand d-flex align-items-center gap-2">
            <i class="fas fa-book-open fa-xl text-primary"></i>
            <span class="fw-bold">{{ __('messages.lms_portal') }}</span>
        </div>
    </div>

    {{-- 2. NAVIGATION --}}
    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>

        {{-- COMMON: Dashboard --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="nav-icon fas fa-home"></i>
                <span>{{ __('messages.dashboard') }}</span>
            </a>
        </li>

        {{-- COMMON: Notifications --}}
        

        {{-- ========================================== --}}
        {{-- STUDENT MENU --}}
        {{-- ========================================== --}}
        @if ($role === 'student')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('notifications.index') ? 'active' : '' }}"
                    href="{{ route('notifications.index') }}">
                    <i class="nav-icon fas fa-bell"></i>
                    <span>{{ __('messages.notifications') }}</span>
                    @php
                        $unreadCount = auth()->user()->unreadNotifications->count();
                    @endphp
                    @if ($unreadCount > 0)
                        <span class="badge badge-sm bg-danger ms-auto">{{ $unreadCount }}</span>
                    @endif
                </a>
            </li>

            <li class="nav-title">
                <i class="fas fa-graduation-cap me-2"></i>{{ __('messages.student') }}
            </li>

            {{-- My Courses --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('courses.index*') ? 'active' : '' }}"
                    href="{{ route('courses.index') }}">
                    <i class="nav-icon fas fa-book"></i>
                    <span>{{ __('messages.my_courses') }}</span>
                </a>
            </li>

            {{-- Assignments --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('assignments.*') ? 'active' : '' }}"
                    href="{{ route('assignments.index') }}">
                    <i class="nav-icon fas fa-pencil-alt"></i>
                    <span>{{ __('messages.assignments') }}</span>
                </a>
            </li>



            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student.grades.index') ? 'active' : '' }}"
                    href="{{ route('student.grades.index') }}">
                    <i class="nav-icon fas fa-chart-pie"></i>
                    <span>{{ __('messages.my_grades') }}</span>
                </a>
            </li>

            {{-- NEW: Calendar --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student.calendar*') ? 'active' : '' }}"
                    href="{{ route('student.calendar.index') }}">
                    <i class="nav-icon fas fa-calendar-alt"></i>
                    <span>{{ __('messages.calendar') }}</span>
                </a>
            </li>

            {{-- NEW: Attendance --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('student.attendance*') ? 'active' : '' }}"
                    href="{{ route('student.attendance.index') }}">
                    <i class="nav-icon fas fa-user-check"></i>
                    <span>{{ __('messages.attendance') }}</span>
                </a>
            </li>

            {{-- Announcements --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('announcements*') ? 'active' : '' }}"
                    href="{{ route('announcements.index') }}">
                    <i class="nav-icon fas fa-bullhorn"></i>
                    <span>{{ __('messages.announcements') }}</span>
                    @php
                        // Calculate total unread announcements for student
                        $totalUnread = 0;
                        if (auth()->user()->isStudent()) {
                            $enrolledCourses = auth()->user()->enrolledCourses;
                            foreach ($enrolledCourses as $course) {
                                $total = $course->announcements()->count();
                                $viewed = \Illuminate\Support\Facades\DB::table('announcement_views')
                                    ->where('user_id', auth()->user()->Academic_ID)
                                    ->whereIn('announcement_id', $course->announcements()->pluck('id'))
                                    ->count();
                                $totalUnread += $total - $viewed;
                            }
                        }
                    @endphp
                    @if ($totalUnread > 0)
                        <span class="badge badge-sm bg-danger ms-auto">{{ $totalUnread }}</span>
                    @endif
                </a>
            </li>
        @endif

        {{-- ========================================== --}}
        {{-- INSTRUCTOR MENU --}}
        {{-- ========================================== --}}
        @if ($role === 'instructor')

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('notifications.index') ? 'active' : '' }}"
                    href="{{ route('notifications.index') }}">
                    <i class="nav-icon fas fa-bell"></i>
                    <span>{{ __('messages.notifications') }}</span>
                    @php
                        $unreadCount = auth()->user()->unreadNotifications->count();
                    @endphp
                    @if ($unreadCount > 0)
                        <span class="badge badge-sm bg-danger ms-auto">{{ $unreadCount }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-title">
                <i class="fas fa-chalkboard-user me-2"></i>{{ __('messages.instructor') }}
            </li>

            {{-- Course Management Dropdown --}}
            <li
                class="nav-item nav-group {{ request()->routeIs('courses*', 'instructor.materials*', 'instructor.quizzes*', 'instructor.questions*') ? 'show' : '' }}">
                <a class="nav-link nav-group-toggle" href="javascript:void(0);">
                    <i class="nav-icon fas fa-book-open"></i>
                    <span>{{ __('messages.course_manager') }}</span>
                </a>
                <ul class="nav-group-items">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('courses.index*') ? 'active' : '' }}"
                            href="{{ route('courses.index') }}">
                            <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                            <span>{{ __('messages.my_courses') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('materials.*') ? 'active' : '' }}"
                            href="{{ route('materials.index') }}">
                            <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                            <span>{{ __('messages.materials_files') }}</span>
                        </a>
                    </li>

                </ul>
            </li>

            {{-- Student Management --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('teacher.students*') ? 'active' : '' }}"
                    href="{{ route('teacher.students.index') }}">
                    <i class="nav-icon fas fa-users"></i>
                    <span>{{ __('messages.students') }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('teacher.attendance*') ? 'active' : '' }}"
                    href="{{ route('teacher.attendance.index') }}">
                    <i class="nav-icon fas fa-calendar-check"></i>
                    <span>{{ __('messages.attendance') }}</span>
                </a>
            </li>

            {{-- Assessment --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('assignments.*') ? 'active' : '' }}"
                    href="{{ route('assignments.index') }}">
                    <i class="nav-icon fas fa-pencil-alt"></i>
                    <span>{{ __('messages.assignments') }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('submissions.index') ? 'active' : '' }}"
                    href="{{ route('submissions.index') }}">
                    <i class="nav-icon fas fa-inbox"></i>
                    <span>{{ __('messages.submissions') }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('instructor.gradebook*') ? 'active' : '' }}"
                    href="{{ route('instructor.gradebook.index') }}">
                    <i class="nav-icon fas fa-table"></i>
                    <span>{{ __('messages.gradebook') }}</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('announcements*') ? 'active' : '' }}"
                    href="{{ route('announcements.index') }}">
                    <i class="nav-icon fas fa-bullhorn"></i>
                    <span>{{ __('messages.announcements') }}</span>
                </a>
            </li>
        @endif

        {{-- ========================================== --}}
        {{-- ADMIN MENU --}}
        {{-- ========================================== --}}
        @if ($role === 'admin')
            <li class="nav-title">
                <i class="fas fa-shield-alt me-2"></i>{{ __('messages.admin') }}
            </li>

            {{-- User Management --}}
            <li class="nav-item nav-group {{ request()->routeIs('admin.users*') ? 'show' : '' }}">
                <a class="nav-link nav-group-toggle" href="javascript:void(0);">
                    <i class="nav-icon fas fa-users"></i>
                    <span>{{ __('messages.users') }}</span>
                </a>
                <ul class="nav-group-items">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}"
                            href="{{ route('users.index') }}">
                            <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                            <span>{{ __('messages.all_users') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('users.create') ? 'active' : '' }}"
                            href="{{ route('users.create') }}">
                            <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                            <span>{{ __('messages.add_user') }}</span>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Course Management --}}
            <li class="nav-item nav-group {{ request()->routeIs('admin.courses*') ? 'show' : '' }}">
                <a class="nav-link nav-group-toggle" href="javascript:void(0);">
                    <i class="nav-icon fas fa-book-open"></i>
                    <span>{{ __('messages.courses') }}</span>
                </a>
                <ul class="nav-group-items">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('courses.index') ? 'active' : '' }}"
                            href="{{ route('courses.index') }}">
                            <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                            <span>{{ __('messages.all_courses') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('courses.create') ? 'active' : '' }}"
                            href="{{ route('courses.create') }}">
                            <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                            <span>{{ __('messages.add_course') }}</span>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- NEW: Organization (Semesters/Depts) --}}
            {{-- NEW: Organization (Semesters/Depts) --}}
            {{-- This group will be dynamically populated in the next step --}}
            <li class="nav-item nav-group {{ request()->routeIs('admin.terms*', 'departments*') ? 'show' : '' }}">
                <a class="nav-link nav-group-toggle" href="javascript:void(0);">
                    <i class="nav-icon fas fa-building-columns"></i>
                    <span>{{ __('messages.organization') }}</span>
                </a>
                <ul class="nav-group-items">
                    {{-- This is where the 'Semesters' and 'Departments' links go --}}

                    {{-- Semesters Link --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('semesters.index') ? 'active' : '' }}"
                            href="{{ route('semesters.index') }}">
                            <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                            <span>{{ __('messages.semesters') }}</span>
                        </a>
                    </li>

                    {{-- Departments Link (Actual working link) --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('departments.index') ? 'active' : '' }}"
                            href="{{ route('departments.index') }}">
                            <span class="nav-icon"><span class="nav-icon-bullet"></span></span>
                            <span>{{ __('messages.departments') }}</span>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- NEW: Logs & Notices --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('logs.*') ? 'active' : '' }}"
                    href="{{ route('logs.index') }}">
                    <i class="nav-icon fas fa-history"></i>
                    <span>{{ __('messages.audit_logs') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('notifications.create') ? 'active' : '' }}"
                    href="{{ route('notifications.create') }}">
                    <i class="nav-icon fas fa-bullhorn"></i>
                    <span>{{ __('messages.global_notices') }}</span>
                </a>
            </li>

            {{-- Reports & Settings --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.reports.index') ? 'active' : '' }}"
                    href="{{ route('admin.reports.index') }}">
                    <i class="nav-icon fas fa-chart-bar"></i>
                    <span>{{ __('messages.reports') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}"
                    href="{{ route('admin.settings.index') }}">
                    <i class="nav-icon fas fa-cog"></i>
                    <span>{{ __('messages.settings') }}</span>
                </a>
            </li>
        @endif

        {{-- ========================================== --}}
        {{-- FOOTER / ACCOUNT --}}
        {{-- ========================================== --}}

        <li class="nav-title mt-4">{{ __('messages.account') }}</li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('profile*') ? 'active' : '' }}"
                href="{{ route('profile.edit') }}">
                <i class="nav-icon fas fa-user-circle"></i>
                <span>{{ __('messages.profile') }}</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <span>{{ __('messages.log_out') }}</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>

    {{-- 3. FOOTER --}}
    <div class="sidebar-footer border-top">
        <div class="d-flex align-items-center gap-2 flex-grow-1">
            <div class="small">
                <div class="fw-bold">{{ auth()->user()->name }}</div>
                <div class="text-muted text-capitalize">{{ $role }}</div>
            </div>
        </div>

    </div>

</div>

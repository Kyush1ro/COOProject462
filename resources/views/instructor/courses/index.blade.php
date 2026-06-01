@extends('layouts.dashboard')

@section('title', 'Student Courses')
@section('page-title','My Courses')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-book"></i>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Course Title</th>
                                <th>Code</th>
                                <th class="text-center" style="width: 250px;">Actions</th>
                            </tr>
                        </thead>
                        
                        {{-- @forelse replaces @foreach + @if(empty) --}}
                        <tbody>
                            @forelse($courses as $course)
                            <tr>
                                {{-- Added 'text-dark' so the title is visible --}}
                                <td class="align-middle text-dark">
                                    <strong>{{ $course->title }}</strong>
                                </td>
                                
                                <td class="align-middle">
                                    <span class="badge badge-secondary text-dark">{{ $course->course_code }}</span>
                                </td>
                                
                                <td class="text-center align-middle text-dark">
                                    
                                    {{-- 1. ADMIN / INSTRUCTOR LOGIC --}}
                                    @if(!Auth::user()->isStudent()) 
                                        <a href="{{ route('courses.show', $course->id) }}" class="btn btn-sm btn-ghost-info">
                                            <i class="fa fa-eye"></i> View Details
                                        </a>
                                    
                                    {{-- 2. STUDENT LOGIC --}}
                                    @else
                                        {{-- ONLY Show Actions if Enrolled --}}
                                        @if(Auth::user()->enrolledCourses->contains($course->id))
                                            
                                            <div class="d-flex justify-content-center align-items-center gap-2 text-dark">
                                                {{-- View Details --}}
                                                <a href="{{ route('courses.show', $course->id) }}" class="btn btn-sm btn-primary me-2 ">
                                                    View
                                                </a>

                                                {{-- Drop / Unenroll Action --}}
                                                {{-- Note: We pass $course->id, NOT Auth::id(), so the controller knows WHICH course to drop --}}
                                                <form action="" method="POST" onsubmit="return confirm('Are you sure you want to drop this course? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger text-white">
                                                        <i class="fa fa-trash"></i> Drop
                                                    </button>
                                                </form>
                                            </div>

                                        @else
                                            {{-- User is NOT enrolled --}}
                                            <span class="text-muted font-italic small text-dark">Not Enrolled</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            
                            @empty
                            {{-- This block runs automatically if $courses is empty --}}
                            <tr>
                                <td colspan="3" class="text-center text-muted p-4">
                                    <i class="fa fa-folder-open-o fa-2x mb-2"></i><br>
                                    <h4>No courses found.</h4>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
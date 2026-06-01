<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Course Logic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">

    <div class="container">
        <h1>Testing Course View Logic</h1>
        <p>Logged in as: <strong>{{ Auth::user()->name }}</strong> (Role: {{ Auth::user()->role }})</p>
        <hr>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Course Title</th>
                    <th>Code</th>
                    <th>Action (Your Logic)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses as $course)
                <tr>
                    <td>{{ $course->title }}</td>
                    <td>{{ $course->course_code }}</td>
                    
                    {{-- YOUR LOGIC SNIPPET --}}
                    <td class="p-2">
                        {{-- 1. Button for ADMIN / INSTRUCTOR --}}
                        @if(!Auth::user()->isStudent())
                            <a href="{{ route('courses.show', $course->id) }}" class="btn btn-sm btn-secondary">
                                View (Instructor/Admin)
                            </a>
                        
                        {{-- 2. Logic for STUDENT --}}
                        @else
                            {{-- Check if the logged-in student is ALREADY enrolled --}}
                            @if(Auth::user()->enrolledCourses->contains($course->id))
                                
                                <span class="badge bg-success mb-1">Enrolled</span>
                                <a href="{{ route('courses.show', $course->id) }}" class="btn btn-sm btn-primary">
                                    Enter Course
                                </a>

                            @else
                                
                                <form action="{{ route('courses.enroll', $course->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">
                                        Enroll Now
                                    </button>
                                </form>

                            @endif
                        @endif
                    </td>
                    {{-- END SNIPPET --}}

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>
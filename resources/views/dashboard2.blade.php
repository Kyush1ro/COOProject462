@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
<div class="card">
    <div class="card-header">Welcome to LMS Dashboard </div>
    <div class="card-body">
        <p>Here you can manage courses, students, and instructors.</p>
       <a href="route('logout')">log out</a>
    </div>
</div>
@endsection

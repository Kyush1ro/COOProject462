@extends('layouts.dashboard')

@section('title', __('messages.course_materials'))
@section('page-title', __('messages.course_materials'))

@section('content')
<div class="row">
    <div class="col-12">
        
        {{-- STATE 1: COURSE SELECTION LIST --}}
        @if(!isset($selectedCourse))
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">{{ __('messages.select_course_manage') }}</h4>
            </div>

            <div class="row">
                @forelse($courses as $course)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm hover-shadow transition-all">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title text-primary mb-0">{{ $course->title }}</h5>
                                    <span class="badge bg-secondary-subtle text-body-emphasis border">{{ $course->course_code }}</span>
                                </div>
                                <p class="card-text text-muted small mb-3">
                                    {{ Str::limit($course->description, 80) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <span class="text-muted small">
                                        <i class="fas fa-file-alt me-1"></i> {{ $course->materials_count ?? 0 }} {{ __('messages.files') }}
                                    </span>
                                    <a href="{{ route('materials.index', ['course_id' => $course->id]) }}" class="btn btn-outline-primary btn-sm stretched-link">
                                        {{ __('messages.manage_files') }} <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i> {{ __('messages.not_teaching_courses') }}
                        </div>
                    </div>
                @endforelse
            </div>

        {{-- STATE 2: MATERIALS LIST FOR SELECTED COURSE --}}
        @else
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <a href="{{ route('materials.index') }}" class="text-decoration-none text-muted small mb-1 d-block">
                        <i class="fas fa-arrow-left me-1"></i> {{ __('messages.back_to_courses') }}
                    </a>
                    <h4 class="mb-0">
                        <span class="text-primary">{{ $selectedCourse->title }}</span> 
                        <span class="text-muted fw-light">{{ __('messages.materials') }}</span>
                    </h4>
                </div>
                <button class="btn btn-success text-white" data-coreui-toggle="modal" data-coreui-target="#uploadModal">
                    <i class="fas fa-upload me-1"></i> {{ __('messages.upload_material') }}
                </button>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 40%;">{{ __('messages.title') }}</th>
                                    <th style="width: 15%;">{{ __('messages.type') }}</th>
                                    <th style="width: 20%;">{{ __('messages.uploaded') }}</th>
                                    <th style="width: 25%;">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($selectedCourse->materials as $material)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-2 text-secondary">
                                                    @if(in_array($material->file_type, ['pdf']))
                                                        <i class="fas fa-file-pdf fa-lg text-danger"></i>
                                                    @elseif(in_array($material->file_type, ['doc', 'docx']))
                                                        <i class="fas fa-file-word fa-lg text-primary"></i>
                                                    @elseif(in_array($material->file_type, ['ppt', 'pptx']))
                                                        <i class="fas fa-file-powerpoint fa-lg text-warning"></i>
                                                    @elseif(in_array($material->file_type, ['zip', 'rar']))
                                                        <i class="fas fa-file-archive fa-lg text-muted"></i>
                                                    @else
                                                        <i class="fas fa-file fa-lg text-secondary"></i>
                                                    @endif
                                                </div>
                                                <span class="fw-bold">{{ $material->title }}</span>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-secondary-subtle text-body-emphasis border">{{ strtoupper($material->file_type) }}</span></td>
                                        <td>{{ $material->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('materials.download', $material->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <form action="{{ route('materials.destroy', $material->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.delete_material_confirm') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <div class="mb-2"><i class="fas fa-folder-open fa-2x text-gray-300"></i></div>
                                            {{ __('messages.no_materials_uploaded') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Upload Modal --}}
            <div class="modal fade" id="uploadModal" tabindex="-1">
                <div class="modal-dialog">
                    <form action="{{ route('courses.materials.store', $selectedCourse->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ __('messages.upload_material_for') }} {{ $selectedCourse->course_code }}</h5>
                                <button type="button" class="btn-close" data-coreui-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.title') }}</label>
                                    <input type="text" name="title" class="form-control" required placeholder="e.g., Lecture 1 Slides">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('messages.file') }}</label>
                                    <input type="file" name="file" class="form-control" required>
                                    <div class="form-text">{{ __('messages.allowed_file_types') }}</div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">{{ __('messages.close') }}</button>
                                <button type="submit" class="btn btn-primary">{{ __('messages.upload') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .hover-shadow:hover {
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .transition-all {
        transition: all 0.3s ease;
    }
</style>
@endsection
@extends('backend.layouts.app')

@section('title', __('h_news.add_news'))

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class=" mb-2 d-flex justify-content-between">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('h_news.add_news') }}</h1>
                </div>
                <div class="">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('news.index') }}">{{ __('h_news.news') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('h_news.add') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                          <div class="d-flex justify-content-between">
                            <h3 class="card-title">{{ __('h_news.add_new_news') }}</h3>
                            <div class="card-tools">
                                <a href="{{ route('news.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> {{ __('h_news.back_to_list') }}
                                </a>
                            </div>
                        </div>
                        </div>

                        <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                @if($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="title">{{ __('h_news.title_required') }} <span class="text-danger">{{ __('h_news.required') }}</span></label>
                                    <input type="text"
                                           class="form-control @error('title') is-invalid @enderror"
                                           id="title"
                                           name="title"
                                           value="{{ old('title') }}"
                                           required>
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description">{{ __('h_news.description_required') }} <span class="text-danger">{{ __('h_news.required') }}</span></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description"
                                              name="description"
                                              rows="4"
                                              required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="news_date">{{ __('h_news.news_date_required') }} <span class="text-danger">{{ __('h_news.required') }}</span></label>
                                    <input type="date"
                                           class="form-control @error('news_date') is-invalid @enderror"
                                           id="news_date"
                                           name="news_date"
                                           value="{{ old('news_date', date('Y-m-d')) }}"
                                           required>
                                    @error('news_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="image">{{ __('h_news.image_field') }}</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file"
                                                   class="custom-file-input @error('image') is-invalid @enderror"
                                                   id="image"
                                                   name="image"
                                                   accept="image/*">
                                            <label class="custom-file-label" for="image">{{ __('h_news.choose_file') }}</label>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">{{ __('h_news.image_help_create') }}</small>
                                    @error('image')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror

                                    <!-- Selected File Name Display -->
                                    <div class="mt-2" id="selectedFileName" style="display: none;">
                                        <small class="text-info">
                                            <i class="fas fa-file-image mr-1"></i>
                                            {{ __('h_news.selected') }}: <span id="fileNameText"></span>
                                        </small>
                                    </div>

                                    <!-- Image Preview -->
                                    <div class="mt-2" id="imagePreview" style="display: none;">
                                        <img id="preview" src="" alt="Image Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary float-right" >
                                    <i class="fas fa-save"></i> {{ __('h_news.save_news') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // File input change handler
        $('#image').on('change', function() {
            const file = this.files[0];

            if (file) {
                // Update the custom file label
                const fileName = file.name;
                $(this).next('.custom-file-label').addClass("selected").html(fileName);

                // Show selected file name
                $('#fileNameText').text(fileName);
                $('#selectedFileName').show();

                // Image preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview').attr('src', e.target.result);
                    $('#imagePreview').show();
                }
                reader.readAsDataURL(file);
            } else {
                // Reset everything if no file selected
                $(this).next('.custom-file-label').removeClass("selected").html('{{ __('h_news.choose_file') }}');
                $('#selectedFileName').hide();
                $('#imagePreview').hide();
            }
        });

        // Additional fallback for browsers that don't support jQuery properly
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const label = this.nextElementSibling;

            if (file && label) {
                label.textContent = file.name;
                label.classList.add('selected');
            } else if (label) {
                label.textContent = '{{ __('h_news.choose_file') }}';
                label.classList.remove('selected');
            }
        });
    });
</script>

@push('styles')
<style>
    .custom-file-label.selected {
        color: #495057;
        font-weight: 500;
    }

    .custom-file-label::after {
        content: "Browse";
    }

    #selectedFileName {
        padding: 0.5rem;
        background-color: #e3f2fd;
        border-left: 3px solid #2196f3;
        border-radius: 0.25rem;
    }
</style>
@endpush
@endpush
@endsection

@extends('backend.layouts.app')

@section('title', __('h_news.edit_news'))

@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class=" mb-2 d-flex justify-content-between">
                <div class="">
                    <h1 class="m-0">{{ __('h_news.edit_news') }}</h1>
                </div>
                <div class="">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{ __('dashboard.home') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('h_news.edit') }}</li>
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
                            <h3 class="card-title">{{ __('h_news.edit_news_title', ['title' => $news->title]) }}</h3>
                            <div class="card-tools">
                                <a href="{{ route('news.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> {{ __('h_news.back_to_list') }}
                                </a>
                            </div>
                            </div>
                        </div>

                        <form action="{{ route('news.update', $news) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
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
                                           value="{{ old('title', $news->title) }}"
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
                                              required>{{ old('description', $news->description) }}</textarea>
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
                                           value="{{ old('news_date', $news->news_date->format('Y-m-d')) }}"
                                           required>
                                    @error('news_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="image">{{ __('h_news.image_field') }}</label>
                                    @if($news->image)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $news->image) }}"
                                                 alt="{{ $news->title }}"
                                                 class="img-thumbnail"
                                                 style="max-width: 200px;">
                                            <p class="text-muted mt-1">{{ __('h_news.current_image') }}</p>
                                        </div>
                                    @endif
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
                                    <small class="form-text text-muted">{{ __('h_news.image_help_edit') }}</small>
                                    @error('image')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>


                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary float-right">
                                    <i class="fas fa-save"></i> {{ __('h_news.update_news') }}
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
// File input label update
$('.custom-file-input').on('change', function() {
    let fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').addClass("selected").html(fileName);
});
</script>
@endpush
@endsection

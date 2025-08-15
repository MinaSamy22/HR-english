@extends('backend.layouts.app')

@section('title', __('h_news.view_news'))

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class=" mb-2 d-flex justify-content-between">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('h_news.view_news') }}</h1>
                </div>
                <div class="">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('news.index') }}">{{ __('h_news.news') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('h_news.view') }}</li>
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
                            <h3 class="card-title">{{ $news->title }}</h3>
                            <div class="card-tools">
                                <a href="{{ route('news.edit', $news) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> {{ __('h_news.edit_btn') }}
                                </a>

                            </div>
                         </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4>{{ $news->title }}</h4>
                                    <p class="text-muted">
                                        <i class="fas fa-calendar"></i> {{ $news->formattedDate }}
                                        @if($news->company)
                                            | <i class="fas fa-building"></i> {{ $news->company->name }}
                                        @endif
                                    </p>

                                    <div class="mt-3">
                                        <h5>{{ __('h_news.description') }}:</h5>
                                        <p>{{ $news->description }}</p>
                                    </div>


                                </div>

                                <div class="col-md-4">
                                    @if($news->hasImage())
                                        <div class="text-center">
                                            @if($news->imageIsSvg)
                                                <div class="img-fluid rounded shadow" style="max-height: 300px; overflow: hidden;">
                                                    <img src="{{ $news->imageUrl }}"
                                                         alt="{{ $news->title }}"
                                                         class="img-fluid rounded shadow"
                                                         style="max-height: 300px; width: auto;">
                                                </div>
                                            @else
                                                <img src="{{ $news->imageUrl }}"
                                                     alt="{{ $news->title }}"
                                                     class="img-fluid rounded shadow"
                                                     style="max-height: 300px; width: auto;">
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-center p-4 bg-light rounded">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                            <p class="text-muted mt-2">{{ __('h_news.no_image_available') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

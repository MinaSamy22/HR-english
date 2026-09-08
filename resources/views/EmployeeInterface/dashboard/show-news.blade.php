@extends('EmployeeInterface.layouts.app')

@section('title', __('h_news.view_news'))

@section('content')
<link rel="stylesheet" href="{{ asset('dist/css/news-show.css') }}">

    <div class="content-wrapper">

        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ __('h_news.view_news') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('employee.home') }}">{{ __('h_news.news') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('h_news.view') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Content ────────────────────────────────────── --}}
        <section class="content">
            <div class="container-fluid">

                <div class="row">

                    {{-- ── Main article ────────────────────── --}}
                    <div class="col-12 mb-4 fade-in-up" style="animation-delay:.1s;">
                        <div class="news-article-card card">

                            {{-- Card Header --}}
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h2 class="news-article-title mb-0">
                                            {{ $news->title }}
                                        </h2>
                                    </div>
                                    <div class="card-tools">
                                        <a href="{{ route('employee.home') }}" class="btn btn-primary rounded-pill" title="{{ __('h_news.news') }}">
                                            <i class="fas fa-arrow-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @if($news->hasImage())
                                <div class="news-hero">
                                    <a href="{{ $news->imageUrl }}" target="_blank" rel="noopener noreferrer"
                                        title="{{ __('h_news.view_btn') }}">
                                        <img src="{{ $news->imageUrl }}" alt="{{ $news->title }}" loading="lazy"
                                            decoding="async">
                                    </a>
                                </div>
                            @else
                                <div class="no-image-placeholder">
                                    <i class="fas fa-image fa-3x"></i>
                                    <span>{{ __('h_news.no_image_available') }}</span>
                                </div>
                            @endif

                            {{-- Body --}}
                            <div class="news-article-body">

                                {{-- Meta badges --}}
                                <div class="news-meta">
                                    @if($news->formattedDate)
                                        <span class="meta-badge date">
                                            <i class="fas fa-calendar-alt"></i> {{ $news->formattedDate }}
                                        </span>
                                    @endif
                                    @if($news->company)
                                        <span class="meta-badge company">
                                            <i class="fas fa-building"></i> {{ $news->company->name }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Description --}}
                                <p class="news-description-label">
                                    <i class="fas fa-align-left mr-1"></i>{{ __('h_news.description') }}
                                </p>
                                <div class="news-description-body">{{ $news->description }}</div>

                            </div>
                        </div>
                    </div>

                </div>{{-- /row --}}
            </div>
        </section>

    </div>
@endsection

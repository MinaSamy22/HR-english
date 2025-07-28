@extends('backend.layouts.app')

@section('title', 'View News')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">View News</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('news.index') }}">News</a></li>
                        <li class="breadcrumb-item active">View</li>
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
                            <h3 class="card-title">{{ $news->title }}</h3>
                            <div class="card-tools">
                                <a href="{{ route('news.edit', $news) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="{{ route('news.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Back to List
                                </a>
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
                                        <h5>Description:</h5>
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
                                            <p class="text-muted mt-2">No image available</p>
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

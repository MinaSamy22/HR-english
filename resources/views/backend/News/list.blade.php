@extends('backend.layouts.app')


@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Company News</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">News</li>
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
                            <h3 class="card-title">News Management</h3>
                            <div class="card-tools">
                                <a href="{{ route('news.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Add News
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th width="5%">ID</th>
                                            <th width="15%">Image</th>
                                            <th width="25%">Title</th>
                                            <th width="30%">Description</th>
                                            <th width="10%">Date</th>
                                            <th width="15%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($news as $item)
                                            <tr>
                                                <td>{{ $item->id }}</td>
                                                <td>
                                                    @if($item->image)
                                                       <img src="{{ $item->image_url }}"
     alt="{{ $item->title }}"
     class="img-fluid"
     style="max-width: 60px; max-height: 60px;">

                                                    @else
                                                        <span class="badge badge-secondary">No Image</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->title }}</td>
                                                <td>{{ Str::limit($item->description, 100) }}</td>
                                                <td>{{ $item->news_date->format('Y-m-d') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('news.show', $item) }}"
                                                           class="btn btn-info rounded-pill" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('news.edit', $item) }}"
                                                           class="btn btn-primary rounded-pill" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('news.destroy', $item) }}"
                                                              method="POST"
                                                              style="display: inline;"
                                                              onsubmit="return confirm('Are you sure you want to delete this news?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    class="btn btn-danger rounded-pill"
                                                                    title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No news found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if($news->hasPages())
                                <div class="d-flex justify-content-center">
                                    {{ $news->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

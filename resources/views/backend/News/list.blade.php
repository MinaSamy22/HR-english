@extends('backend.layouts.app')


@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class=" mb-2 d-flex justify-content-between">
                <div class="">
                    <h1 class="m-0">{{ __('dashboard.company_news') }}</h1>
                </div>
                <div class="">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{ __('dashboard.home') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('dashboard.news') }}</li>
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
                                <h3 class="card-title">{{ __('dashboard.news_management') }}</h3>
                                <div class="card-tools">
                                    <a href="{{ route('news.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> {{ __('dashboard.add_news') }}
                                    </a>
                                </div>
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
                                            <th width="5%">{{ __('h_news.id') }}</th>
                                            <th width="15%">{{ __('h_news.image') }}</th>
                                            <th width="25%">{{ __('h_news.title') }}</th>
                                            <th width="30%">{{ __('h_news.description') }}</th>
                                            <th width="10%">{{ __('h_news.date') }}</th>
                                            <th width="15%">{{ __('h_news.actions') }}</th>
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
                                                        <span class="badge badge-secondary">{{ __('h_news.no_image') }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->title }}</td>
                                                <td>{{ Str::limit($item->description, 100) }}</td>
                                                <td>{{ $item->news_date->format('Y-m-d') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('news.show', $item) }}"
                                                           class="btn btn-info rounded-pill" title="{{ __('h_news.view_btn') }}">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('news.edit', $item) }}"
                                                           class="btn btn-primary rounded-pill" title="{{ __('h_news.edit_btn') }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('news.destroy', $item) }}"
                                                              method="POST"
                                                              style="display: inline;"
                                                              onsubmit="return confirm('{{ __('h_news.delete_confirm') }}')">
                                                            @csrf
                                                            @method('DELETE')
                                                             <button type="button" class="btn btn-danger rounded-pill delete-btn"
                                                                    data-id="{{ $item->id }}" title="{{ __('h_news.delete_btn') }}">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">{{ __('h_news.no_news_found') }}</td>
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
@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const deleteTranslations = {
        delete: "{{ __('dashboard.delete') }}",
        confirmation: "{{ __('dashboard.delete_confirmation') }}",
        cancel: "{{ __('dashboard.cancel') }}",
        deleted: "{{ __('dashboard.deleted') }}!",
        success: "{{ __('dashboard.delete_success') }}",
        error: "{{ __('dashboard.error') }}",
        failed: "{{ __('dashboard.delete_failed') }}",
        deleteUrl: "{{ url('admin/news') }}",
        token: '{{ csrf_token() }}'
    };
</script>

<script src="{{ asset('dist/js/news.js') }}?v=1"></script>
@endsection

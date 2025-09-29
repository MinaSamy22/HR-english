{{-- resources/views/backend/performance-criteria/index.blade.php --}}
@extends('backend.layouts.app')

@section('title', __('h_criteria.title'))

@section('content')
<div class="content-wrapper" style="background-image: url('{{ asset('/dist/img/dashboard.jpg') }}'); background-size: cover; background-position: center;">
    <div class="content-header">
        <div class="container-fluid">
            <div class=" mb-2 d-flex justify-content-between">
                <div class="col-sm-6">
                    <h1>{{ __('h_criteria.title') }}</h1>
                </div>
                <div class="">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">{{ __('h_criteria.dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('h_criteria.title') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                  <div class="d-flex justify-content-between">
                    <h3 class="card-title">{{ __('h_criteria.evaluation_criteria') }}</h3>
                    <div class="card-tools">
    <a href="{{ route('performance-criteria.create') }}" class="btn btn-primary btn-sm rounded-pill">
        <i class="fas fa-plus"></i> {{ __('h_criteria.add_criteria') }}
    </a>
</div>

                  </div>
                </div>

                <div class="card-body">
                    @if($criteria->count() > 0)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>{{ __('h_criteria.info') }}:</strong> {{ __('h_criteria.note_info') }}
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="criteria-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('h_criteria.criteria_name') }}</th>
                                        <th>{{ __('h_criteria.description') }}</th>
                                        <th>{{ __('h_criteria.status') }}</th>
                                        <th width="120">{{ __('h_criteria.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="sortable-criteria">
                                    @foreach($criteria as $criterion)
                                        <tr data-id="{{ $criterion->id }}">
                                            <td>
                                                <strong>{{ $criterion->name }}</strong>
                                            </td>
                                            <td>
                                                {{ Str::limit($criterion->description, 100) ?: '-' }}
                                            </td>

                                            <td>
                                                <span class="badge badge-{{ $criterion->is_active ? 'success' : 'secondary' }}">
                                                    {{ $criterion->is_active ? __('h_criteria.active') : __('h_criteria.inactive') }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('performance-criteria.edit', $criterion->id) }}"
                                                       class="btn btn-primary rounded-pill" title="{{ __('h_criteria.edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('performance-criteria.destroy', $criterion->id) }}" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger rounded-pill delete-btn"
                                                                    data-id="{{ $criterion->id }}" title="{{ __('h_criteria.delete') }}">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $criteria->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-list-ul fa-3x text-muted mb-3"></i>
                            <h5>{{ __('h_criteria.no_criteria_found') }}</h5>
                            <p class="text-muted">{{ __('h_criteria.no_criteria_description') }}</p>
                            <a href="{{ route('performance-criteria.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> {{ __('h_criteria.add_criteria') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>

<script>
    const criteriaTranslations = {
        delete: "{{ __('dashboard.delete') }}",
        confirmation: "{{ __('dashboard.delete_confirmation') }}",
        cancel: "{{ __('dashboard.cancel') }}",
        deleted: "{{ __('dashboard.deleted') }}!",
        successMsg: "{{ __('dashboard.delete_success') }}",
        error: "{{ __('dashboard.error') }}",
        failed: "{{ __('dashboard.delete_failed') }}",
        success: "{{ __('h_criteria.success') }}",
        warning: "{{ __('h_criteria.warning') }}",
        info: "{{ __('h_criteria.info') }}",
        orderUpdated: "{{ __('h_criteria.order_updated') }}",
        orderFailed: "{{ __('h_criteria.order_update_failed') }}",
        restoreConfirm: "{{ __('h_criteria.restore_order_confirm') }}",
        deleteUrl: "{{ url('admin/performance-criteria') }}",
        updateUrl: "{{ route('performance-criteria.update-order') }}",
        token: '{{ csrf_token() }}'
    };
</script>

<script src="{{ asset('dist/js/performance_criteria.js') }}"></script>
@endsection


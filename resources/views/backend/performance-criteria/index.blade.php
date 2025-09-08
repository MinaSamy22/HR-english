{{-- resources/views/backend/performance-criteria/index.blade.php --}}
@extends('backend.layouts.app')

@section('title', __('h_criteria.title'))

@section('content')
<div class="content-wrapper">
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
                                                        <button type="submit"
                                                                onclick="return confirm('{{ __('h_criteria.delete_confirm') }}')"
                                                                class="btn btn-danger rounded-pill"
                                                                title="{{ __('h_criteria.delete') }}">
                                                            <i class="fas fa-trash"></i>
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

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
<script>
$(document).ready(function() {
    // Make table sortable
    const sortableList = document.getElementById('sortable-criteria');
    if (sortableList) {
        new Sortable(sortableList, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onStart: function(evt) {
                evt.item.style.opacity = '0.5';
            },
            onEnd: function(evt) {
                evt.item.style.opacity = '1';

                // Get all rows and create order data
                const rows = Array.from(sortableList.querySelectorAll('tr[data-id]'));
                const orderData = rows.map((row, index) => ({
                    id: parseInt(row.getAttribute('data-id')),
                    sort_order: index + 1
                }));

                console.log('Sending order data:', orderData);

                // Send AJAX request to update order
                $.ajax({
                    url: '{{ route("performance-criteria.update-order") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        criteria: orderData
                    },
                    success: function(response) {
                        console.log('Order updated successfully:', response);
                        showToast('{{ __("h_criteria.order_updated") }}', 'success');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating order:', error);
                        console.error('Response:', xhr.responseText);
                        showToast('{{ __("h_criteria.order_update_failed") }}', 'error');

                        // Refresh page to restore original order
                        setTimeout(() => {
                            if (confirm('{{ __("h_criteria.restore_order_confirm") }}')) {
                                window.location.reload();
                            }
                        }, 2000);
                    }
                });
            }
        });
    }

    // Add hover effects to sortable rows
    $('#sortable-criteria').on('mouseenter', 'tr', function() {
        $(this).addClass('table-active');
    }).on('mouseleave', 'tr', function() {
        $(this).removeClass('table-active');
    });
});

// Helper function to show toast notifications
function showToast(message, type = 'info') {
    const toastClass = {
        'success': 'alert-success',
        'error': 'alert-danger',
        'warning': 'alert-warning',
        'info': 'alert-info'
    }[type] || 'alert-info';

    const toastTitle = {
        'success': '{{ __("h_criteria.success") }}',
        'error': '{{ __("h_criteria.error") }}',
        'warning': '{{ __("h_criteria.warning") }}',
        'info': '{{ __("h_criteria.info") }}'
    }[type] || '{{ __("h_criteria.info") }}';

    const toast = $(`
        <div class="alert ${toastClass} alert-dismissible fade show position-fixed"
             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <strong>${toastTitle}</strong> ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `);

    $('body').append(toast);

    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.alert('close');
    }, 5000);
}
</script>

<style>
/* Custom styles for sortable table */
.sortable-ghost {
    opacity: 0.4;
    background-color: #f8f9fa;
}

.sortable-chosen {
    background-color: #e3f2fd;
}

.sortable-drag {
    background-color: #fff;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    transform: rotate(5deg);
}

.drag-handle:hover {
    color: #007bff !important;
    cursor: grab;
}

.drag-handle:active {
    cursor: grabbing;
}

/* Table row hover effects */
#criteria-table tbody tr:hover {
    background-color: rgba(0,123,255,0.05);
}

/* Loading state for buttons */
.btn-loading {
    position: relative;
    pointer-events: none;
}

.btn-loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 16px;
    height: 16px;
    margin: -8px 0 0 -8px;
    border: 2px solid transparent;
    border-top: 2px solid #ffffff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endsection

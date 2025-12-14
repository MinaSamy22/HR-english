@extends('backend.layouts.app')
@section('content')
<div class="content-wrapper" style="background-image: url('{{ asset('/dist/img/vacation.jpg') }}'); background-size: cover; background-position: center;">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('h_vacation.vacations') }}</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ url('admin/vacations/add') }}" class="btn btn-primary rounded-pill">
                        <i class="fas fa-user-plus"></i> {{ __('h_vacation.add_vacation') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            {{-- Filter Form --}}
            <div class="row">
                <section class="col-md-12">
                    <div class="card" style="background-color: rgba(255, 255, 255, 0.9); border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                        <form method="get" action="">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-2 col-sm-6">
                                        <label>{{ __('h_vacation.employee_name') }}</label>
                                        <input type="text" value="{{ Request()->name }}" name="name" class="form-control" placeholder="{{ __('h_vacation.enter_name') }}">
                                    </div>

                                    @if (session('branch_id') === null || \App\Models\Branch::find(session('branch_id'))?->is_main == 1)
                                    <div class="form-group col-md-2 col-sm-6">
                                        <label>{{ __('h_employee.branch') }}</label>
                                        <select name="filter_branch_id" class="form-control">
                                            <option value="">{{ __('h_employee.all') }}</option>
                                            @foreach($branches as $branch)
                                                <option value="{{ $branch->id }}" {{ Request()->filter_branch_id == $branch->id ? 'selected' : '' }}>
                                                    {{ $branch->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif

                                    <div class="form-group col-md-3 col-sm-6 d-flex align-items-end">
                                        <button class="btn btn-primary rounded-pill" type="submit" style="margin-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 10px;" title="{{ __('h_vacation.search') }}">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <a href="{{ url('admin/vacations') }}" class="btn btn-success rounded-pill" title="{{ __('h_vacation.reset') }}">
                                            <i class="fas fa-sync-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    @include('_message')

                    {{-- Tabs with Delete Button in Header --}}
                    <div class="card" style="background-color: rgba(255, 255, 255, 0.9); border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                            <ul class="nav nav-tabs card-header-tabs" id="vacationTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="with-balance-tab" data-toggle="tab" href="#withBalance" role="tab" aria-controls="withBalance" aria-selected="true">
                                        {{ __('h_vacation.with_balance') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="no-balance-tab" data-toggle="tab" href="#noBalance" role="tab" aria-controls="noBalance" aria-selected="false">
                                        {{ __('h_vacation.no_balance') }}
                                    </a>
                                </li>
                            </ul>
                            <div class="ml-auto">
                                <button class="btn btn-danger" id="deleteSelected">{{ __('h_vacation.delete_selection') }}</button>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="tab-content" id="vacationTabsContent">
                                {{-- With Balance --}}
                                <div class="tab-pane fade show active" id="withBalance" role="tabpanel" aria-labelledby="with-balance-tab">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="selectAllWith"></th>
                                                    <th>{{ __('h_vacation.id') }}</th>
                                                    <th>{{ __('h_vacation.employee_name') }}</th>
                                                    <th>{{ __('h_employee.branch') }}</th>
                                                    <th>{{ __('h_vacation.vacation_type') }}</th>
                                                    <th>{{ __('h_vacation.start_date') }}</th>
                                                    <th>{{ __('h_vacation.end_date') }}</th>
                                                    <th>{{ __('h_vacation.total_days') }}</th>
                                                    <th>{{ __('h_vacation.action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($withBalance as $vacation)
                                                    <tr>
                                                        <td><input type="checkbox" class="vacationCheckboxWith" value="{{ $vacation->id }}"></td>
                                                        <td>{{ $vacation->id }}</td>
                                                        <td>{{ $vacation->name }}</td>
                                                        <td>{{ $vacation->branch_name ?? __('h_dashboard.main_branch') }}</td>
<td>
    @php
        $typeTranslations = [
            'Annual' => __('h_vacation.annual_vacation'),
            'Sick' => __('h_vacation.sick_leave'),
            'Unpaid' => __('h_vacation.unpaid_leave'),
        ];
    @endphp
    {{ $typeTranslations[$vacation->vacation_type] ?? $vacation->vacation_type }}
</td>                                                            <td>{{ date('d-m-Y', strtotime($vacation->start_date)) }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($vacation->end_date)) }}</td>
                                                        <td>{{ $vacation->total }}</td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger rounded-pill delete-btn" data-id="{{ $vacation->id }}" title="{{ __('h_vacation.delete') }}">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="9" class="text-center">{{ __('h_vacation.no_vacations_found') }}</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-end p-3">
                                        {!! $withBalance->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
                                    </div>
                                </div>

                                {{-- No Balance --}}
                                <div class="tab-pane fade" id="noBalance" role="tabpanel" aria-labelledby="no-balance-tab">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="selectAllNo"></th>
                                                    <th>{{ __('h_vacation.id') }}</th>
                                                    <th>{{ __('h_vacation.employee_name') }}</th>
                                                    <th>{{ __('h_employee.branch') }}</th>
                                                    <th>{{ __('h_vacation.vacation_type') }}</th>
                                                    <th>{{ __('h_vacation.start_date') }}</th>
                                                    <th>{{ __('h_vacation.end_date') }}</th>
                                                    <th>{{ __('h_vacation.total_days') }}</th>
                                                    <th>{{ __('h_vacation.action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($noBalance as $vacation)
                                                    <tr>
                                                        <td><input type="checkbox" class="vacationCheckboxNo" value="{{ $vacation->id }}"></td>
                                                        <td>{{ $vacation->id }}</td>
                                                        <td>{{ $vacation->name }}</td>
                                                        <td>{{ $vacation->branch_name ?? __('h_dashboard.main_branch') }}</td>
<td>
    @php
        $typeTranslations = [
            'Annual' => __('h_vacation.annual_vacation'),
            'Sick' => __('h_vacation.sick_leave'),
            'Unpaid' => __('h_vacation.unpaid_leave'),
        ];
    @endphp
    {{ $typeTranslations[$vacation->vacation_type] ?? $vacation->vacation_type }}
</td>                                                        <td>{{ date('d-m-Y', strtotime($vacation->start_date)) }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($vacation->end_date)) }}</td>
                                                        <td>{{ $vacation->total }}</td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger rounded-pill delete-btn" data-id="{{ $vacation->id }}" title="{{ __('h_vacation.delete') }}">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="9" class="text-center">{{ __('h_vacation.no_vacations_found') }}</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-end p-3">
                                        {!! $noBalance->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</div>

@if(app()->getLocale() == 'ar')
<style>
    body { direction: rtl; text-align: right; }
    .float-sm-right { float: left !important; }
    .text-right { text-align: left !important; }
    .ml-auto { margin-left: 0 !important; margin-right: auto !important; }
    .mr-1, .mr-2 { margin-right: 0 !important; margin-left: 0.25rem; }
    .mr-2 { margin-left: 0.5rem !important; }
</style>
@endif
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // JS remains unchanged
    $(document).on('click', '.delete-btn', function () {
        let deleteId = $(this).data('id');
        Swal.fire({
            title: "{{ __('dashboard.delete') }}",
            text: "{{ __('dashboard.delete_confirmation') }}",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "{{ __('dashboard.delete') }}",
            cancelButtonText: "{{ __('dashboard.cancel') }}"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('admin/vacations/delete') }}/" + deleteId,
                    type: 'GET',
                    success: function () {
                        $('button.delete-btn[data-id="' + deleteId + '"]').closest('tr').fadeOut();
                        Swal.fire({ title: "{{ __('dashboard.deleted') }}!", text: "{{ __('dashboard.delete_success') }}", icon: "success", timer: 2000, showConfirmButton: false });
                    },
                    error: function () {
                        Swal.fire({ title: "{{ __('dashboard.error') }}", text: "{{ __('dashboard.delete_failed') }}", icon: "error", confirmButtonText: "{{ __('dashboard.ok') }}" });
                    }
                });
            }
        });
    });

    $('#deleteSelected').click(function() {
        var selectedIds = [];
        $('.vacationCheckboxWith:checked, .vacationCheckboxNo:checked').each(function() {
            selectedIds.push($(this).val());
        });
        if (selectedIds.length === 0) {
            Swal.fire({ title: "{{ __('dashboard.no_selection') }}", text: "{{ __('dashboard.select_items_first') }}", icon: "warning", confirmButtonText: "{{ __('dashboard.ok') }}" });
            return;
        }
        Swal.fire({
            title: "{{ __('dashboard.delete_selected') }}",
            text: "{{ __('dashboard.delete_selected_confirm') }}",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "{{ __('dashboard.delete') }}",
            cancelButtonText: "{{ __('dashboard.cancel') }}"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('admin/vacations/delete-multiple') }}",
                    type: 'POST',
                    data: { ids: selectedIds, _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        $('.vacationCheckboxWith:checked, .vacationCheckboxNo:checked').each(function() {
                            $(this).closest('tr').fadeOut();
                        });
                        $('#selectAllWith, #selectAllNo').prop('checked', false);
                        Swal.fire({ title: "{{ __('dashboard.deleted') }}!", text: "{{ __('dashboard.bulk_delete_success') }}", icon: "success", timer: 2000, showConfirmButton: false });
                    },
                    error: function() {
                        Swal.fire({ title: "{{ __('dashboard.error') }}", text: "{{ __('dashboard.bulk_delete_failed') }}", icon: "error", confirmButtonText: "{{ __('dashboard.ok') }}" });
                    }
                });
            }
        });
    });

    $('#selectAllWith').change(function() { $('.vacationCheckboxWith').prop('checked', this.checked); });
    $('#selectAllNo').change(function() { $('.vacationCheckboxNo').prop('checked', this.checked); });

    $(document).on('change', '.vacationCheckboxWith, .vacationCheckboxNo', function() {
        $('#selectAllWith').prop('checked', $('.vacationCheckboxWith:checked').length === $('.vacationCheckboxWith').length);
        $('#selectAllNo').prop('checked', $('.vacationCheckboxNo:checked').length === $('.vacationCheckboxNo').length);
    });
</script>
@endsection

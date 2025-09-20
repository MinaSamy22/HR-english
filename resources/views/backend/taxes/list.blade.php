@extends('backend.layouts.app')

@section('content')
    <!-- Meta tags for JavaScript localization -->
    <meta name="no-row-selected" content="{{ __('h_tax.no_row_selected') }}">
    <meta name="delete-selection-confirmation" content="{{ __('h_tax.delete_selection_confirmation') }}">
    <meta name="error-occurred" content="{{ __('h_tax.error_occurred') }}">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"
        style="background-image: url('{{ asset('/dist/img/additional.jpg') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ __('h_tax.taxes') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6 text-right">
                        <a href="{{ url('admin/taxes/add') }}" class="btn btn-primary rounded-pill">
                            <i class="fas fa-user-plus"></i> {{ __('h_tax.add_tax') }}
                        </a>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div><!-- /.content-header -->

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <section class="col-md-12">
                        <div class="card"
                            style="background-color: rgba(255, 255, 255, 0.9); border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('h_tax.search_tax') }}</h3>
                            </div>
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-2">
                                            <label>{{ __('h_tax.tax_code') }}</label>
                                            <input type="text" name="code" class="form-control"
                                                value="{{ Request()->code }}"
                                                placeholder="{{ __('h_tax.code_placeholder') }}">
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label>{{ __('h_tax.tax_name') }}</label>
                                            <input type="text" value="{{ Request()->name }}" name="name"
                                                class="form-control" placeholder="{{ __('h_tax.name_placeholder') }}">
                                        </div>

                                        {{-- 🆕 Add Branch Filter --}}
                                        @if (session('branch_id') === null || \App\Models\Branch::find(session('branch_id'))?->is_main == 1)
                                            <div class="form-group col-md-2">
                                                <label>{{ __('h_employee.branch') }}</label>
                                                <select name="filter_branch_id" class="form-control">
                                                    <option value="">{{ __('h_employee.all') }}</option>
                                                    @foreach ($branches as $branch)
                                                        <option value="{{ $branch->id }}"
                                                            {{ Request()->filter_branch_id == $branch->id ? 'selected' : '' }}>
                                                            {{ $branch->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif

                                        <div class="form-group col-md-3 d-flex align-items-end">
                                            <button class="btn btn-primary rounded-pill" type="submit"
                                                style="margin-right: 10px;" title="{{ __('h_tax.search_tooltip') }}">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <a href="{{ url('admin/taxes') }}" class="btn btn-success rounded-pill"
                                                title="{{ __('h_tax.reset_tooltip') }}">
                                                <i class="fas fa-sync-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        @include('_message')

                        <div class="card"
                            style="background-color: rgba(255, 255, 255, 0.9); border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <form method="POST" action="{{ route('taxes.toggleCompanyTax') }}"
                                        style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-{{ $isTaxApplied ? 'danger' : 'success' }}">
                                            {{ $isTaxApplied ? __('h_tax.dont_apply_tax_to_payroll') : __('h_tax.apply_tax_to_payroll') }}
                                        </button>
                                    </form>
                                </div>

                                <h3 class="card-title text-center flex-grow-1 text-center">{{ __('h_tax.taxes_list') }}
                                </h3>

                                <div>
                                    <button class="btn btn-danger"
                                        id="deleteSelected">{{ __('h_tax.delete_selection') }}</button>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="selectAll"></th>
                                                <th>{{ __('h_tax.code') }}</th>
                                                <th>{{ __('h_tax.tax_name') }}</th>
                                                <th>{{ __('h_tax.employee_name') }}</th>
                                                <th>{{ __('h_tax.apply_to_payroll') }}</th>
                                                <th>{{ __('h_employee.branch') }}</th>
                                                <th>{{ __('h_tax.percentage') }}</th>
                                                <th>{{ __('h_tax.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($getRecord as $value)
                                                <tr>
                                                    <td><input type="checkbox" class="taxCheckbox"
                                                            value="{{ $value->id }}"></td>
                                                    <td>{{ $value->code }}</td>
                                                    <td>{{ $value->name }}</td>
                                                    <td>{{ $value->employee_name }}</td>
                                                    <td>
                                                        <span
                                                            class="badge {{ $value->apply_to_payroll == 1 ? 'badge-success' : 'badge-warning' }}">
                                                            {{ $value->apply_to_payroll == 1 ? __('h_tax.yes') : __('h_tax.no') }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $value->branch_name ?? __('h_dashboard.main_branch') }}</td>
                                                    <td>{{ $value->percent }}%</td>
                                                    <td>
                                                        <a href="{{ url('admin/taxes/edit/' . $value->id) }}"
                                                            class="btn btn-primary rounded-pill"
                                                            title="{{ __('h_tax.edit_tooltip') }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="{{ url('admin/taxes/delete/' . $value->id) }}"
                                                            onclick="return confirm('{{ __('h_tax.delete_confirmation') }}')"
                                                            class="btn btn-danger rounded-pill"
                                                            title="{{ __('h_tax.delete_tooltip') }}">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6">{{ __('h_tax.not_found') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div style="padding: 10px; float:right;">
                                    {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
                                </div>
                            </div>
                        </div>

                    </section>
                </div>
            </div>
        </section>
    </div>

    <!-- Link to the new JavaScript file -->
    <script src="{{ url('dist/js/tax.js') }}"></script>

@endsection

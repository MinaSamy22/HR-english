@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"
        style="background-image: url('{{ asset('/dist/img//payroll3.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">



                    <div id="accordion" class="w-100">
                        <div class="card card-light">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title m-0">
                                    <a class="d-block" data-toggle="collapse" href="#collapseOne">
                                        {{ __('dashboard.payroll_list') }}
                                    </a>
                                </h4>
                                <div class="ml-auto">
                                    <a href="{{ url('admin/payroll/add') }}" class="btn btn-primary text-white">{{ __('dashboard.create_payroll') }}</a>
                                </div>
                            </div>
                            <div id="collapseOne" class="collapse " data-parent="#accordion">
                                <div class="card-body">

                                    ⚠️ <strong>{{ __('dashboard.important') }}</strong>
                                    {{ __('dashboard.before_creating_payroll') }}
                                    <strong>{{ __('dashboard.working_days') }}</strong>
                                    {{ __('dashboard.and') }}
                                    <strong>{{ __('dashboard.official_holidays') }}</strong>,
                                    {{ __('dashboard.as_they_affect_deductions') }}<br><br>

                                    <strong>{{ __('dashboard.clarification_of_payroll') }}</strong><br><br>

                                    • <strong>{{ __('dashboard.bonus') }}</strong> – {{ __('dashboard.bonus_explanation') }}<br>

                                    • <strong>{{ __('dashboard.deductions') }}</strong> – {{ __('dashboard.deductions_explanation') }}<br>

                                    • <strong>{{ __('dashboard.taxes_insurance') }}</strong> – {{ __('dashboard.taxes_insurance_explanation') }}<br>

                                    • <strong>{{ __('dashboard.total_vacation_balance') }}</strong> – {{ __('dashboard.total_vacation_balance_explanation') }}<br>

                                    • <strong>{{ __('dashboard.net_pay') }}</strong> – {{ __('dashboard.net_pay_explanation') }}<br>
                                    <code>{{ __('dashboard.net_pay_formula') }}</code>
                                </div>

                            </div>
                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div><!-- /.content-header -->




        <form method="get" action="">
            <div class="card-body">
                <div class="row">

                    <div class="form-group col-md-2 col-sm-6">
                        <label>{{ __('dashboard.employee_name') }}</label>
                        <input type="text" value="{{ Request::get('name') }}" name="name" class="form-control"
                            placeholder="Enter Name"
                            style="background-color: rgba(255, 255, 255, 0.5); color: black; border: 1px solid rgba(255, 255, 255, 0.8);">
                    </div>





                    <div class="form-group col-md-2 col-sm-6">
                        <label>{{ __('dashboard.payroll_type') }}</label>
                        <select name="payroll_type" class="form-control"
                            style="background-color: rgba(255, 255, 255, 0.5); border: 1px solid rgba(255, 255, 255, 0.8);">
                            <option value="">{{ __('dashboard.select_payroll_type') }}</option>
                            <option value="monthly" {{ Request::get('payroll_type') == 'monthly' ? 'selected' : '' }}>{{ __('dashboard.monthly') }}
                            </option>
                            <option value="weekly" {{ Request::get('payroll_type') == 'weekly' ? 'selected' : '' }}>{{ __('dashboard.weekly') }}
                            </option>
                            <option value="daily" {{ Request::get('payroll_type') == 'daily' ? 'selected' : '' }}>{{ __('dashboard.daily') }}
                            </option>
                        </select>
                    </div>


                    <div class="form-group col-md-2 col-sm-6">
                        <label>{{ __('dashboard.month') }}</label>
                        <select name="month" class="form-control"
                            style="background-color: rgba(255, 255, 255, 0.5); border: 1px solid rgba(255, 255, 255, 0.8);">
                            <option value="">{{ __('dashboard.select_month') }}</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ Request::get('month') == $i ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                            @endfor
                        </select>
                    </div>


                    <div class="form-group col-md-2 col-sm-6">
                        <label>{{ __('dashboard.year') }}</label>
                        <select name="year" class="form-control"
                            style="background-color: rgba(255, 255, 255, 0.5); border: 1px solid rgba(255, 255, 255, 0.8);">
                            <option value="">{{ __('dashboard.select_year') }}</option>
                            @php
                                $currentYear = date('Y');
                                $endYear = $currentYear + 6; // Show 6 years into the future
                            @endphp
                            @for ($i = $currentYear; $i <= $endYear; $i++)
                                <option value="{{ $i }}" {{ Request::get('year') == $i ? 'selected' : '' }}>
                                    {{ $i }}</option>
                            @endfor
                        </select>
                    </div>




    <div class="form-group col-md-4" style="margin-top: 32px">
      <div class="d-flex justify-content-between w-100">
        <!-- Left side: Search & Reset -->
        <div>
            <button class="btn btn-primary rounded-pill" type="submit" title="Search">
                <i class="fas fa-search"></i>
            </button>
            <a href="{{ url('admin/payroll') }}" class="btn btn-success rounded-pill" title="Reset">
                <i class="fas fa-sync-alt"></i>
            </a>
        </div>

        <!-- Right side: Excel & PDF -->
        <div>
            <a class="btn btn-success rounded-pill"
                href="{{ url(path: 'admin/payroll_export?name=' . Request::get('name') . '&month=' . Request::get('month') . '&year=' . Request::get('year')  . '&payroll_type=' . Request::get('payroll_type')) }}">
                <i class="fas fa-file-excel"></i> {{ __('dashboard.excel') }}
            </a>
            <a href="{{ route('payrolls.exportPdf', Request::all()) }}" class="btn btn-danger rounded-pill ms-2">
                <i class="fas fa-file-pdf"></i> {{ __('dashboard.pdf') }}
            </a>
        </div>
    </div>
</div>


                </div>
            </div>
        </form>




        @include('_message')

        <div class="card"
            style="background-color: rgba(255, 255, 255, 0.7); border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <h3 class="card-title mb-2 mb-md-0">{{ __('dashboard.payroll_list') }}</h3>
                <div class="ml-auto">
                    <button class="btn btn-danger" id="deleteSelected">{{ __('dashboard.delete_selection') }}</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>{{ __('dashboard.employee_id') }}</th>
                            <th>{{ __('dashboard.employee_name') }}</th>
                            <th>{{ __('dashboard.basic_salary') }}</th>
                            <th>{{ __('dashboard.bounas') }}</th>
                            <th>{{ __('dashboard.deductions') }}</th>
                            <th>{{ __('dashboard.attendance_deduction') }}</th>
                            <th>{{ __('dashboard.taxes_inscurance') }}</th>
                            <th>{{ __('dashboard.vacation_balance') }}</th>
                            <th>{{ __('dashboard.net_pay') }}</th>
                            <th>{{ __('dashboard.payroll_type') }}</th>
                            <th>{{ __('dashboard.pay_date') }}</th>
                            <th>{{ __('dashboard.month') }}</th>
                            <th>{{ __('dashboard.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($getRecord as $value)
                            <tr>
                                <td><input type="checkbox" class="payrollCheckbox" value="{{ $value->id }}"></td>
                                <td>{{ $value->employee_id }}</td>
                                <td>{{ $value->name }}</td>
                                <td>{{ $value->basic_salary }}</td>
                                <td>{{ $value->bounas }}</td>
                                <td>{{ $value->deductions }}</td>
                                <td>{{ $value->attendance_deduction }}</td>
                                <td>{{ $value->taxes }}</td>

                                <td>
                                    @if ($value->payroll_type == 'monthly')
                                        {{ $value->rest_vacancy }} {{ __('dashboard.day') }}
                                    @else
                                        0
                                    @endif
                                </td>

                                <td>{{ $value->net_pay }}</td>
                                <td>{{ $value->payroll_type }}</td>
                                <td>{{ date('d-m-Y', strtotime($value->created_at)) }}</td>
                                <td>{{ date('m', strtotime($value->start_date)) }}</td>
                                <td>
                                    <a href="{{ url('admin/payroll/edit/' . $value->id) }}"
                                        class="btn btn-primary rounded-pill" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ url('admin/payroll/delete/' . $value->id) }}"
                                        onclick="return confirm('Are you sure you want to delete?')"
                                        class="btn btn-danger rounded-pill" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3 pr-3">
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
    <script src="{{ url('dist/js/payroll.js') }}"></script>
@endsection

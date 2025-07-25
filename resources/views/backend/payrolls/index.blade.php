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
                                        Payroll List
                                    </a>
                                </h4>
                                <div class="ml-auto">
                                    <a href="{{ url('admin/payroll/add') }}" class="btn btn-primary text-white">Create
                                        Payroll</a>
                                </div>
                            </div>
                            <div id="collapseOne" class="collapse " data-parent="#accordion">
                                <div class="card-body">

                                    ⚠️ <strong>Important:</strong> Before creating payroll, make sure your company policy is
                                    configured correctly.
                                    special attention to <strong>working days</strong> and <strong>official
                                        holidays</strong>,
                                    as they directly affect attendance deductions.<br><br>

                                    <strong>Clarification of the Payroll :</strong><br><br>

                                    • <strong>Bonus</strong> – Calculated based on the company's bonus policy for extra
                                    hours in company policy.<br>

                                    • <strong>Deductions</strong> – Includes manual deductions, penalties, or extra vacation
                                    days taken beyond the balance.<br>

                                    • <strong>Taxes / Insurance</strong> – Automatically deducted based on the configured
                                    percentages locate in the tax and insurance.<br>

                                    • <strong>Total Vacation Balance</strong> – Determined by the company’s policy (e.g., 21
                                    or 30 days per year).<br>




                                    • <strong>Net Pay</strong> – Calculated as:<br>
                                    <code>Net Pay = Basic Salary - (Taxes + Insurance + Deductions + Attendance Deductions)
                                        + Bonus</code>
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
                        <label>Employee Name</label>
                        <input type="text" value="{{ Request::get('name') }}" name="name" class="form-control"
                            placeholder="Enter Name"
                            style="background-color: rgba(255, 255, 255, 0.5); color: black; border: 1px solid rgba(255, 255, 255, 0.8);">
                    </div>





                    <div class="form-group col-md-2 col-sm-6">
                        <label>Payroll Type</label>
                        <select name="payroll_type" class="form-control"
                            style="background-color: rgba(255, 255, 255, 0.5); border: 1px solid rgba(255, 255, 255, 0.8);">
                            <option value="">Select Payroll Type</option>
                            <option value="monthly" {{ Request::get('payroll_type') == 'monthly' ? 'selected' : '' }}>Monthly
                            </option>
                            <option value="weekly" {{ Request::get('payroll_type') == 'weekly' ? 'selected' : '' }}>Weekly
                            </option>
                            <option value="daily" {{ Request::get('payroll_type') == 'daily' ? 'selected' : '' }}>Daily
                            </option>
                        </select>
                    </div>


                    <div class="form-group col-md-2 col-sm-6">
                        <label>Month</label>
                        <select name="month" class="form-control"
                            style="background-color: rgba(255, 255, 255, 0.5); border: 1px solid rgba(255, 255, 255, 0.8);">
                            <option value="">Select Month</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ Request::get('month') == $i ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                            @endfor
                        </select>
                    </div>


                    <div class="form-group col-md-2 col-sm-6">
                        <label>Year</label>
                        <select name="year" class="form-control"
                            style="background-color: rgba(255, 255, 255, 0.5); border: 1px solid rgba(255, 255, 255, 0.8);">
                            <option value="">Select Year</option>
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
                <i class="fas fa-file-excel"></i> Excel
            </a>
            <a href="{{ route('payrolls.exportPdf', Request::all()) }}" class="btn btn-danger rounded-pill ms-2">
                <i class="fas fa-file-pdf"></i> PDF
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
                <h3 class="card-title mb-2 mb-md-0">Payroll List</h3>
                <div class="ml-auto">
                    <button class="btn btn-danger" id="deleteSelected">Delete Selection</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>Employee ID</th>
                            <th>Employee Name</th>
                            <th>Basic Salary</th>
                            <th>Bounas</th>
                            <th>Deductions</th>
                            <th>ِAttendance Deduction</th>
                            <th>Taxes/Inscurance</th>
                            <th>Vacation Balance</th>
                            <th>Net Pay</th>
                            <th>Payroll Type</th>
                            <th>Pay Date</th>
                            <th>Month</th>
                            <th>Action</th>
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
                                        {{ $value->rest_vacancy }} day
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

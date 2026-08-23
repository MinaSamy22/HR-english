@extends('backend.layouts.app')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"
        style="background-image: url('{{ asset('/dist/img/vacation.jpg') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
                       <h1 class="m-0 mt-3 mb-3">{{ __('h_vacation.vacation') }}</h1>

                        <ol class="breadcrumb float-sm-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}">
                            <li class="breadcrumb-item"><a href="{{ url('admin/vacations') }}">{{ __('h_vacation.vacations') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('h_vacation.add') }}</li>
                        </ol>


                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('h_vacation.add_vacation_title') }}</h3>
                            </div>
                            <form class="form-horizontal" method="post" action="{{ url('admin/vacations/add') }}"
                                enctype="multipart/form-data" id="addForm">
                                {{ csrf_field() }}
                                <div class="card-body">

                                    <div class="form-group row">
    <label class="col-sm-2 col-form-label">
        {{ __('h_vacation.employee_name') }} <span style="color: red;">*</span>
    </label>
    <div class="col-sm-10">
        <select class="form-control" name="employee_id" required>
            <option value="">{{ __('h_vacation.select_employee_name') }}</option>
            @foreach($getUsers as $user)
    @php
        $usedDays = \App\Models\Vacation::where('employee_id', $user->id)->sum('total');
        $remaining = $user->vacation_balance !== null ? $user->vacation_balance - $usedDays : __('h_vacation.no_balance');;
    @endphp
    <option value="{{ $user->id }}">
        {{ $user->name }} ({{ __('h_vacation.remaining_balance') }}: {{ $remaining }})
    </option>
@endforeach

        </select>
    </div>
</div>


                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_vacation.vacation_type') }} <span
                                                style="color: red;">*</span></label>
                                        <div class="col-sm-10">
                                            <select name="vacation_type" id="vacation_type" class="form-control" required>
                                                <option value="">{{ __('h_vacation.select_vacation_type') }}</option>
                                                <option value="Annual">{{ __('h_vacation.annual_vacation') }}</option>
                                                <option value="Sick">{{ __('h_vacation.sick_leave') }}</option>
                                                <option value="Unpaid">{{ __('h_vacation.unpaid_leave') }}</option>

                                                @php
                                                    $existingTypes = DB::table('vacations')
                                                        ->select('vacation_type')
                                                        ->whereNotNull('vacation_type')
                                                        ->where('vacation_type', '!=', '')
                                                        ->whereNotIn('vacation_type', ['Annual', 'Sick', 'Unpaid'])
                                                        ->distinct()
                                                        ->pluck('vacation_type');
                                                @endphp

                                                @foreach ($existingTypes as $type)
                                                    <option value="{{ $type }}"
                                                        {{ old('vacation_type') == $type ? 'selected' : '' }}>
                                                        {{ $type }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="form-text text-muted">
                                                <a href="javascript:void(0)" onclick="addCustomType()">{{ __('h_vacation.add_custom_type') }}</a>
                                            </small>
                                        </div>
                                    </div>

                                    <script>
                                        function addCustomType() {
                                            var customType = prompt("{{ __('h_vacation.enter_new_vacation_type') }}");
                                            if (customType && customType.trim() !== '') {
                                                customType = customType.trim();

                                                // Check if type already exists
                                                var existingOptions = Array.from(document.querySelectorAll('#vacation_type option')).map(option => option
                                                    .value);

                                                if (!existingOptions.includes(customType)) {
                                                    // Add new option
                                                    var select = document.getElementById('vacation_type');
                                                    var option = document.createElement('option');
                                                    option.value = customType;
                                                    option.text = customType;
                                                    option.selected = true;
                                                    select.appendChild(option);
                                                } else {
                                                    // Select existing option
                                                    document.getElementById('vacation_type').value = customType;
                                                }
                                            }
                                        }
                                    </script>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_vacation.start_date') }} <span
                                                style="color: red;">*</span></label>
                                        <div class="col-sm-10">
                                            <input type="date" value="{{ old('start_date') }}" name="start_date"
                                                class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('h_vacation.end_date') }} <span
                                                style="color: red;">*</span></label>
                                        <div class="col-sm-10">
                                            <input type="date" value="{{ old('end_date') }}" name="end_date"
                                                class="form-control" required>
                                        </div>
                                    </div>

                                </div>

                                <div class="card-footer">
                                    <a href="{{ url('admin/vacations') }}" class="btn btn-default float-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}">{{ __('h_vacation.back') }}</a>
                                    <button type="submit" id="submitBtn" class="btn btn-primary float-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}">{{ __('h_vacation.submit') }}</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- RTL Support for Arabic -->
    @if(app()->getLocale() == 'ar')
        <style>
            body {
                direction: rtl;
                text-align: right;
            }

            .float-sm-right {
                float: left !important;
            }

            .float-left {
                float: right !important;
            }

            .float-right {
                float: left !important;
            }

            .text-right {
                text-align: left !important;
            }

            .ml-auto {
                margin-left: 0 !important;
                margin-right: auto !important;
            }

            .mr-1, .mr-2 {
                margin-right: 0 !important;
                margin-left: 0.25rem;
            }

            .mr-2 {
                margin-left: 0.5rem !important;
            }
        </style>
    @endif
@endsection

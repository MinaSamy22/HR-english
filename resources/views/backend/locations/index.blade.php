@extends('backend.layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="background-image: url('{{ asset('/dist/img/generall.png') }}'); background-size: cover; background-position: center;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 style="color: #333;">{{ __('dashboard.locations') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('locations.create') }}" class="btn btn-primary rounded-pill">
                            <i class="fas fa-user-plus"></i> {{ __('dashboard.add_location') }}
                        </a>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <section class="col-md-12">
                        <div class="card" style="background-color: rgba(255, 255, 255, 0.9); border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('dashboard.search_location') }}</h3>
                            </div>
                            <form method="get" action="">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-2 col-sm-6">
                                            <label>{{ __('dashboard.name') }}</label>
                                            <input type="text" value="{{ Request()->name }}" name="name" class="form-control" placeholder="{{ __('dashboard.name') }}">
                                        </div>
                                        <div class="form-group col-md-3 col-sm-6 d-flex align-items-end">
                                            <button class="btn btn-primary rounded-pill" type="submit" style="margin-right: 10px;" title="{{ __('dashboard.search') }}">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <a href="{{ route('locations.index') }}" class="btn btn-success rounded-pill" title="{{ __('dashboard.reset') }}">
                                                <i class="fas fa-sync-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        @include('_message')

                        <div class="card" style="background-color: rgba(255, 255, 255, 0.9); border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('dashboard.locations_list') }}</h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('dashboard.id') }}</th>
                                                <th>{{ __('dashboard.name') }}</th>
                                                <th>{{ __('dashboard.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($getRecord as $value)
                                            <tr>
                                                <td>{{ $value->id }}</td>
                                                <td>{{ $value->name }}</td>
                                                <td>
                                                    {{-- <a href="{{ route('locations.show',$value->id) }}" class="btn btn-info rounded-pill" title="{{ __('dashboard.view') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a> --}}
                                                    <a href="{{ route('locations.edit',$value->id) }}" class="btn btn-primary rounded-pill" title="{{ __('dashboard.edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger rounded-pill delete-btn"
                                                            data-id="{{ $value->id }}" title="{{ __('h_manager.delete') }}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>

                                                        <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#assignEmployeesModal"
                                                            data-location="{{ $value->id }}">
                                                    {{__('dashboard.assign_employees')}}
                                                    </button>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center">{{ __('dashboard.not_found') }}</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-end p-3">
                                    {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </div>

    <!-- Assign Employees Modal -->
    <div class="modal fade" id="assignEmployeesModal" tabindex="-1" role="dialog" aria-labelledby="assignEmployeesLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" action="{{ route('locations.assignEmployees') }}">
                @csrf
                <input type="hidden" name="location_id" id="location_id">

                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('dashboard.assign_employees')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    @foreach($employees as $employee)
                        <div class="form-check">
                            <input class="form-check-input employee-checkbox" 
                                    type="checkbox" 
                                    name="employees[]" 
                                    value="{{ $employee->id }}" 
                                    id="emp{{ $employee->id }}">
                            <label class="form-check-label" for="emp{{ $employee->id }}">
                                {{ $employee->name }}
                            </label>
                        </div>
                    @endforeach
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('dashboard.close')}}</button>
                    <button type="submit" class="btn btn-primary">{{__('dashboard.save')}}</button>
                </div>
                </div>
            </form>
        </div>
    </div>


@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $('#assignEmployeesModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var locationId = button.data('location');
            var modal = $(this);

            // Set hidden field
            modal.find('#location_id').val(locationId);

            // Clear previous selections
            modal.find('.employee-checkbox').prop('checked', false);

            // Fetch assigned employees for this location
            $.get("{{url('admin/locations') }}/" + locationId + "/employees", function (data) {
                if (data.employees) {
                    data.employees.forEach(function (id) {
                        modal.find('.employee-checkbox[value="' + id + '"]').prop('checked', true);
                    });
                }
            });
        });


    </script>


    <script>
        $(document).on('click', '.delete-btn', function () {
            let deleteId = $(this).data('id');

            Swal.fire({
                title: "{{ __('h_manager.delete') }}?",
                text: "{{ __('h_manager.delete_confirmation') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "{{ __('h_manager.delete') }}",
                cancelButtonText: "{{ __('dashboard.cancel') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('admin/locations') }}/" + deleteId,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function () {
                            $('button.delete-btn[data-id="' + deleteId + '"]').closest('tr').fadeOut();

                            Swal.fire({
                                title: "{{ __('h_manager.deleted') }}!",
                                text: "{{ __('h_manager.delete_success') }}",
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function () {
                            Swal.fire({
                                title: "{{ __('h_manager.error') }}",
                                text: "{{ __('h_manager.delete_failed') }}",
                                icon: "error",
                                confirmButtonText: "OK"
                            });
                        }
                    });
                }
            });
        });
    </script>

@endsection
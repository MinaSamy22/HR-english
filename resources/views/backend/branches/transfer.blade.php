@extends('backend.layouts.app')

@section('content')
    <div class="content-wrapper" style="background-image: url('{{ asset('/dist/img/generall.png') }}'); background-size: cover; background-position: center;">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Transfer Employees Between Branches</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @elseif(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="card p-3" style="background: rgba(255,255,255,0.9); border-radius: 10px;">
                    <form action="{{ route('branches.transfer') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="employee_selection">Choose Employees</label>
                            <div class="mb-2">
                                <button type="button" class="btn btn-sm btn-success" onclick="toggleSelectAll()" id="select-all-btn">
                                    <i class="fas fa-check-double"></i> Select All
                                </button>
                            </div>
                            <div id="employee-list" style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; border-radius: 5px; padding: 10px;">
                                @foreach($employees as $employee)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input employee-checkbox" type="checkbox"
                                               name="user_ids[]" value="{{ $employee->id }}"
                                               id="employee_{{ $employee->id }}">
                                        <label class="form-check-label" for="employee_{{ $employee->id }}">
                                            {{ $employee->name }}
                                            @if($employee->branch)
                                                <span class="text-muted">({{ $employee->branch->name }})</span>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">
                                    Selected: <span id="selected-count">0</span> employees
                                </small>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label for="branch_id">Choose the new branch</label>
                            <select name="branch_id" class="form-control" required>
                                <option value="">-- Select Branch --</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary" id="transfer-btn" disabled>
                                <i class="fas fa-random"></i> Transfer Selected Employees
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <script src="{{ asset('dist/js/transfere.js') }}"></script>



@endsection

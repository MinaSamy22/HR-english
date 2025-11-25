@extends('backend.layouts.app')


@section('content')
<div class="content-wrapper" style="background-image: url('{{ asset('/dist/img/dashboard.jpg') }}'); background-size: cover; background-position: center;">
    <div class="content-header">
        <div class="container-fluid">
            <div class=" mb-2 d-flex justify-content-between">
                <div class="">
                    <h1 class="m-0">{{ __('h_message.compose_message') }}</h1>
                </div>
                <div class="">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('messages.sent') }}">{{ __('h_message.messages') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('h_message.compose') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-pencil-alt mr-1"></i>
                                {{ __('h_message.compose_new_message') }}
                            </h3>
                        </div>
                        <form class="form-horizontal" action="{{ route('messages.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <!-- Recipients -->
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{ __('h_message.recipients') }} <span style="color: red;">{{ __('h_message.required_field') }}</span></label>
                                    <div class="col-sm-6">
    <div id="employee-list"
         style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; border-radius: 5px; padding: 10px;">

        <!-- Select All -->
        <div class="form-check mb-2">
            <input type="checkbox" id="select-all" class="form-check-input">
            <label for="select-all" class="form-check-label">
                {{ __('h_message.select_all') }}
            </label>
        </div>

        <!-- Employees -->
        @foreach($employees as $employee)
            <div class="form-check mb-2 employee-item">
                <input type="checkbox" name="recipient_ids[]"
                       value="{{ $employee->id }}"
                       id="employee-{{ $employee->id }}"
                       class="form-check-input employee-checkbox"
                       {{ in_array($employee->id, old('recipient_ids', [])) ? 'checked' : '' }}>
                <label for="employee-{{ $employee->id }}" class="form-check-label">
                    {{ $employee->name }}
                </label>
            </div>
        @endforeach
    </div>

    <!-- Counter -->
    <div class="mt-2">
        <small class="text-muted" id="selection-count">
            0 {{ __('h_message.selected') }}
        </small>
    </div>

    <!-- Validation error -->
    @error('recipient_ids')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

                                </div>

                                <!-- Subject -->
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{ __('h_message.subject') }} <span style="color: red;">{{ __('h_message.required_field') }}</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="subject" class="form-control" required
                                               placeholder="{{ __('h_message.enter_subject') }}" value="{{ old('subject') }}">
                                        @error('subject')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Urgent checkbox -->
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{ __('h_message.priority') }}</label>
                                    <div class="col-sm-10">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="is_urgent"
                                                   name="is_urgent" value="1" {{ old('is_urgent') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="is_urgent">
                                                <i class="fas fa-star text-warning"></i> {{ __('h_message.mark_as_urgent') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{ __('h_message.message') }} <span style="color: red;">{{ __('h_message.required_field') }}</span></label>
                                    <div class="col-sm-10">
                                        <textarea name="content" class="form-control" rows="8" required minlength="1"
                                                  placeholder="{{ __('h_message.type_message_here') }}">{{ old('content') }}</textarea>
                                        @error('content')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        @error('error')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <a href="{{ route('messages.sent') }}" class="btn btn-default float-left">{{ __('h_message.back') }}</a>
                                <button type="submit" class="btn btn-primary float-right" id="send-btn">
                                    <i class="far fa-envelope"></i> {{ __('h_message.send_message') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.checkbox-box {
    max-height: 300px;
    overflow-y: auto;
    border: 1px solid #ddd;
    padding: 15px;
    border-radius: 4px;
    background-color: #f9f9f9;
}

.checkbox-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    padding: 5px 0;
}

.checkbox-item input[type="checkbox"] {
    margin-right: 10px;
    transform: scale(1.2);
}

.checkbox-item label {
    margin-bottom: 0;
    font-weight: normal;
    cursor: pointer;
    user-select: none;
}

.checkbox-item:first-child {
    border-bottom: 1px solid #ddd;
    margin-bottom: 15px;
    padding-bottom: 10px;
}

.checkbox-item:first-child input[type="checkbox"] {
    transform: scale(1.3);
}

.checkbox-item:first-child label {
    font-weight: bold;
    color: #007bff;
}

#selection-count {
    font-weight: 500;
    color: #007bff;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const employeeCheckboxes = document.querySelectorAll('.employee-checkbox');
    const selectionCount = document.getElementById('selection-count');
    const sendBtn = document.getElementById('send-btn');

    // Update selection count
    function updateSelectionCount() {
        const selected = document.querySelectorAll('.employee-checkbox:checked').length;
        selectionCount.textContent = selected + ' {{ __('h_message.selected') }}';

        // Update select all checkbox state
        if (selected === employeeCheckboxes.length && employeeCheckboxes.length > 0) {
            selectAllCheckbox.checked = true;
            selectAllCheckbox.indeterminate = false;
        } else if (selected > 0) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = true;
        } else {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        }
    }

    // Select all functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            employeeCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            updateSelectionCount();
        });
    }

    // Individual checkbox change
    employeeCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectionCount);
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const selected = document.querySelectorAll('.employee-checkbox:checked').length;
        if (selected === 0) {
            e.preventDefault();
            alert('{{ __('h_message.select_at_least_one') }}');
            return false;
        }

        // Show sending indicator
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __('h_message.sending') }}';
        sendBtn.disabled = true;
    });

    // Initialize count on page load
    updateSelectionCount();
});
</script>
@endsection

@extends('backend.layouts.app')
@section('content')
<div class="content-wrapper" style="background-image: url('{{ asset('/dist/img/generall.png') }}'); background-size: cover; background-position: center;">
    <section class="content">
        <div class="container-fluid">
            <h2 class="pt-4">{{ __('h_branches.add_branch_title') }}</h2>

            @include('_message')

            <form method="POST" action="{{ url('admin/branches/add') }}" style="background-color: rgba(255,255,255,0.95); padding: 20px; border-radius: 10px;" id="addForm">
                @csrf

                <div class="form-group">
                    <label>{{ __('h_branches.branch_name') }}</label>
                    <input type="text" name="name" class="form-control" required placeholder="{{ __('h_branches.enter_branch_name') }}">
                </div>

                <div class="form-group">
                    <label>{{ __('h_branches.location') }}</label>
                    <input type="text" name="location" class="form-control" placeholder="{{ __('h_branches.enter_location_optional') }}">
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_main"> {{ __('h_branches.is_main_branch_question') }}
                    </label>
                </div>

                <button type="submit" id="submitBtn" class="btn btn-primary rounded-pill"><i class="fas fa-plus-circle"></i> {{ __('h_branches.add_branch_button') }}</button>
            </form>
        </div>
    </section>
</div>

<script>
    // Prevent double submit on add form
    const addForm = document.getElementById('addForm');
    const submitBtn = document.getElementById('submitBtn');
    let isSubmitting = false;

    addForm.addEventListener('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return;
        }

        isSubmitting = true;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __('h_branches.add_branch_button') }}...';
        submitBtn.style.opacity = '0.7';
        submitBtn.style.cursor = 'not-allowed';
    });

    // Re-enable button if user goes back
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            isSubmitting = false;
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-plus-circle"></i> {{ __('h_branches.add_branch_button') }}';
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
        }
    });
</script>
@endsection

@extends('backend.layouts.app')
@section('content')
<div class="content-wrapper" style="background-image: url('{{ asset('/dist/img/generall.png') }}'); background-size: cover; background-position: center;">
    <section class="content">
        <div class="container-fluid">
            <h2 class="pt-4">{{ __('h_branches.add_branch_title') }}</h2>

            @include('_message')

            <form method="POST" action="{{ url('admin/branches/add') }}" style="background-color: rgba(255,255,255,0.95); padding: 20px; border-radius: 10px;">
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

                <button type="submit" class="btn btn-primary rounded-pill"><i class="fas fa-plus-circle"></i> {{ __('h_branches.add_branch_button') }}</button>
            </form>
        </div>
    </section>
</div>
@endsection

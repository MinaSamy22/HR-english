@extends('backend.layouts.app')
@section('content')
<div class="content-wrapper" style="background-image: url('{{ asset('/dist/img/generall.png') }}'); background-size: cover; background-position: center;">
    <section class="content">
        <div class="container-fluid">
            <h2 class="pt-4">Add Branch</h2>

            @include('_message')

            <form method="POST" action="{{ url('admin/branches/add') }}" style="background-color: rgba(255,255,255,0.95); padding: 20px; border-radius: 10px;">
                @csrf

                <div class="form-group">
                    <label>Branch Name</label>
                    <input type="text" name="name" class="form-control" required placeholder="Enter branch name">
                </div>

                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" class="form-control" placeholder="Enter location (optional)">
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_main"> Is this the Main Branch?
                    </label>
                </div>

                <button type="submit" class="btn btn-primary rounded-pill"><i class="fas fa-plus-circle"></i> Add Branch</button>
            </form>
        </div>
    </section>
</div>
@endsection

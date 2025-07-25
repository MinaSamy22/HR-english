@extends('backend.layouts.app')
@section('content')
<div class="content-wrapper" style="background-image: url('{{ asset('/dist/img/generall.png') }}'); background-size: cover; background-position: center;">
    <section class="content">
        <div class="container-fluid">
            <h2 class="pt-4">Edit Branch</h2>

            @include('_message')

            <form method="POST" action="{{ url('admin/branches/edit/' . $getRecord->id) }}" style="background-color: rgba(255,255,255,0.95); padding: 20px; border-radius: 10px;">
                @csrf

                <div class="form-group">
                    <label>Branch Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $getRecord->name }}" required>
                </div>

                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" class="form-control" value="{{ $getRecord->location }}">
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_main" {{ $getRecord->is_main ? 'checked' : '' }}> Main Branch?
                    </label>
                </div>

                <button type="submit" class="btn btn-primary rounded-pill"><i class="fas fa-save"></i> Save Changes</button>
            </form>
        </div>
    </section>
</div>
@endsection

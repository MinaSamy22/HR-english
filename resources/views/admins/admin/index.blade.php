<!-- resources/views/admin/admins/manage.blade.php -->
@extends('admins.layouts.app')

@section('content')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manage Admins">
    <title>Manage Admins</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ url('dist/img/hr_logo-.png') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ url('dist/css/admin.css') }}">
</head>
<body>
    <div class="content-wrapper dashboard" style="background-image: url('{{ asset('/dist/img/dashboard.jpg') }}'); background-size: cover; background-position: center;">
        <div class="card">
            <div class="card-header">
                <h1>Manage Admins</h1>
            </div>
            <div class="card-body">
                <p class="lead">Delete an admin from the system</p>



                <!-- Admins List -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $currentAdminId = auth('admin')->id();
                    @endphp

                    @foreach($admins as $admin)
                        @if($currentAdminId !== $admin->id)
                            <tr>
                                <td>{{ $admin->name }}</td>
                                <td>{{ $admin->email }}</td>
                                <td>
                                    <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this admin?');">
                                            <i class="fas fa-user-slash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @endforeach

                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection

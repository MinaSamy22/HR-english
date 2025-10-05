@extends('admins.layouts.app')

@section('content')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - All Companies</title>
      <!-- Favicon -->
      <link rel="icon" type="image/x-icon" href="{{ url('dist/img/hr_logo-.png') }}">

       <!-- Custom CSS -->
       <link rel="stylesheet" href="{{ url('dist/css/admin.css') }}">
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<body>
    <div class="content-wrapper dashboard" style="background-image: url('{{ asset('/dist/img/dashboard.jpg') }}'); background-size: cover; background-position: center;">
        <div class="card">
            <div class="card-header">
                <h1>All Companies</h1>
            </div>
            <div class="card-body">
                <p class="lead">Manage and delete companies</p>




                <!-- Table to display companies -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>Company Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($companies as $company)
                            <tr>
                                <td>{{ $company->name }}</td>
                                <td>
                                    <form action="{{ route('admin.companies.destroy', $company->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this company?');">
                                            <i class="fas fa-building"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>



            </div>
        </div>
    </div>
@endsection

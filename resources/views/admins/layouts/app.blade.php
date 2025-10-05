<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin dashboard for company management">
    <title>@yield('title', 'Admin Portal - HR Management System')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ url('dist/img/hr_logo-.png') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ url('dist/css/admin-home.css') }}">

    @stack('styles')

</head>

<body>
    <div class="dashboard-container">

        {{-- ================= Header Section ================= --}}
        @section('header')
            <div class="header">
                <h1><i class="fas fa-tachometer-alt" style="margin-right: 8px; color: #667eea;"></i>Admin Control Panel</h1>
                <div class="user-info">
                    <span style="color: #4a5568; font-weight: 500; font-size: 14px;">
                        Welcome, {{ Auth::guard('admin')->user()->name ?? 'Administrator' }}
                    </span>
                    <a href="{{ route('logout') }}" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        @show
        {{-- =============================================== --}}

        {{-- Page Content --}}
        <div class="content">
            @yield('content')
        </div>
    </div>

    @stack('scripts')
</body>
</html>

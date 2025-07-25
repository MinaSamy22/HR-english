<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | Register</title>
    {{-- for the tiny logo in top bar --}}
    <link rel="icon" type="image/x-icon" href="{{ url('dist/img/hr_logo-.png') }}" />

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">



    <!-- for Font Awesome -->
    <!-- de 34an arbot al css bta3 adminlte lel login  -->

    <link rel="stylesheet" href="{{ url('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ url('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- hna brdp nfs el klaam -->
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('dist/css/adminlte.min.css') }}">

    <!-- mina css -->
    <link rel="stylesheet" href="{{ url('dist/css/mina.css') }}">


</head>


<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href=""><b>Human</b>Resource</a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            @include('_message')

            <div class="card-body login-card-body">
                <p class="login-box-msg">Register a new Admin</p>

                <form action="{{ route('admin.register.post') }}" method="post">
                    {{ csrf_field() }}

                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Name" name="name" required value="{{ old('name') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <span style="color:red;">{{ $errors->first('name') }}</span>

                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email" name="email" required value="{{ old('email') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <span style="color:red;">{{ $errors->first('email') }}</span>

                    <div class="form-group position-relative">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                        <span class="position-absolute" onclick="togglePassword('password', 'eyeIcon1')" style="top: 10px; right: 15px; cursor: pointer;">
                            <i id="eyeIcon1" class="fa fa-eye"></i>
                        </span>
                    </div>
                    <span style="color:red;">{{ $errors->first('password') }}</span>

                    <div class="form-group position-relative">
                        <input type="password" id="confirm_password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                        <span class="position-absolute" onclick="togglePassword('confirm_password', 'eyeIcon2')" style="top: 10px; right: 15px; cursor: pointer;">
                            <i id="eyeIcon2" class="fa fa-eye"></i>
                        </span>
                    </div>
                    <span style="color:red;">{{ $errors->first('password_confirmation') }}</span>

                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary"></div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                    </div>
                </form>

                    <!-- for going to  -->


                    <p class="mb-0">
                        <a href="{{ url('/') }}" class="text-center">Sign In</a>
                    </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->



    <!--7ot hna brdo al url -->
    <!-- jQuery -->
    <script src="{{ url('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ url('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ url('dist/js/adminlte.min.js') }}"></script>
    <!-- for eye toggle -->
    <script src="{{ url('dist/js/eye.js') }}"></script>

</body>

</html>

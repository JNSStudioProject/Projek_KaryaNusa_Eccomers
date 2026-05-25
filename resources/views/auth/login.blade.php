<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('asset/css/style.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animsition@4.0.2/dist/css/animsition.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="{{ asset('asset/css/util.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/main.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <div class="bg-overlay"></div>
    <div class="form-structor">
        <!-- Form Registrasi -->
        <div class="signup">
            <h1 class="form-title">Sign In</h1>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="form-holder">
                <!-- Form Login -->
                <h2 class="form-title"><span>or</span>Log in</h2>

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ url('/bypass-login') }}" method="POST">
                    @csrf
                    <div class="wrap-input100">
                        <span class="label-input100"><i class="bi bi-people-fill"></i> Email</span>
                        <input class="input100" type="email" name="email" placeholder="Type your Email">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="wrap-input100">
                        <span class="label-input100"><i class="bi bi-eye-fill"></i> Password</span>
                        <input class="input100" type="password" name="password" placeholder="Type your password">
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- <div class="text-right">
                        <a href="{{ route('password.request') }}">Forgot password?</a>
                    </div> --}}
                    <div class="text-right">
                        <a href="{{ route('register') }}">Dont Have Account ?</a>
                    </div>

                    <div class="container-login100-form-btn">
                        <button type="submit" class="submit-btn">Sign In</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

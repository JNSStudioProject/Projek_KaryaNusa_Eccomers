<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('asset/css/style.css') }}">
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
    <div class="form-structor">
        <div class="signup">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="form-holder" style="padding-bottom: 0px">
                        <form action="{{ route('verification.send') }}" method="POST"
                            style="padding-bottom: 0px;height: 200px">
                            @csrf
                            <div class="container" style="margin-top: 30px">
                                <h3>{{ __('Verify Your Email Address') }}</h3>
                                <p>{{ __('Before proceeding, please check your email for a verification link.') }}</p>
                                <p>{{ __('If you did not receive the email,') }}</p>
                                <button type="submit" class="btn btn-link">Kirim ulang link verifikasi</button>
                            </div>
                        </form>
                        @if (session('status'))
                            <div class="alert alert-success" role="alert" style="max-width: 30%;height: 40px">
                                {{ __('Cek Email!') }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('status'))
            Swal.fire({
                icon: 'success',
                title: 'Link Verifikasi Terkirim',
                text: 'Link verifikasi baru telah dikirim ke email Anda!',
                showConfirmButton: false,
                timer: 1500
            });
        @endif
    </script>
</body>

</html>

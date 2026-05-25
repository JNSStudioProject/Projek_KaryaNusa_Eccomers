<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Untree.co">
    <link rel="shortcut icon" href="favicon.png">
    <meta name="description" content="" />
    <meta name="keywords" content="bootstrap, bootstrap4" />
    <!-- Bootstrap CSS -->
    <!-- ICon : -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tiny-slider@2.9.3/dist/tiny-slider.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/css/main copy.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/util copy.css') }}">
    <link href="{{ asset('asset/css/furniCSS.css') }}" rel="stylesheet">
    
    <!-- Global Font: Inter (Google Fonts) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body, h1, h2, h3, h4, h5, h6, p, a, span, div, input, button, select, textarea {
            font-family: 'Inter', sans-serif !important;
        }
    </style>

    <title>{{ $title ?? 'UMKM INDONESIA' }}</title>
</head>

<body>

    <!-- Start Header/Navigation -->
    <nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">

        <div class="container">
            <a class="navbar-brand" href="#">Nusantara Craft<span>.</span></a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni"
                aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsFurni">
                <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
                    <li class="nav-item {{ Request::is('/') ? 'active' : '' }}">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item {{ Request::routeIs('Produk.index') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('Produk.index') }}">Shop</a>
                    </li>
                    <li class="nav-item {{ Request::routeIs('AboutUsCustomer') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('AboutUsCustomer') }}">About us</a>
                    </li>
                    <li class="nav-item {{ Request::routeIs('ContactCustomer') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('ContactCustomer') }}">Contact us</a>
                    </li>
                </ul>

                <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5 d-flex flex-row align-items-center" style="gap: 1.5rem;">
                    <li class="nav-item">
                        <a class="nav-link p-0" href="{{ route('pesanan.saya') }}" title="Pesanan Saya">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-bag-check-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M10.5 3.5a2.5 2.5 0 0 0-5 0V4h5v-.5zm1 0V4H15v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4h3.5v-.5a3.5 3.5 0 1 1 7 0zm-.646 5.354a.5.5 0 0 0-.708-.708L7.5 10.793 6.354 9.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l3-3z"/>
                            </svg>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-0" href="{{ route('cartCustomer') }}" title="Keranjang"> 
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-cart-fill" viewBox="0 0 16 16">
                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                            </svg>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-0" href="{{ route('logout') }}" title="Logout" style="cursor: pointer"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
                                <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
                            </svg>
                        </a>
                    </li>
                    <form action="{{ route('logout') }}" style="display: none" id="logout-form" method="POST">
                        @csrf
                    </form>
                </ul>
            </div>
        </div>

    </nav>
    <!-- Start Hero Section -->
    <div class="hero">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-5">
                    <div class="intro-excerpt">
                        <h1> @yield('hero-title', 'default Title')<span clsas="d-block"></span></h1>
                        <p class="mb-4">Dukung pengrajin Indonesia, temukan karya terbaik dari <br>UMKM Indonesia
                            Pelosok.
                        </p>
                        <p><a href="{{ route('Produk.index') }}" class="btn btn-secondary me-2">Shop Now</a><a
                                href="{{ route('Produk.index') }}" class="btn btn-white-outline">Explore</a></p>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="hero-img-wrap">
                        {{-- <img src="{{ asset('furni-1.0.0/images/couch.png') }} " class="img-fluid"> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Hero Section -->
    @yield('content')

    <!-- Start Footer Section -->
    <div class="footer-background-wrap">
        <footer class="footer-section">
            <div class="container relative">
                <div class="row g-5 mb-5">
                    <div class="col-lg-4">
                        <div class="mb-4 footer-logo-wrap"><a href="#" class="footer-logo">Nusantara
                                Craft<span>.</span></a>
                        </div>
                        <p class="mb-4"> Kami mendukung perkembangan UMKM di Indonesia dengan menyediakan produk
                            berkualitas dan layanan terbaik untuk masyarakat. Jadilah bagian dari perjalanan kami menuju
                            kemajuan ekonomi lokal.
                        </p>

                        <ul class="list-unstyled custom-social">
                            <li><a href="#"><span class="fa fa-brands fa-facebook-f"></span></a></li>
                            <li><a href="#"><span class="fa fa-brands fa-twitter"></span></a></li>
                            <li><a href="#"><span class="fa fa-brands fa-instagram"></span></a></li>
                            <li><a href="#"><span class="fa fa-brands fa-whatsapp"></span></a></li>
                        </ul>
                    </div>

                    <div class="col-lg-8">
                        <div class="row links-wrap">
                            <div class="col-6 col-sm-6 col-md-3">
                                <ul class="list-unstyled">
                                    <li><a href="{{ route('Produk.index') }}">Shop</a></li>
                                    <li><a href="{{ route('AboutUsCustomer') }}">About us</a></li>
                                    <li><a href="{{ route('ContactCustomer') }}">Contact us</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </footer>
    </div>

    <!-- End Footer Section -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tiny-slider@2.9.3/dist/tiny-slider.min.js"></script>
    <script src="{{ asset('asset/js/custom.js') }}"></script> <!-- Tetap menggunakan file lokal -->
    <script src="{{ asset('asset/js/mainFurni.js') }}"></script> <!-- Tetap menggunakan file lokal -->
    <script src="{{ asset('asset/js/map-custom.js') }}"></script> <!-- Tetap menggunakan file lokal -->
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    @yield('script')

</body>

</html>

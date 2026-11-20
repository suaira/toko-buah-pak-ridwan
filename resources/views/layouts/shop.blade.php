<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Toko Buah Pak Ridwan - Buah Segar Pilihan Terbaik')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Toko Buah Pak Ridwan, Buah Segar Pekanbaru, Toko Buah Segar" name="keywords">
    <meta content="Toko Buah Pak Ridwan menyediakan buah lokal dan impor berkualitas, segar setiap hari dengan harga bersahabat dan layanan antar ke rumah." name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('fruitables/lib/lightbox/css/lightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('fruitables/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('fruitables/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('fruitables/css/style.css') }}" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', 'Open Sans', sans-serif;
        }
        .navbar-brand h1 {
            font-family: 'Raleway', sans-serif;
            font-weight: 800;
        }
        .badge-promo {
            background: linear-gradient(135deg, #ff4757, #ff6b81);
            color: #fff;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            position: absolute;
            top: 15px;
            right: 15px;
            box-shadow: 0 4px 10px rgba(255, 71, 87, 0.3);
            z-index: 2;
        }
        .badge-stock {
            position: absolute;
            top: 15px;
            left: 15px;
            z-index: 2;
        }
        .fruite-item {
            border-radius: 18px;
            overflow: hidden;
            transition: all 0.3s ease-in-out;
            background: #ffffff;
        }
        .fruite-item:hover {
            transform: translateY(-7px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
        }
        .fruite-img img {
            transition: transform 0.4s ease;
            height: 220px;
            object-fit: cover;
            width: 100%;
        }
        .fruite-item:hover .fruite-img img {
            transform: scale(1.06);
        }
        .hero-header {
            background: linear-gradient(rgba(244, 246, 248, 0.9), rgba(244, 246, 248, 0.9)), url("{{ asset('fruitables/img/hero-img.jpg') }}");
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .single-page-header {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url("{{ asset('fruitables/img/cart-page-header-img.jpg') }}");
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
            padding: 100px 0 60px 0;
        }
        .btn-primary {
            background-color: #81c408 !important;
            border-color: #81c408 !important;
            color: #ffffff !important;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #6da706 !important;
            border-color: #6da706 !important;
        }
        .text-primary {
            color: #81c408 !important;
        }
        .bg-primary {
            background-color: #81c408 !important;
        }
        .border-primary {
            border-color: #81c408 !important;
        }
    </style>
    @yield('styles')
</head>

<body>

    <!-- Spinner Start -->
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar start -->
    <div class="container-fluid fixed-top">
        <div class="container topbar bg-primary d-none d-lg-block">
            <div class="d-flex justify-content-between">
                <div class="top-info ps-2">
                    <small class="me-3 text-white"><i class="fas fa-map-marker-alt me-2 text-secondary"></i> Jl. Umban Sari / Sail, Pekanbaru, Riau</small>
                    <small class="me-3 text-white"><i class="fas fa-phone-alt me-2 text-secondary"></i> +62 812-3456-7890</small>
                    <small class="text-white"><i class="fas fa-clock me-2 text-secondary"></i> Buka Setiap Hari: 07.30 - 21.30 WIB</small>
                </div>
                <div class="top-link pe-2">
                    @auth
                        <span class="text-white me-2">Halo, <strong>{{ Auth::user()->name }}</strong> ({{ strtoupper(Auth::user()->role) }})</span>
                    @else
                        <a href="{{ route('login') }}" class="text-white"><small class="text-white mx-2"><i class="fas fa-sign-in-alt me-1"></i> Masuk</small></a>|
                        <a href="{{ route('register') }}" class="text-white"><small class="text-white mx-2"><i class="fas fa-user-plus me-1"></i> Daftar</small></a>
                    @endauth
                </div>
            </div>
        </div>
        <div class="container px-0">
            <nav class="navbar navbar-light bg-white navbar-expand-xl">
                <a href="{{ route('home') }}" class="navbar-brand">
                    <h1 class="text-primary display-6 mb-0">
                        <i class="fas fa-apple-alt text-secondary me-2"></i>Pak Ridwan
                    </h1>
                    <small class="text-muted d-block" style="font-size: 0.72rem; letter-spacing: 1px;">TOKO BUAH SEGAR & ALAMI</small>
                </a>
                <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars text-primary"></span>
                </button>
                <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                    <div class="navbar-nav mx-auto">
                        <a href="{{ route('home') }}" class="nav-item nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                        <a href="{{ route('shop') }}" class="nav-item nav-link {{ request()->routeIs('shop*') ? 'active' : '' }}">Katalog Buah</a>
                        <a href="{{ route('contact') }}" class="nav-item nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Tentang & Kontak</a>
                        
                        @auth
                            @if(Auth::user()->isCustomer())
                                <a href="{{ route('orders.my') }}" class="nav-item nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">Pesanan Saya</a>
                            @elseif(Auth::user()->isKasir())
                                <a href="{{ route('kasir.dashboard') }}" class="nav-item nav-link text-warning fw-bold">Panel Kasir <i class="fas fa-external-link-alt ms-1"></i></a>
                            @elseif(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="nav-item nav-link text-success fw-bold">Panel Admin <i class="fas fa-external-link-alt ms-1"></i></a>
                            @endif
                        @endauth
                    </div>
                    <div class="d-flex m-3 me-0 align-items-center">
                        <button class="btn-search btn border border-secondary btn-md-square rounded-circle bg-white me-3" data-bs-toggle="modal" data-bs-target="#searchModal">
                            <i class="fas fa-search text-primary"></i>
                        </button>
                        
                        @php
                            $cartCount = 0;
                            if (Auth::check() && Auth::user()->isCustomer() && Auth::user()->pelanggan) {
                                $cartCount = \App\Models\Keranjang::where('id_pelanggan', Auth::user()->pelanggan->id_pelanggan)->count();
                            }
                        @endphp
                        <a href="{{ route('cart') }}" class="position-relative me-4 my-auto" title="Keranjang Belanja">
                            <i class="fa fa-shopping-bag fa-2x text-primary"></i>
                            <span class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1" style="top: -5px; left: 15px; height: 20px; min-width: 20px;">
                                {{ $cartCount }}
                            </span>
                        </a>

                        @auth
                            <div class="dropdown my-auto">
                                <a href="#" class="dropdown-toggle text-dark fw-bold d-flex align-items-center" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle fa-2x text-primary me-2"></i>
                                    <span>{{ Str::limit(Auth::user()->name, 12) }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                    <li><h6 class="dropdown-header text-uppercase text-muted" style="font-size: 0.75rem;">Akun: {{ Auth::user()->role }}</h6></li>
                                    <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user-edit me-2 text-primary"></i> Profil Saya</a></li>
                                    @if(Auth::user()->isCustomer())
                                        <li><a class="dropdown-item" href="{{ route('orders.my') }}"><i class="fas fa-box me-2 text-primary"></i> Pesanan Saya</a></li>
                                        <li><a class="dropdown-item" href="{{ route('cart') }}"><i class="fas fa-shopping-cart me-2 text-primary"></i> Keranjang</a></li>
                                    @elseif(Auth::user()->isKasir())
                                        <li><a class="dropdown-item text-primary fw-bold" href="{{ route('kasir.dashboard') }}"><i class="fas fa-cash-register me-2"></i> Panel Kasir</a></li>
                                    @elseif(Auth::user()->isAdmin())
                                        <li><a class="dropdown-item text-success fw-bold" href="{{ route('admin.dashboard') }}"><i class="fas fa-shield-alt me-2"></i> Panel Admin</a></li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i> Keluar</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-3 py-1 my-auto">
                                <i class="fas fa-user me-1"></i> Masuk
                            </a>
                        @endauth
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->

    <!-- Modal Search Start -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cari Buah Segar di Toko Pak Ridwan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex align-items-center">
                    <form action="{{ route('shop') }}" method="GET" class="input-group w-75 mx-auto d-flex">
                        <input type="search" name="q" class="form-control p-3" placeholder="Ketik nama buah (contoh: Apel, Mangga, Jeruk)..." aria-describedby="search-icon-1">
                        <button type="submit" id="search-icon-1" class="input-group-text p-3 bg-primary text-white border-0"><i class="fa fa-search"></i> Cari</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Search End -->

    <!-- Flash Messages -->
    <div class="container" style="margin-top: 130px; margin-bottom: -100px; position: relative; z-index: 100;">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 d-flex align-items-center" role="alert">
                <i class="fas fa-check-circle fa-lg me-3 text-success"></i>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-circle fa-lg me-3 text-danger"></i>
                <div class="flex-grow-1">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show shadow-sm rounded-3 d-flex align-items-center" role="alert">
                <i class="fas fa-info-circle fa-lg me-3 text-info"></i>
                <div class="flex-grow-1">{{ session('info') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
        <div class="container py-5">
            <div class="pb-4 mb-4" style="border-bottom: 1px solid rgba(226, 175, 24, 0.5) ;">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-4">
                        <a href="{{ route('home') }}">
                            <h1 class="text-primary mb-0">Toko Buah Pak Ridwan</h1>
                            <p class="text-secondary mb-0">100% Buah Segar Berkualitas</p>
                        </a>
                    </div>
                    <div class="col-lg-5">
                        <p class="mb-0 text-light">Belanja buah segar harian, parcel sehat, buah lokal & impor langsung ke rumah Anda di wilayah Pekanbaru!</p>
                    </div>
                    <div class="col-lg-3 text-lg-end">
                        <div class="d-flex justify-content-end pt-3">
                            <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-whatsapp"></i></a>
                            <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-5">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-item">
                        <h4 class="text-light mb-3">Tentang Kami</h4>
                        <p class="mb-4">Toko Buah Pak Ridwan berdedikasi menyediakan buah-buahan segar terbaik dengan proses sortir higienis setiap hari. Layanan ramah dan harga terjangkau.</p>
                        <a href="{{ route('shop') }}" class="btn border-secondary py-2 px-4 rounded-pill text-primary">Mulai Belanja</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex flex-column text-start footer-item">
                        <h4 class="text-light mb-3">Menu Cepat</h4>
                        <a class="btn-link" href="{{ route('home') }}">Beranda</a>
                        <a class="btn-link" href="{{ route('shop') }}">Katalog Buah Lengkap</a>
                        <a class="btn-link" href="{{ route('cart') }}">Keranjang Belanja</a>
                        <a class="btn-link" href="{{ route('contact') }}">Hubungi Toko</a>
                        @auth
                            <a class="btn-link" href="{{ route('orders.my') }}">Pesanan Saya</a>
                        @else
                            <a class="btn-link" href="{{ route('login') }}">Login Akun</a>
                            <a class="btn-link" href="{{ route('register') }}">Registrasi Pelanggan</a>
                        @endauth
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="footer-item">
                        <h4 class="text-light mb-3">Kontak Toko</h4>
                        <p><i class="fas fa-map-marker-alt me-2 text-primary"></i> Pekanbaru, Riau, Indonesia</p>
                        <p><i class="fas fa-envelope me-2 text-primary"></i> info@tokobuahpakridwan.com</p>
                        <p><i class="fas fa-phone-alt me-2 text-primary"></i> +62 812-3456-7890</p>
                        <p><i class="fas fa-money-bill-wave me-2 text-primary"></i> Pembayaran: Transfer Bank & COD</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Copyright Start -->
    <div class="container-fluid copyright bg-dark py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <span class="text-light"><a href="#" class="text-white fw-bold"><i class="fas fa-copyright text-light me-2"></i>Toko Buah Pak Ridwan</a>, All rights reserved.</span>
                </div>
                <div class="col-md-6 my-auto text-center text-md-end text-white">
                    Projek Bengkel Pemrograman Framework 1 - Kelompok 3 (Puti Salwa, Syafiqah Zahra, Zulfika Madinatul Ilmi)
                </div>
            </div>
        </div>
    </div>
    <!-- Copyright End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>   

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('fruitables/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('fruitables/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('fruitables/lib/lightbox/js/lightbox.min.js') }}"></script>
    <script src="{{ asset('fruitables/lib/owlcarousel/owl.carousel.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('fruitables/js/main.js') }}"></script>
    @yield('scripts')
</body>
</html>

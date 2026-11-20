<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Panel Kasir - Toko Buah Pak Ridwan')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="{{ asset('fruitables/css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7f6;
            color: #333;
        }
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: #ffffff;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s;
        }
        .main-content {
            margin-left: 260px;
            padding: 30px;
            min-height: 100vh;
        }
        .sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid #edf2f7;
        }
        .nav-link {
            padding: 12px 20px;
            color: #555;
            font-weight: 500;
            border-radius: 12px;
            margin: 4px 12px;
            display: flex;
            align-items: center;
            transition: all 0.2s;
        }
        .nav-link i {
            width: 24px;
            font-size: 1.1rem;
            margin-right: 12px;
        }
        .nav-link:hover {
            color: #81c408;
            background-color: #f0f9e8;
        }
        .nav-link.active {
            color: #ffffff;
            background-color: #81c408;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(129, 196, 8, 0.3);
        }
        .card-stat {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
            transition: transform 0.2s;
        }
        .card-stat:hover {
            transform: translateY(-4px);
        }
        .topbar {
            background: #ffffff;
            padding: 15px 30px;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
            margin-bottom: 25px;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column justify-content-between">
        <div>
            <div class="sidebar-brand d-flex align-items-center">
                <i class="fas fa-apple-alt text-success fa-2x me-2"></i>
                <div>
                    <h5 class="mb-0 fw-bold text-dark">Kasir Buah</h5>
                    <small class="text-muted" style="font-size: 0.72rem;">PAK RIDWAN STORE</small>
                </div>
            </div>

            <div class="nav flex-column mt-3">
                <a href="{{ route('kasir.dashboard') }}" class="nav-link {{ request()->routeIs('kasir.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="{{ route('kasir.orders') }}" class="nav-link {{ request()->routeIs('kasir.orders*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i> Pesanan Online
                    @php
                        $waitingCount = \App\Models\Pesanan::where('status', 'menunggu')->count();
                    @endphp
                    @if($waitingCount > 0)
                        <span class="badge bg-danger rounded-pill ms-auto">{{ $waitingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('kasir.pos') }}" class="nav-link {{ request()->routeIs('kasir.pos*') ? 'active' : '' }}">
                    <i class="fas fa-cash-register"></i> Kasir POS Toko
                </a>
                <a href="{{ route('kasir.payments') }}" class="nav-link {{ request()->routeIs('kasir.payments*') ? 'active' : '' }}">
                    <i class="fas fa-credit-card"></i> Validasi Bayar
                </a>
                <a href="{{ route('kasir.stocks') }}" class="nav-link {{ request()->routeIs('kasir.stocks*') ? 'active' : '' }}">
                    <i class="fas fa-boxes"></i> Pantau Stok & Promo
                </a>
                <a href="{{ route('kasir.daily-report') }}" class="nav-link {{ request()->routeIs('kasir.daily-report*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i> Rekap Tutup Kasir
                </a>
            </div>
        </div>

        <div class="p-3 border-top">
            <a href="{{ route('home') }}" class="nav-link text-primary mb-2" target="_blank">
                <i class="fas fa-globe"></i> Lihat Toko Web
            </a>
            <div class="d-flex align-items-center px-2 py-2">
                <div class="bg-warning text-dark fw-bold rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 38px; height: 38px;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <h6 class="mb-0 text-truncate fw-bold text-dark" style="font-size: 0.85rem;">{{ Auth::user()->name }}</h6>
                    <small class="text-muted" style="font-size: 0.72rem;">Karyawan Kasir</small>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-light text-danger rounded-circle p-2" title="Keluar">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold text-dark mb-0">@yield('page_title', 'Panel Petugas Kasir')</h4>
                <small class="text-muted">{{ now()->translatedFormat('l, d F Y') }}</small>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('kasir.pos') }}" class="btn btn-success rounded-pill px-3 py-2 fw-bold text-white shadow-sm">
                    <i class="fas fa-plus-circle me-1"></i> Transaksi Kasir Baru
                </a>
                <a href="{{ route('profile') }}" class="btn btn-light rounded-pill px-3 py-2 border">
                    <i class="fas fa-user-circle me-1"></i> Profil
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm d-flex align-items-center" role="alert">
                <i class="fas fa-check-circle fa-lg me-3 text-success"></i>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-circle fa-lg me-3 text-danger"></i>
                <div class="flex-grow-1">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>

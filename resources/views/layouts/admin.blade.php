<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Panel Owner / Admin - Toko Buah Pak Ridwan')</title>
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
            width: 270px;
            min-height: 100vh;
            background: #ffffff;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s;
            overflow-y: auto;
        }
        .main-content {
            margin-left: 270px;
            padding: 30px;
            min-height: 100vh;
        }
        .sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid #edf2f7;
        }
        .nav-link {
            padding: 11px 18px;
            color: #555;
            font-weight: 500;
            border-radius: 12px;
            margin: 3px 12px;
            display: flex;
            align-items: center;
            transition: all 0.2s;
            font-size: 0.92rem;
        }
        .nav-link i {
            width: 24px;
            font-size: 1.05rem;
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

    <!-- Sidebar Admin -->
    <div class="sidebar d-flex flex-column justify-content-between">
        <div>
            <div class="sidebar-brand d-flex align-items-center">
                <i class="fas fa-crown text-warning fa-2x me-2"></i>
                <div>
                    <h5 class="mb-0 fw-bold text-dark">Panel Pemilik</h5>
                    <small class="text-success fw-bold" style="font-size: 0.72rem;">PAK RIDWAN (OWNER)</small>
                </div>
            </div>

            <div class="nav flex-column mt-3">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i> Dashboard Utama
                </a>

                <div class="px-3 pt-3 pb-1 text-uppercase text-muted fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Katalog & Stok</div>
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.index') || request()->routeIs('admin.products.create') || request()->routeIs('admin.products.edit') ? 'active' : '' }}">
                    <i class="fas fa-apple-alt"></i> Kelola Buah & Harga
                </a>
                <a href="{{ route('admin.products.stock-history') }}" class="nav-link {{ request()->routeIs('admin.products.stock-history') ? 'active' : '' }}">
                    <i class="fas fa-history"></i> Riwayat & Jejak Stok
                </a>
                <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-folder"></i> Kategori Buah
                </a>

                <div class="px-3 pt-3 pb-1 text-uppercase text-muted fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Promosi & Area</div>
                <a href="{{ route('admin.promos.index') }}" class="nav-link {{ request()->routeIs('admin.promos.*') ? 'active' : '' }}">
                    <i class="fas fa-percent"></i> Promo & Diskon
                </a>
                <a href="{{ route('admin.areas.index') }}" class="nav-link {{ request()->routeIs('admin.areas.*') ? 'active' : '' }}">
                    <i class="fas fa-map-marked-alt"></i> Area Antar & Ongkir
                </a>

                <div class="px-3 pt-3 pb-1 text-uppercase text-muted fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Pengguna & Transaksi</div>
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users-cog"></i> Akun Kasir & Pelanggan
                </a>
                <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="fas fa-receipt"></i> Semua Transaksi
                </a>

                <div class="px-3 pt-3 pb-1 text-uppercase text-muted fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Laporan</div>
                <a href="{{ route('admin.reports.sales') }}" class="nav-link {{ request()->routeIs('admin.reports.sales') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice-dollar"></i> Laporan Penjualan
                </a>
            </div>
        </div>

        <div class="p-3 border-top">
            <a href="{{ route('home') }}" class="nav-link text-primary mb-2" target="_blank">
                <i class="fas fa-globe"></i> Lihat Toko Web
            </a>
            <div class="d-flex align-items-center px-2 py-2">
                <div class="bg-success text-white fw-bold rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 38px; height: 38px;">
                    PR
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <h6 class="mb-0 text-truncate fw-bold text-dark" style="font-size: 0.85rem;">Pak Ridwan</h6>
                    <small class="text-muted" style="font-size: 0.72rem;">Pemilik Toko</small>
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
                <h4 class="fw-bold text-dark mb-0">@yield('page_title', 'Dashboard Pemilik Toko')</h4>
                <small class="text-muted">{{ now()->translatedFormat('l, d F Y') }}</small>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary rounded-pill px-3 py-2 fw-bold text-white shadow-sm">
                    <i class="fas fa-plus-circle me-1"></i> Tambah Buah Baru
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

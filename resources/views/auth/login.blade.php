@extends('layouts.shop')

@section('title', 'Login Akun - Toko Buah Pak Ridwan')

@section('content')
<!-- Single Page Header start -->
<div class="container-fluid page-header py-5 single-page-header">
    <h1 class="text-center text-white display-6 fw-bold">Masuk ke Akun</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Beranda</a></li>
        <li class="breadcrumb-item active text-white">Login</li>
    </ol>
</div>
<!-- Single Page Header End -->

<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card border rounded-4 shadow-sm overflow-hidden">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <i class="fas fa-apple-alt fa-3x mb-2 text-warning"></i>
                        <h4 class="fw-bold mb-0 text-white">Toko Buah Pak Ridwan</h4>
                        <small class="text-white-50">Silakan masukkan email dan kata sandi Anda</small>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('login.post') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold text-dark">Alamat Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                                    <input type="email" name="email" id="emailInput" class="form-control border-start-0 py-2 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label fw-bold text-dark">Kata Sandi (Password)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                    <input type="password" name="password" id="passwordInput" class="form-control border-start-0 py-2 @error('password') is-invalid @enderror" placeholder="••••••••" required>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                                    <label class="form-check-label text-muted small" for="rememberMe">
                                        Ingat saya
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill text-white fw-bold shadow fs-6">
                                <i class="fas fa-sign-in-alt me-2"></i> Masuk Sekarang
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <p class="text-muted small mb-0">Belum punya akun pelanggan? 
                                <a href="{{ route('register') }}" class="text-primary fw-bold">Daftar Akun Baru</a>
                            </p>
                        </div>

                        <hr class="my-4">

                        <!-- Quick Login Demo Buttons (Sangat praktis untuk dosen & penguji!) -->
                        <div class="p-3 bg-light rounded-3 border">
                            <small class="fw-bold text-muted d-block mb-2 text-center text-uppercase" style="font-size: 0.72rem; letter-spacing: 1px;">
                                <i class="fas fa-bolt text-warning me-1"></i> Quick Login (Demonstrasi Demo):
                            </small>
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-sm btn-outline-success text-start" onclick="fillLogin('admin@tokobuahpakridwan.com', 'password')">
                                    <i class="fas fa-crown text-warning me-1"></i> <strong>Admin (Pak Ridwan)</strong> <small class="text-muted">admin@tokobuahpakridwan.com</small>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-warning text-start text-dark" onclick="fillLogin('kasir@tokobuahpakridwan.com', 'password')">
                                    <i class="fas fa-cash-register text-primary me-1"></i> <strong>Kasir (Siti Kasir)</strong> <small class="text-muted">kasir@tokobuahpakridwan.com</small>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary text-start" onclick="fillLogin('pika@gmail.com', 'password')">
                                    <i class="fas fa-user text-info me-1"></i> <strong>Customer (Syafiqah Zahra)</strong> <small class="text-muted">pika@gmail.com</small>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function fillLogin(email, password) {
        document.getElementById('emailInput').value = email;
        document.getElementById('passwordInput').value = password;
    }
</script>
@endsection

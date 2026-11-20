@extends('layouts.shop')

@section('title', 'Daftar Akun Pelanggan - Toko Buah Pak Ridwan')

@section('content')
<!-- Single Page Header start -->
<div class="container-fluid page-header py-5 single-page-header">
    <h1 class="text-center text-white display-6 fw-bold">Daftar Akun Pelanggan</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Beranda</a></li>
        <li class="breadcrumb-item active text-white">Registrasi</li>
    </ol>
</div>
<!-- Single Page Header End -->

<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-7 col-xl-6">
                <div class="card border rounded-4 shadow-sm overflow-hidden">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <i class="fas fa-user-plus fa-3x mb-2 text-warning"></i>
                        <h4 class="fw-bold mb-0 text-white">Registrasi Pelanggan Baru</h4>
                        <small class="text-white-50">Daftarkan akun Anda untuk kemudahan belanja & promo eksklusif</small>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('register.post') }}" method="POST">
                            @csrf
                            
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold text-dark">Nama Lengkap <sup>*</sup></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                                    <input type="text" name="name" class="form-control border-start-0 py-2 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Syafiqah Zahra" required autofocus>
                                </div>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label fw-bold text-dark">Alamat Email <sup>*</sup></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0 py-2 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="nama@email.com" required>
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label fw-bold text-dark">Nomor WhatsApp / HP <sup>*</sup></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-phone text-muted"></i></span>
                                    <input type="text" name="no_hp" class="form-control border-start-0 py-2 @error('no_hp') is-invalid @enderror" value="{{ old('no_hp') }}" placeholder="Contoh: 081234567890" required>
                                </div>
                                @error('no_hp')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label fw-bold text-dark">Alamat Pengantaran Utama (Pekanbaru)</label>
                                <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="2" placeholder="Jalan, No Rumah, Kelurahan, Kecamatan...">{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label class="form-label fw-bold text-dark">Kata Sandi <sup>*</sup></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                        <input type="password" name="password" class="form-control border-start-0 py-2 @error('password') is-invalid @enderror" placeholder="Min. 6 karakter" required>
                                    </div>
                                    @error('password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 form-group mb-4">
                                    <label class="form-label fw-bold text-dark">Ulangi Kata Sandi <sup>*</sup></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-check-circle text-muted"></i></span>
                                        <input type="password" name="password_confirmation" class="form-control border-start-0 py-2" placeholder="Ulangi kata sandi" required>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill text-white fw-bold shadow fs-6">
                                <i class="fas fa-user-check me-2"></i> Buat Akun Pelanggan Sekarang
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <p class="text-muted small mb-0">Sudah memiliki akun? 
                                <a href="{{ route('login') }}" class="text-primary fw-bold">Login ke Akun Anda</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

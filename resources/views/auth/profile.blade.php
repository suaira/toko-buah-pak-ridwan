@extends('layouts.shop')

@section('title', 'Profil Akun - Toko Buah Pak Ridwan')

@section('content')
<!-- Single Page Header start -->
<div class="container-fluid page-header py-5 single-page-header">
    <h1 class="text-center text-white display-6 fw-bold">Profil Akun Saya</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Beranda</a></li>
        <li class="breadcrumb-item active text-white">Profil</li>
    </ol>
</div>
<!-- Single Page Header End -->

<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-7">
                <div class="card border rounded-4 shadow-sm overflow-hidden">
                    <div class="card-header bg-primary text-white py-4 px-4 d-flex align-items-center">
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold me-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h4 class="mb-0 text-white fw-bold">{{ $user->name }}</h4>
                            <span class="badge bg-secondary text-dark text-uppercase px-2 py-1 mt-1">Peran: {{ $user->role }}</span>
                        </div>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-id-card text-primary me-2"></i>Informasi Kontak & Akun</h5>

                            <div class="form-group mb-3">
                                <label class="form-label fw-bold text-dark">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control py-2" value="{{ old('name', $user->name) }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label fw-bold text-dark">Email Akun</label>
                                <input type="email" name="email" class="form-control py-2" value="{{ old('email', $user->email) }}" required>
                            </div>

                            @if($user->isCustomer())
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold text-dark">Nomor WhatsApp / HP</label>
                                    <input type="text" name="no_hp" class="form-control py-2" value="{{ old('no_hp', $pelanggan->no_hp ?? '') }}" placeholder="08xxxxxxxxxx">
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold text-dark">Alamat Pengantaran Utama (Pekanbaru)</label>
                                    <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat rumah lengkap untuk pengiriman buah...">{{ old('alamat', $pelanggan->alamat ?? '') }}</textarea>
                                </div>
                            @endif

                            <hr class="my-4">

                            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-key text-primary me-2"></i>Ubah Kata Sandi (Opsional)</h5>
                            <p class="text-muted small mb-3">Biarkan kosong jika tidak ingin mengganti kata sandi.</p>

                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label class="form-label fw-bold text-dark">Kata Sandi Baru</label>
                                    <input type="password" name="password" class="form-control py-2" placeholder="Minimal 6 karakter">
                                </div>
                                <div class="col-md-6 form-group mb-4">
                                    <label class="form-label fw-bold text-dark">Ulangi Kata Sandi Baru</label>
                                    <input type="password" name="password_confirmation" class="form-control py-2" placeholder="Ulangi kata sandi baru">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 text-white fw-bold shadow">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan Profil
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

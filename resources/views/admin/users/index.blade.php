@extends('layouts.admin')

@section('title', 'Manajemen Pengguna - Panel Pemilik')
@section('page_title', 'Kelola Akun Kasir & Data Pelanggan')

@section('content')
<!-- Nav Tabs -->
<ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active px-4 py-2 rounded-pill fw-bold" id="pills-kasir-tab" data-bs-toggle="pill" data-bs-target="#pills-kasir" type="button" role="tab">
            <i class="fas fa-cash-register me-2"></i>Akun Petugas Kasir ({{ $kasirList->count() }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link px-4 py-2 rounded-pill fw-bold ms-2" id="pills-pelanggan-tab" data-bs-toggle="pill" data-bs-target="#pills-pelanggan" type="button" role="tab">
            <i class="fas fa-users me-2"></i>Data Profil Pelanggan ({{ $pelangganList->count() }})
        </button>
    </li>
</ul>

<div class="tab-content" id="pills-tabContent">
    <!-- TAB 1: KASIR -->
    <div class="tab-pane fade show active" id="pills-kasir" role="tabpanel">
        <div class="row g-4">
            <!-- Form Buat Kasir Baru -->
            <div class="col-lg-4">
                <div class="card border-0 rounded-4 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 px-4 border-bottom">
                        <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-user-plus text-primary me-2"></i>Tambah Akun Kasir</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.users.kasir.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Petugas Kasir <sup>*</sup></label>
                                <input type="text" name="name" class="form-control" placeholder="Contoh: Siti Kasir" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Email Login <sup>*</sup></label>
                                <input type="email" name="email" class="form-control" placeholder="kasir2@tokobuahpakridwan.com" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Kata Sandi (Password) <sup>*</sup></label>
                                <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                            </div>

                            <button type="submit" class="btn btn-primary rounded-pill w-100 py-2 fw-bold text-white shadow-sm">
                                <i class="fas fa-save me-1"></i> Buat Akun Kasir
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tabel Akun Kasir -->
            <div class="col-lg-8">
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-id-badge text-primary me-2"></i>Daftar Petugas Kasir</h5>
                        <small class="text-muted">Akses kasir dibuat khusus oleh Pak Ridwan</small>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 text-center">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-start ps-4">Nama Petugas</th>
                                        <th class="text-start">Email Login</th>
                                        <th>Status Akun</th>
                                        <th class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($kasirList as $kasir)
                                    <tr>
                                        <td class="text-start ps-4">
                                            <div class="fw-bold text-dark">{{ $kasir->name }}</div>
                                            <small class="text-muted">ID: #{{ $kasir->id }}</small>
                                        </td>
                                        <td class="text-start text-muted">{{ $kasir->email }}</td>
                                        <td>
                                            <span class="badge {{ $kasir->aktif ? 'bg-success' : 'bg-danger' }} rounded-pill px-3 py-1">
                                                {{ $kasir->aktif ? 'Aktif Bekerja' : 'Dinonaktifkan' }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <!-- Tombol Edit Kasir -->
                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-circle me-1" data-bs-toggle="modal" data-bs-target="#modalEditKasir{{ $kasir->id }}" title="Edit Kasir">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <!-- Tombol Toggle Nonaktifkan (Sesuai PDF: D = nonaktifkan) -->
                                            <form action="{{ route('admin.users.toggle', $kasir->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin {{ $kasir->aktif ? 'menonaktifkan' : 'mengaktifkan kembali' }} akun kasir {{ $kasir->name }}?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ $kasir->aktif ? 'btn-outline-danger' : 'btn-outline-success' }} rounded-pill px-2">
                                                    <i class="fas {{ $kasir->aktif ? 'fa-ban' : 'fa-check' }} me-1"></i>
                                                    {{ $kasir->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>

                                            <!-- Modal Edit Kasir -->
                                            <div class="modal fade" id="modalEditKasir{{ $kasir->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content rounded-4 border-0 shadow text-start">
                                                        <div class="modal-header bg-light">
                                                            <h5 class="modal-title fw-bold">Edit Kasir: {{ $kasir->name }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('admin.users.kasir.update', $kasir->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body p-4">
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold">Nama Petugas:</label>
                                                                    <input type="text" name="name" class="form-control" value="{{ $kasir->name }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold">Email Login:</label>
                                                                    <input type="email" name="email" class="form-control" value="{{ $kasir->email }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-bold">Password Baru (Opsional):</label>
                                                                    <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak diubah">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer bg-light">
                                                                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-primary rounded-pill px-4 text-white fw-bold">Simpan Perubahan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="py-4 text-muted">Belum ada akun kasir.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 2: PELANGGAN -->
    <div class="tab-pane fade" id="pills-pelanggan" role="tabpanel">
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-address-book text-primary me-2"></i>Data Pelanggan Toko Buah</h5>
                <small class="text-muted">Profil dibuat otomatis saat customer register</small>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 text-center">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-start ps-4">Nama Pelanggan</th>
                                <th class="text-start">Kontak / No WhatsApp</th>
                                <th class="text-start">Alamat Utama</th>
                                <th>Status Akun</th>
                                <th>Terdaftar Sejak</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pelangganList as $pel)
                            <tr>
                                <td class="text-start ps-4">
                                    <div class="fw-bold text-dark">{{ $pel->nama_pelanggan }}</div>
                                    <small class="text-muted">{{ $pel->user->email ?? '-' }}</small>
                                </td>
                                <td class="text-start">
                                    <span class="fw-bold text-dark"><i class="fab fa-whatsapp text-success me-1"></i> {{ $pel->no_hp }}</span>
                                </td>
                                <td class="text-start text-muted small" style="max-width: 250px;">
                                    {{ $pel->alamat ?: 'Belum diisi' }}
                                </td>
                                <td>
                                    @if(optional($pel->user)->aktif)
                                        <span class="badge bg-success rounded-pill px-3 py-1">Aktif</span>
                                    @else
                                        <span class="badge bg-danger rounded-pill px-3 py-1">Dinonaktifkan</span>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ $pel->created_at->format('d/m/Y') }}</td>
                                <td class="text-end pe-4">
                                    @if($pel->user)
                                    <form action="{{ route('admin.users.toggle', $pel->user_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda ingin {{ $pel->user->aktif ? 'menonaktifkan' : 'mengaktifkan kembali' }} akun pelanggan {{ $pel->nama_pelanggan }}?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $pel->user->aktif ? 'btn-outline-danger' : 'btn-outline-success' }} rounded-pill px-3 py-1">
                                            <i class="fas {{ $pel->user->aktif ? 'fa-user-slash' : 'fa-user-check' }} me-1"></i>
                                            {{ $pel->user->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-4 text-muted">Belum ada pelanggan yang mendaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

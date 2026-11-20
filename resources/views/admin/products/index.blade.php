@extends('layouts.admin')

@section('title', 'Kelola Buah & Stok - Panel Pemilik')
@section('page_title', 'Manajemen Produk Buah & Stok')

@section('content')
<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex gap-2 flex-grow-1" style="max-width: 500px;">
                <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Cari nama buah...">
                <select name="kategori" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($kategoriList as $kat)
                        <option value="{{ $kat->id_kategori }}" {{ request('kategori') == $kat->id_kategori ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-secondary px-3"><i class="fas fa-search"></i></button>
            </form>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.products.stock-history') }}" class="btn btn-outline-secondary rounded-pill px-3">
                    <i class="fas fa-history me-1"></i> Jejak Riwayat Stok
                </a>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary rounded-pill px-4 text-white fw-bold shadow-sm">
                    <i class="fas fa-plus me-1"></i> Tambah Buah Baru
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0 text-center">
                <thead class="bg-light">
                    <tr>
                        <th class="text-start ps-4">Foto & Nama Buah</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th class="text-end">Harga Jual</th>
                        <th class="text-center">Sisa Stok</th>
                        <th>Status Tampil</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                    <tr>
                        <td class="text-start ps-4">
                            <div class="d-flex align-items-center">
                                @php
                                    $imgUrl = asset('fruitables/img/' . ($p->gambar ?: 'fruite-item-5.jpg'));
                                    if (file_exists(public_path('storage/products/' . $p->gambar))) {
                                        $imgUrl = asset('storage/products/' . $p->gambar);
                                    }
                                @endphp
                                <img src="{{ $imgUrl }}" class="rounded-3 me-3 border shadow-sm" style="width: 50px; height: 50px; object-fit: cover;" alt="">
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark">{{ $p->nama_produk }}</h6>
                                    <small class="text-muted">ID: #{{ $p->id_produk }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $p->kategori->nama_kategori ?? '-' }}</span>
                        </td>
                        <td><span class="text-uppercase fw-bold text-muted small">{{ $p->satuan }}</span></td>
                        <td class="text-end fw-bold text-dark">
                            Rp {{ number_format($p->harga, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $p->stok <= 10 ? 'bg-danger' : 'bg-success' }} fs-6 px-3 py-1">
                                {{ floatval($p->stok) }} {{ $p->satuan }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.products.toggle', $p->id_produk) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $p->aktif ? 'btn-success' : 'btn-secondary' }} rounded-pill px-3 py-1" style="font-size: 0.75rem;">
                                    <i class="fas {{ $p->aktif ? 'fa-check' : 'fa-eye-slash' }} me-1"></i>
                                    {{ $p->aktif ? 'Aktif di Katalog' : 'Disembunyikan' }}
                                </button>
                            </form>
                        </td>
                        <td class="text-end pe-4">
                            <!-- Tombol Modal Sesuaikan Stok -->
                            <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-2 me-1 text-dark" data-bs-toggle="modal" data-bs-target="#modalStok{{ $p->id_produk }}" title="Input Stok Masuk / Koreksi">
                                <i class="fas fa-boxes me-1"></i> Stok
                            </button>

                            <a href="{{ route('admin.products.edit', $p->id_produk) }}" class="btn btn-sm btn-outline-primary rounded-circle" title="Edit Buah">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- Modal Sesuaikan Stok -->
                            <div class="modal fade" id="modalStok{{ $p->id_produk }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <div class="modal-header bg-light">
                                            <h5 class="modal-title fw-bold">Kelola Stok: {{ $p->nama_produk }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.products.stock-adjust') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id_produk" value="{{ $p->id_produk }}">
                                            <div class="modal-body text-start p-4">
                                                <div class="mb-3 p-3 bg-light rounded-3 border">
                                                    <small class="text-muted d-block">Sisa Stok Fisik Saat Ini:</small>
                                                    <h4 class="fw-bold text-dark mb-0">{{ floatval($p->stok) }} {{ $p->satuan }}</h4>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Jenis Perubahan Stok:</label>
                                                    <select name="jenis" class="form-select" required>
                                                        <option value="masuk">1. Stok Masuk (Penambahan dari supplier)</option>
                                                        <option value="koreksi">2. Koreksi Stok (Penyesuaian buah rusak / selisih hitung)</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Jumlah ({{ $p->satuan }}):</label>
                                                    <input type="number" step="0.1" name="jumlah" class="form-control" placeholder="Contoh: 20 (atau -3 jika koreksi susut)" required>
                                                    <small class="text-muted">Untuk koreksi pengurangan buah rusak/busuk, gunakan tanda minus (-).</small>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Keterangan / Alasan:</label>
                                                    <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Kiriman pasokan baru, Sortir buah busuk, dll.">
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4 text-white fw-bold shadow-sm">Simpan Riwayat Stok</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-5 text-center text-muted">
                            Belum ada buah yang terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

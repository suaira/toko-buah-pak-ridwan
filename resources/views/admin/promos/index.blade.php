@extends('layouts.admin')

@section('title', 'Promo & Diskon - Panel Pemilik')
@section('page_title', 'Kelola Promo & Diskon Buatan Owner')

@section('content')
<div class="row g-4">
    <!-- Form Buat Promo Baru -->
    <div class="col-lg-4">
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-plus-circle text-primary me-2"></i>Buat Promo Baru</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.promos.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Promo <sup>*</sup></label>
                        <input type="text" name="nama_promo" class="form-control" placeholder="Contoh: Diskon Beli 3 kg Apel" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Buah yang Diberi Diskon</label>
                        <select name="id_produk" class="form-select">
                            <option value="">-- Berlaku untuk Semua Buah (Global) --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id_produk }}">{{ $p->nama_produk }} ({{ $p->satuan }})</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Kosongkan jika berlaku untuk semua jenis buah di toko.</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Persen Diskon (%) <sup>*</sup></label>
                            <input type="number" step="0.1" name="persen_diskon" class="form-control" placeholder="10.0" min="0.1" max="100" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Min. Jumlah Beli <sup>*</sup></label>
                            <input type="number" step="0.1" name="min_jumlah" class="form-control" placeholder="3.0" min="0" value="0" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tanggal Mulai <sup>*</sup></label>
                            <input type="date" name="tanggal_mulai" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tanggal Selesai <sup>*</sup></label>
                            <input type="date" name="tanggal_selesai" class="form-control" value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                        </div>
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" name="aktif" value="1" class="form-check-input" id="checkPromoAktif" checked>
                        <label class="form-check-label fw-bold" for="checkPromoAktif">Aktifkan promo ini sekarang</label>
                    </div>

                    <button type="submit" class="btn btn-primary rounded-pill w-100 py-3 fw-bold text-white shadow-sm">
                        <i class="fas fa-save me-1"></i> Simpan & Pasang Promo
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Promo -->
    <div class="col-lg-8">
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-tags text-primary me-2"></i>Daftar Promo Diskon Aktif & Riwayat</h5>
                <span class="badge bg-light text-muted border">Total: {{ $promos->total() }} Promo</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 text-center">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-start ps-4">Nama Promo</th>
                                <th>Target Buah</th>
                                <th>Diskon</th>
                                <th>Min. Beli</th>
                                <th>Periode Promo</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($promos as $promo)
                            <tr>
                                <td class="text-start ps-4 fw-bold text-dark">
                                    {{ $promo->nama_promo }}
                                </td>
                                <td>
                                    @if($promo->produk)
                                        <span class="badge bg-light text-dark border">{{ $promo->produk->nama_produk }}</span>
                                    @else
                                        <span class="badge bg-info text-dark">Semua Buah</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-danger fs-6 px-2 py-1">{{ floatval($promo->persen_diskon) }}%</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark">{{ floatval($promo->min_jumlah) }} {{ $promo->produk ? $promo->produk->satuan : 'item' }}</span>
                                </td>
                                <td class="text-muted small">
                                    {{ $promo->tanggal_mulai->format('d/m/y') }} s/d {{ $promo->tanggal_selesai->format('d/m/y') }}
                                </td>
                                <td>
                                    @if($promo->aktif && $promo->tanggal_selesai >= now()->toDateString())
                                        <span class="badge bg-success rounded-pill px-3 py-1">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill px-3 py-1">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <form action="{{ route('admin.promos.destroy', $promo->id_promo) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus promo ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Hapus Promo">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-4 text-muted">Belum ada promo diskon yang dibuat.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 p-3 d-flex justify-content-center">
                    {{ $promos->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

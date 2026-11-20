@extends('layouts.kasir')

@section('title', 'Pantau Stok & Promo - Panel Kasir')
@section('page_title', 'Monitoring Stok Buah & Promo Aktif')

@section('content')
<div class="row g-4">
    <!-- Kolom Kiri: Tabel Sisa Stok Buah -->
    <div class="col-lg-8">
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-boxes text-primary me-2"></i>Sisa Stok Buah di Toko</h5>
                <span class="badge bg-light text-muted border">Total: {{ $products->count() }} Jenis Buah</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Nama Buah</th>
                                <th>Kategori</th>
                                <th class="text-end">Harga Jual</th>
                                <th class="text-center">Sisa Stok</th>
                                <th class="text-end pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $p)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        @php
                                            $imgUrl = asset('fruitables/img/' . ($p->gambar ?: 'fruite-item-5.jpg'));
                                            if (file_exists(public_path('storage/products/' . $p->gambar))) {
                                                $imgUrl = asset('storage/products/' . $p->gambar);
                                            }
                                        @endphp
                                        <img src="{{ $imgUrl }}" class="rounded me-3 border" style="width: 45px; height: 45px; object-fit: cover;" alt="">
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark">{{ $p->nama_produk }}</h6>
                                            <small class="text-muted">ID: #{{ $p->id_produk }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $p->kategori->nama_kategori ?? '-' }}</span>
                                </td>
                                <td class="text-end fw-bold">Rp {{ number_format($p->harga, 0, ',', '.') }} <small class="text-muted">/{{ $p->satuan }}</small></td>
                                <td class="text-center">
                                    <h6 class="mb-0 fw-bold {{ $p->stok <= 10 ? 'text-danger' : 'text-success' }}">
                                        {{ floatval($p->stok) }} {{ $p->satuan }}
                                    </h6>
                                </td>
                                <td class="text-end pe-4">
                                    @if($p->stok <= 0)
                                        <span class="badge bg-danger rounded-pill px-3 py-1">HABIS</span>
                                    @elseif($p->stok <= 10)
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1">MENIPIS</span>
                                    @else
                                        <span class="badge bg-success rounded-pill px-3 py-1">TERSEDIA</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Promo yang Sedang Berjalan -->
    <div class="col-lg-4">
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-tags text-warning me-2"></i>Promo Aktif (Untuk Pembeli)</h5>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small mb-3">Kasir dapat menginformasikan promo berikut kepada pelanggan toko agar menambah jumlah belanjanya:</p>

                @forelse($activePromos as $promo)
                <div class="p-3 bg-light rounded-4 border mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="badge bg-danger fs-6 px-2 py-1">Diskon {{ floatval($promo->persen_diskon) }}%</span>
                        <small class="text-muted"><i class="fas fa-calendar-alt me-1"></i> s/d {{ $promo->tanggal_selesai->format('d M Y') }}</small>
                    </div>
                    <h6 class="fw-bold text-dark mt-2 mb-1">{{ $promo->nama_promo }}</h6>
                    <div class="text-muted small">
                        Buah Target: <strong>{{ $promo->produk->nama_produk ?? 'Semua Buah di Toko' }}</strong>
                    </div>
                    <div class="text-muted small">
                        Syarat Min: <strong>{{ floatval($promo->min_jumlah) }} {{ $promo->produk ? $promo->produk->satuan : 'item' }}</strong>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-tag fa-2x mb-2 text-secondary opacity-50"></i>
                    <div>Tidak ada promo aktif saat ini.</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

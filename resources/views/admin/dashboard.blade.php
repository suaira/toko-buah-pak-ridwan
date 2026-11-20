@extends('layouts.admin')

@section('title', 'Dashboard Owner - Toko Buah Pak Ridwan')
@section('page_title', 'Ringkasan Bisnis Toko Buah')

@section('content')
<!-- Stat Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-stat bg-white p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-bold text-uppercase">Total Omzet Keseluruhan</span>
                    <h3 class="fw-bold text-primary my-1">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
                    <small class="text-success fw-bold"><i class="fas fa-check-double me-1"></i> Transaksi Berhasil</small>
                </div>
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary">
                    <i class="fas fa-coins fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-stat bg-white p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-bold text-uppercase">Omzet Bulan Ini</span>
                    <h3 class="fw-bold text-success my-1">Rp {{ number_format($monthSales, 0, ',', '.') }}</h3>
                    <small class="text-muted">{{ now()->translatedFormat('F Y') }}</small>
                </div>
                <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success">
                    <i class="fas fa-chart-line fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-stat bg-white p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-bold text-uppercase">Total Transaksi</span>
                    <h2 class="fw-bold text-dark my-1">{{ $totalOrders }}</h2>
                    <small class="text-muted">Online & Offline Toko</small>
                </div>
                <div class="rounded-circle bg-warning bg-opacity-10 p-3 text-warning">
                    <i class="fas fa-shopping-cart fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-stat bg-white p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-bold text-uppercase">Total Customer</span>
                    <h2 class="fw-bold text-info my-1">{{ $totalCustomers }}</h2>
                    <small class="text-muted">{{ $totalProducts }} Jenis Buah Terdaftar</small>
                </div>
                <div class="rounded-circle bg-info bg-opacity-10 p-3 text-info">
                    <i class="fas fa-users fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Buah Terlaris (Top 5 Best Seller) -->
    <div class="col-lg-6">
        <div class="card border-0 rounded-4 shadow-sm h-100">
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-medal text-warning me-2"></i>5 Buah Terlaris (Best Seller)</h5>
                <a href="{{ route('admin.reports.sales') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Laporan Lengkap</a>
            </div>
            <div class="card-body p-4">
                <ul class="list-unstyled mb-0">
                    @forelse($bestSellers as $idx => $bs)
                    <li class="d-flex justify-content-between align-items-center py-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <span class="badge {{ $idx == 0 ? 'bg-warning text-dark' : 'bg-light text-dark border' }} rounded-circle p-2 me-3 fs-6" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                #{{ $idx + 1 }}
                            </span>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">{{ $bs->produk->nama_produk ?? 'Buah' }}</h6>
                                <small class="text-muted">Terjual: <strong>{{ floatval($bs->total_terjual) }} {{ $bs->produk->satuan ?? 'kg' }}</strong></small>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="fw-bold text-primary">Rp {{ number_format($bs->total_omzet, 0, ',', '.') }}</span>
                        </div>
                    </li>
                    @empty
                    <li class="text-center py-4 text-muted">Belum ada data penjualan selesai.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Peringatan Stok Menipis -->
    <div class="col-lg-6">
        <div class="card border-0 rounded-4 shadow-sm h-100">
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Peringatan Stok Menipis (<= 15)</h5>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">Input Stok Masuk</a>
            </div>
            <div class="card-body p-4">
                <ul class="list-unstyled mb-0">
                    @forelse($lowStocks as $ls)
                    <li class="d-flex justify-content-between align-items-center py-3 border-bottom">
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">{{ $ls->nama_produk }}</h6>
                            <small class="text-muted">Kategori: {{ $ls->kategori->nama_kategori ?? '-' }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge {{ $ls->stok <= 0 ? 'bg-danger' : 'bg-warning text-dark' }} fs-6 px-3 py-1">
                                Sisa {{ floatval($ls->stok) }} {{ $ls->satuan }}
                            </span>
                        </div>
                    </li>
                    @empty
                    <li class="text-center py-4 text-success">
                        <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                        <div>Semua stok buah saat ini aman dan mencukupi!</div>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Transaksi Terkini -->
<div class="card border-0 rounded-4 shadow-sm">
    <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-receipt text-primary me-2"></i>Transaksi Pesanan Terbaru</h5>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Lihat Semua Transaksi</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0 text-center">
                <thead class="bg-light">
                    <tr>
                        <th class="text-start ps-4">Kode Transaksi</th>
                        <th class="text-start">Pelanggan</th>
                        <th>Sumber</th>
                        <th>Metode Ambil</th>
                        <th>Metode Bayar</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                    <tr>
                        <td class="text-start ps-4 fw-bold">
                            <a href="{{ route('kasir.orders.detail', $order->id_pesanan) }}" class="text-dark">{{ $order->kode_pesanan }}</a>
                        </td>
                        <td class="text-start">{{ $order->pelanggan->nama_pelanggan ?? ($order->catatan ?: 'Pembeli Toko') }}</td>
                        <td><span class="badge {{ $order->sumber == 'online' ? 'bg-primary' : 'bg-secondary' }}">{{ strtoupper($order->sumber) }}</span></td>
                        <td><span class="badge bg-light text-dark border">{{ strtoupper($order->metode_ambil) }}</span></td>
                        <td><span class="badge bg-light text-dark border">{{ strtoupper(optional($order->pembayaran)->metode ?? '-') }}</span></td>
                        <td class="fw-bold text-dark">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</td>
                        <td><span class="{{ $order->status_badge }} rounded-pill px-2 py-1 small">{{ $order->status_label }}</span></td>
                        <td class="text-end pe-4 text-muted small">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

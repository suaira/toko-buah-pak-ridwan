@extends('layouts.kasir')

@section('title', 'Dashboard Kasir - Toko Buah Pak Ridwan')
@section('page_title', 'Dashboard Petugas Kasir')

@section('content')
<!-- Stat Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-stat bg-white p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-bold text-uppercase">Pesanan Menunggu</span>
                    <h2 class="fw-bold text-danger my-1">{{ $newOrdersCount }}</h2>
                    <small class="text-danger fw-bold"><i class="fas fa-bell me-1"></i> Perlu Segera Dikonfirmasi</small>
                </div>
                <div class="rounded-circle bg-danger bg-opacity-10 p-3 text-danger">
                    <i class="fas fa-inbox fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-stat bg-white p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-bold text-uppercase">Sedang Diproses</span>
                    <h2 class="fw-bold text-primary my-1">{{ $processingCount }}</h2>
                    <small class="text-muted">Dalam penyiapan & sortir</small>
                </div>
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary">
                    <i class="fas fa-box-open fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-stat bg-white p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-bold text-uppercase">Siap / Diantar</span>
                    <h2 class="fw-bold text-info my-1">{{ $readyOrShippedCount }}</h2>
                    <small class="text-muted">Siap diambil / diantar kurir</small>
                </div>
                <div class="rounded-circle bg-info bg-opacity-10 p-3 text-info">
                    <i class="fas fa-shipping-fast fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-stat bg-white p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-bold text-uppercase">Penjualan Hari Ini</span>
                    <h3 class="fw-bold text-success my-1">Rp {{ number_format($todaySales, 0, ',', '.') }}</h3>
                    <small class="text-success"><i class="fas fa-check-circle me-1"></i> Pesanan Selesai</small>
                </div>
                <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success">
                    <i class="fas fa-wallet fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alert Notif Pending Pembayaran -->
@if($pendingPaymentsCount > 0)
<div class="alert alert-warning border-warning rounded-4 shadow-sm p-4 mb-4 d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center">
        <i class="fas fa-exclamation-triangle fa-2x text-warning me-3"></i>
        <div>
            <h5 class="mb-0 fw-bold text-dark">Ada {{ $pendingPaymentsCount }} Bukti Transfer Baru Menunggu Validasi!</h5>
            <small class="text-muted">Pelanggan telah mengunggah bukti transfer, silakan periksa mutasi rekening.</small>
        </div>
    </div>
    <a href="{{ route('kasir.payments') }}" class="btn btn-warning rounded-pill px-4 fw-bold">Periksa Sekarang</a>
</div>
@endif

<!-- Quick Actions & Recent Orders -->
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-list-alt text-primary me-2"></i>Pesanan Masuk Terbaru</h5>
                <a href="{{ route('kasir.orders') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 text-center">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-start ps-4">Kode</th>
                                <th class="text-start">Pelanggan</th>
                                <th>Sumber</th>
                                <th>Metode</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td class="text-start ps-4 fw-bold text-dark">
                                    <a href="{{ route('kasir.orders.detail', $order->id_pesanan) }}">{{ $order->kode_pesanan }}</a>
                                </td>
                                <td class="text-start">
                                    <div class="fw-bold text-dark">{{ $order->pelanggan->nama_pelanggan ?? 'Beli di Toko' }}</div>
                                    <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $order->sumber == 'online' ? 'bg-primary' : 'bg-secondary' }}">{{ strtoupper($order->sumber) }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $order->metode_ambil == 'antar' ? 'bg-info text-dark' : 'bg-light text-dark border' }}">{{ strtoupper($order->metode_ambil) }}</span>
                                </td>
                                <td class="fw-bold text-dark">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</td>
                                <td>
                                    <span class="{{ $order->status_badge }} rounded-pill px-2 py-1 small">{{ $order->status_label }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('kasir.orders.detail', $order->id_pesanan) }}" class="btn btn-sm btn-outline-primary rounded-circle" title="Buka Detail">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-4 text-muted">Belum ada transaksi pesanan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Tool Buttons -->
    <div class="col-lg-4">
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-bolt text-warning me-2"></i>Aksi Cepat Kasir</h5>
            </div>
            <div class="card-body p-4">
                <div class="d-grid gap-3">
                    <a href="{{ route('kasir.pos') }}" class="btn btn-success p-3 rounded-4 text-start d-flex align-items-center shadow-sm">
                        <i class="fas fa-cash-register fa-2x me-3 text-white"></i>
                        <div>
                            <div class="fw-bold text-white fs-6">Kasir POS Toko</div>
                            <small class="text-white-50">Catat transaksi pembeli langsung di toko</small>
                        </div>
                    </a>

                    <a href="{{ route('kasir.orders') }}" class="btn btn-primary p-3 rounded-4 text-start d-flex align-items-center shadow-sm">
                        <i class="fas fa-clipboard-check fa-2x me-3 text-white"></i>
                        <div>
                            <div class="fw-bold text-white fs-6">Konfirmasi Pesanan Online</div>
                            <small class="text-white-50">Ubah status pesanan masuk hingga selesai</small>
                        </div>
                    </a>

                    <a href="{{ route('kasir.payments') }}" class="btn btn-warning p-3 rounded-4 text-start d-flex align-items-center shadow-sm text-dark">
                        <i class="fas fa-receipt fa-2x me-3 text-dark"></i>
                        <div>
                            <div class="fw-bold text-dark fs-6">Validasi Pembayaran</div>
                            <small class="text-dark opacity-75">Cek bukti transfer dari pembeli online</small>
                        </div>
                    </a>

                    <a href="{{ route('kasir.daily-report') }}" class="btn btn-light border p-3 rounded-4 text-start d-flex align-items-center">
                        <i class="fas fa-file-invoice-dollar fa-2x me-3 text-secondary"></i>
                        <div>
                            <div class="fw-bold text-dark fs-6">Laporan Tutup Kasir</div>
                            <small class="text-muted">Rekap pendapatan harian & cetak</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.kasir')

@section('title', 'Rekap Harian Tutup Kasir - Panel Kasir')
@section('page_title', 'Laporan Rekap Tutup Kasir Harian')

@section('content')
<!-- Date Filter Card -->
<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body p-4">
        <form action="{{ route('kasir.daily-report') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-md-4">
                <label class="form-label fw-bold text-dark mb-1">Pilih Tanggal Laporan:</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fas fa-calendar-alt text-primary"></i></span>
                    <input type="date" name="date" class="form-control fw-bold" value="{{ $date }}" onchange="this.form.submit()">
                </div>
            </div>
            <div class="col-md-4 mt-md-4">
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold text-white shadow-sm">
                    <i class="fas fa-filter me-1"></i> Tampilkan Rekap
                </button>
                <button type="button" onclick="window.print()" class="btn btn-outline-dark rounded-pill px-3 ms-2">
                    <i class="fas fa-print me-1"></i> Cetak Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Ringkasan Statistik Tutup Kasir -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 rounded-4 shadow-sm p-4 bg-primary text-white">
            <span class="small opacity-75 fw-bold text-uppercase">Total Omzet Selesai</span>
            <h3 class="fw-bold my-2 text-white">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
            <small class="opacity-75">Dari {{ $transaksiSelesai }} transaksi selesai</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 rounded-4 shadow-sm p-4 bg-white border-start border-success border-4">
            <span class="text-muted small fw-bold text-uppercase">Uang Tunai (Cash)</span>
            <h4 class="fw-bold text-success my-2">Rp {{ number_format($tunaiTotal, 0, ',', '.') }}</h4>
            <small class="text-muted">Fisik kas laci toko</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 rounded-4 shadow-sm p-4 bg-white border-start border-info border-4">
            <span class="text-muted small fw-bold text-uppercase">Transfer Bank / QRIS</span>
            <h4 class="fw-bold text-info my-2">Rp {{ number_format($transferTotal, 0, ',', '.') }}</h4>
            <small class="text-muted">Masuk ke rekening bank</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 rounded-4 shadow-sm p-4 bg-white border-start border-warning border-4">
            <span class="text-muted small fw-bold text-uppercase">COD (Kurir)</span>
            <h4 class="fw-bold text-warning my-2">Rp {{ number_format($codTotal, 0, ',', '.') }}</h4>
            <small class="text-muted">Setoran tunai kurir</small>
        </div>
    </div>
</div>

<!-- Tabel Transaksi Hari Ini -->
<div class="card border-0 rounded-4 shadow-sm">
    <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="fas fa-list text-primary me-2"></i>Rincian Transaksi Tanggal {{ date('d F Y', strtotime($date)) }}
        </h5>
        <span class="badge bg-light text-muted border">Total: {{ $totalTransaksi }} Transaksi</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle text-center mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="text-start ps-4">No & Jam</th>
                        <th class="text-start">Kode Pesanan</th>
                        <th class="text-start">Pelanggan</th>
                        <th>Sumber</th>
                        <th>Metode Bayar</th>
                        <th>Subtotal Buah</th>
                        <th>Diskon</th>
                        <th>Ongkir</th>
                        <th class="text-end">Total Bayar</th>
                        <th class="text-end pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $idx => $order)
                    <tr>
                        <td class="text-start ps-4 text-muted small">
                            {{ $idx + 1 }}. {{ $order->created_at->format('H:i') }} WIB
                        </td>
                        <td class="text-start fw-bold">
                            <a href="{{ route('kasir.orders.detail', $order->id_pesanan) }}" class="text-dark">
                                {{ $order->kode_pesanan }}
                            </a>
                        </td>
                        <td class="text-start">
                            <div class="fw-bold text-dark">{{ $order->pelanggan->nama_pelanggan ?? ($order->catatan ?: 'Pembeli Toko') }}</div>
                        </td>
                        <td>
                            <span class="badge {{ $order->sumber == 'online' ? 'bg-primary' : 'bg-secondary' }}">
                                {{ strtoupper($order->sumber) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border text-uppercase">
                                {{ optional($order->pembayaran)->metode ?? '-' }}
                            </span>
                        </td>
                        <td>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                        <td class="text-danger">- Rp {{ number_format($order->total_diskon, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($order->ongkir, 0, ',', '.') }}</td>
                        <td class="text-end fw-bold text-dark">
                            Rp {{ number_format($order->total_bayar, 0, ',', '.') }}
                        </td>
                        <td class="text-end pe-4">
                            <span class="{{ $order->status_badge }} rounded-pill px-2 py-1 small">
                                {{ $order->status_label }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="py-5 text-center text-muted">
                            Tidak ada transaksi pada tanggal ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

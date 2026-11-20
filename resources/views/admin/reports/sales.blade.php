@extends('layouts.admin')

@section('title', 'Laporan Penjualan - Panel Pemilik')
@section('page_title', 'Laporan Penjualan Lengkap & Buah Terlaris')

@section('content')
<!-- Filter Period Buttons & Custom Date -->
<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="btn-group" role="group">
                <a href="{{ route('admin.reports.sales', ['period' => 'day']) }}" class="btn {{ $period == 'day' ? 'btn-primary text-white fw-bold' : 'btn-outline-primary' }} rounded-start-pill px-4">
                    <i class="fas fa-calendar-day me-1"></i> Hari Ini
                </a>
                <a href="{{ route('admin.reports.sales', ['period' => 'week']) }}" class="btn {{ $period == 'week' ? 'btn-primary text-white fw-bold' : 'btn-outline-primary' }} px-4">
                    <i class="fas fa-calendar-week me-1"></i> Minggu Ini
                </a>
                <a href="{{ route('admin.reports.sales', ['period' => 'month']) }}" class="btn {{ $period == 'month' ? 'btn-primary text-white fw-bold' : 'btn-outline-primary' }} rounded-end-pill px-4">
                    <i class="fas fa-calendar-alt me-1"></i> Bulan Ini
                </a>
            </div>

            <form action="{{ route('admin.reports.sales') }}" method="GET" class="d-flex align-items-center gap-2">
                <input type="hidden" name="period" value="custom">
                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}" required>
                <span class="text-muted">s/d</span>
                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}" required>
                <button type="submit" class="btn btn-sm btn-secondary rounded-pill px-3">Filter</button>
            </form>

            <button onclick="window.print()" class="btn btn-outline-dark rounded-pill px-4">
                <i class="fas fa-print me-1"></i> Cetak Laporan
            </button>
        </div>
    </div>
</div>

<!-- Periode Label & Metric Cards -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold text-dark mb-0">Laporan Penjualan: <span class="text-primary">{{ $label }}</span></h5>
    <span class="badge bg-success px-3 py-2 fs-6 rounded-pill">Khusus Pesanan Berstatus Selesai</span>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 rounded-4 shadow-sm p-4 bg-primary text-white">
            <span class="small opacity-75 fw-bold text-uppercase">Total Omzet Penjualan</span>
            <h3 class="fw-bold my-2 text-white">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</h3>
            <small class="opacity-75">Bersih setelah diskon & ongkir</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 rounded-4 shadow-sm p-4 bg-white border-start border-success border-4">
            <span class="text-muted small fw-bold text-uppercase">Jumlah Transaksi Selesai</span>
            <h3 class="fw-bold text-success my-2">{{ $totalTransaksi }}</h3>
            <small class="text-muted">Pesanan tuntas diterima</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 rounded-4 shadow-sm p-4 bg-white border-start border-danger border-4">
            <span class="text-muted small fw-bold text-uppercase">Total Diskon Promo</span>
            <h3 class="fw-bold text-danger my-2">Rp {{ number_format($totalDiskon, 0, ',', '.') }}</h3>
            <small class="text-muted">Diberikan ke pelanggan</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 rounded-4 shadow-sm p-4 bg-white border-start border-info border-4">
            <span class="text-muted small fw-bold text-uppercase">Pendapatan Ongkir</span>
            <h3 class="fw-bold text-info my-2">Rp {{ number_format($totalOngkir, 0, ',', '.') }}</h3>
            <small class="text-muted">Jasa kurir pengantaran</small>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Tabel Buah Terlaris -->
    <div class="col-lg-5">
        <div class="card border-0 rounded-4 shadow-sm h-100">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-medal text-warning me-2"></i>Peringkat Buah Terlaris</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 text-center">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-start ps-4">Peringkat & Buah</th>
                                <th>Total Terjual</th>
                                <th class="text-end pe-4">Total Omzet</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bestSellers as $idx => $bs)
                            <tr>
                                <td class="text-start ps-4">
                                    <div class="d-flex align-items-center">
                                        <span class="badge {{ $idx == 0 ? 'bg-warning text-dark' : ($idx == 1 ? 'bg-secondary text-white' : ($idx == 2 ? 'bg-danger text-white' : 'bg-light text-dark border')) }} me-2" style="width: 25px;">
                                            {{ $idx + 1 }}
                                        </span>
                                        <div class="fw-bold text-dark">{{ $bs->produk->nama_produk ?? 'Buah' }}</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-success rounded-pill px-3 py-1 fs-6">
                                        {{ floatval($bs->total_kg) }} {{ $bs->produk->satuan ?? 'kg' }}
                                    </span>
                                </td>
                                <td class="text-end pe-4 fw-bold text-primary">
                                    Rp {{ number_format($bs->total_omzet_produk, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-4 text-muted">Belum ada buah terjual di periode ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Rincian Pesanan Selesai -->
    <div class="col-lg-7">
        <div class="card border-0 rounded-4 shadow-sm h-100">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-clipboard-check text-primary me-2"></i>Daftar Transaksi Selesai</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 480px; overflow-y: auto;">
                    <table class="table align-middle mb-0 text-center">
                        <thead class="bg-light sticky-top">
                            <tr>
                                <th class="text-start ps-4">Kode & Tanggal</th>
                                <th class="text-start">Pelanggan</th>
                                <th>Sumber</th>
                                <th>Metode Bayar</th>
                                <th class="text-end pe-4">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $o)
                            <tr>
                                <td class="text-start ps-4">
                                    <a href="{{ route('kasir.orders.detail', $o->id_pesanan) }}" class="fw-bold text-dark">
                                        {{ $o->kode_pesanan }}
                                    </a>
                                    <div class="text-muted small">{{ $o->created_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td class="text-start">
                                    <div class="fw-bold text-dark">{{ $o->pelanggan->nama_pelanggan ?? ($o->catatan ?: 'Pembeli Toko') }}</div>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ strtoupper($o->sumber) }}</span></td>
                                <td><span class="badge bg-light text-dark border">{{ strtoupper(optional($o->pembayaran)->metode ?? '-') }}</span></td>
                                <td class="text-end pe-4 fw-bold text-primary">
                                    Rp {{ number_format($o->total_bayar, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-5 text-center text-muted">
                                    Tidak ada transaksi selesai pada periode ini.
                                </td>
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

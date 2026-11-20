@extends('layouts.admin')

@section('title', 'Semua Transaksi - Panel Pemilik')
@section('page_title', 'Daftar Seluruh Transaksi & Pesanan')

@section('content')
<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body p-4">
        <!-- Filter Form -->
        <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3 align-items-center mb-4">
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Dari Tanggal:</label>
                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Sampai Tanggal:</label>
                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted">Status Pesanan:</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="dikonfirmasi" {{ request('status') == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="siap" {{ request('status') == 'siap' ? 'selected' : '' }}>Siap Ambil</option>
                    <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-muted">Sumber Transaksi:</label>
                <select name="sumber" class="form-select form-select-sm">
                    <option value="">Semua Sumber</option>
                    <option value="online" {{ request('sumber') == 'online' ? 'selected' : '' }}>Online</option>
                    <option value="toko" {{ request('sumber') == 'toko' ? 'selected' : '' }}>Toko</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1 align-self-end">
                <button type="submit" class="btn btn-primary btn-sm rounded-pill w-100 fw-bold text-white">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle text-center mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="text-start ps-4">Kode Transaksi</th>
                        <th class="text-start">Pelanggan</th>
                        <th>Sumber</th>
                        <th>Metode Ambil</th>
                        <th>Subtotal</th>
                        <th>Diskon</th>
                        <th>Ongkir</th>
                        <th>Total Bayar</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="text-start ps-4">
                            <span class="fw-bold text-dark fs-6">{{ $order->kode_pesanan }}</span>
                            <div class="text-muted small">{{ $order->created_at->format('d/m/Y H:i') }} WIB</div>
                        </td>
                        <td class="text-start">
                            <div class="fw-bold text-dark">{{ $order->pelanggan->nama_pelanggan ?? ($order->catatan ?: 'Pembeli Toko') }}</div>
                            <small class="text-muted">{{ $order->pelanggan->no_hp ?? '-' }}</small>
                        </td>
                        <td>
                            <span class="badge {{ $order->sumber == 'online' ? 'bg-primary' : 'bg-secondary' }}">
                                {{ strtoupper($order->sumber) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $order->metode_ambil == 'antar' ? 'bg-info text-dark' : 'bg-light text-dark border' }}">
                                {{ strtoupper($order->metode_ambil) }}
                            </span>
                        </td>
                        <td>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                        <td class="text-danger">- Rp {{ number_format($order->total_diskon, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($order->ongkir, 0, ',', '.') }}</td>
                        <td class="fw-bold text-dark">
                            Rp {{ number_format($order->total_bayar, 0, ',', '.') }}
                        </td>
                        <td>
                            <span class="badge {{ optional($order->pembayaran)->status == 'lunas' ? 'bg-success' : (optional($order->pembayaran)->status == 'ditolak' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                {{ strtoupper(optional($order->pembayaran)->status ?? 'BELUM') }} ({{ strtoupper(optional($order->pembayaran)->metode ?? '-') }})
                            </span>
                        </td>
                        <td>
                            <span class="{{ $order->status_badge }} rounded-pill px-3 py-1 small">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('kasir.orders.detail', $order->id_pesanan) }}" class="btn btn-sm btn-outline-primary rounded-circle" title="Detail Lengkap">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="py-5 text-center text-muted">
                            Tidak ada transaksi yang cocok dengan filter yang dipilih.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

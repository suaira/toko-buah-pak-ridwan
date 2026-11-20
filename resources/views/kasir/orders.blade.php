@extends('layouts.kasir')

@section('title', 'Kelola Pesanan - Panel Kasir')
@section('page_title', 'Pengelolaan Pesanan Masuk & Online')

@section('content')
<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body p-4">
        <!-- Filter Tabs / Form -->
        <form action="{{ route('kasir.orders') }}" method="GET" class="row g-3 align-items-center mb-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Cari kode pesanan / nama pembeli...">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Status Pesanan --</option>
                    <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                    <option value="dikonfirmasi" {{ request('status') == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                    <option value="siap" {{ request('status') == 'siap' ? 'selected' : '' }}>Siap Diambil di Toko</option>
                    <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Sedang Diantar Kurir</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="sumber" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Sumber --</option>
                    <option value="online" {{ request('sumber') == 'online' ? 'selected' : '' }}>Pesanan Online</option>
                    <option value="toko" {{ request('sumber') == 'toko' ? 'selected' : '' }}>Transaksi Langsung di Toko</option>
                </select>
            </div>
            <div class="col-md-2">
                <a href="{{ route('kasir.orders') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle text-center mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="text-start ps-3">Kode & Waktu</th>
                        <th class="text-start">Pelanggan</th>
                        <th>Sumber</th>
                        <th>Metode</th>
                        <th>Total Bayar</th>
                        <th>Status Pembayaran</th>
                        <th>Status Pesanan</th>
                        <th class="text-end pe-3">Aksi Cepat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr class="{{ $order->status === 'menunggu' ? 'table-warning bg-opacity-25' : '' }}">
                        <td class="text-start ps-3">
                            <a href="{{ route('kasir.orders.detail', $order->id_pesanan) }}" class="fw-bold text-dark fs-6">
                                {{ $order->kode_pesanan }}
                            </a>
                            <div class="text-muted small">{{ $order->created_at->format('d/m/Y H:i') }} WIB</div>
                        </td>
                        <td class="text-start">
                            <div class="fw-bold text-dark">{{ $order->pelanggan->nama_pelanggan ?? ($order->catatan ? $order->catatan : 'Pembeli Toko') }}</div>
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
                        <td class="fw-bold text-dark">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ optional($order->pembayaran)->status == 'lunas' ? 'bg-success' : (optional($order->pembayaran)->status == 'ditolak' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                {{ strtoupper(optional($order->pembayaran)->status ?? 'BELUM') }} ({{ strtoupper(optional($order->pembayaran)->metode ?? '-') }})
                            </span>
                        </td>
                        <td>
                            <span class="{{ $order->status_badge }} rounded-pill px-3 py-1">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="text-end pe-3">
                            <div class="dropdown d-inline-block">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle rounded-pill" type="button" data-bs-toggle="dropdown">
                                    Update Status
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                    <li><h6 class="dropdown-header">Ubah Status Alur</h6></li>
                                    <li>
                                        <form action="{{ route('kasir.orders.status', $order->id_pesanan) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="dikonfirmasi">
                                            <button type="submit" class="dropdown-item"><i class="fas fa-check text-info me-2"></i> Konfirmasi</button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('kasir.orders.status', $order->id_pesanan) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="diproses">
                                            <button type="submit" class="dropdown-item"><i class="fas fa-box text-primary me-2"></i> Sedang Diproses</button>
                                        </form>
                                    </li>
                                    @if($order->metode_ambil == 'ambil')
                                    <li>
                                        <form action="{{ route('kasir.orders.status', $order->id_pesanan) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="siap">
                                            <button type="submit" class="dropdown-item"><i class="fas fa-store text-secondary me-2"></i> Siap Diambil di Toko</button>
                                        </form>
                                    </li>
                                    @else
                                    <li>
                                        <form action="{{ route('kasir.orders.status', $order->id_pesanan) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="dikirim">
                                            <button type="submit" class="dropdown-item"><i class="fas fa-shipping-fast text-info me-2"></i> Sedang Diantar Kurir</button>
                                        </form>
                                    </li>
                                    @endif
                                    <li>
                                        <form action="{{ route('kasir.orders.status', $order->id_pesanan) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="selesai">
                                            <button type="submit" class="dropdown-item text-success fw-bold"><i class="fas fa-check-double me-2"></i> Selesai (Lunas)</button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('kasir.orders.status', $order->id_pesanan) }}" method="POST" onsubmit="return confirm('Tolak pesanan ini dan kembalikan stok?')">
                                            @csrf
                                            <input type="hidden" name="status" value="ditolak">
                                            <button type="submit" class="dropdown-item text-danger"><i class="fas fa-ban me-2"></i> Tolak Pesanan</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                            <a href="{{ route('kasir.orders.detail', $order->id_pesanan) }}" class="btn btn-sm btn-light border rounded-circle ms-1" title="Detail Lengkap">
                                <i class="fas fa-eye text-muted"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-5 text-center text-muted">
                            <i class="fas fa-inbox fa-3x mb-2 text-secondary opacity-50"></i>
                            <div>Tidak ada data pesanan yang sesuai filter.</div>
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

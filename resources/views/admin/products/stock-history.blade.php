@extends('layouts.admin')

@section('title', 'Jejak Riwayat Stok - Panel Pemilik')
@section('page_title', 'Jejak Audit & Riwayat Perubahan Stok')

@section('content')
<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body p-4">
        <!-- Filter Bar -->
        <form action="{{ route('admin.products.stock-history') }}" method="GET" class="row g-3 align-items-center mb-4">
            <div class="col-md-4">
                <select name="id_produk" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Buah --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id_produk }}" {{ request('id_produk') == $p->id_produk ? 'selected' : '' }}>
                            {{ $p->nama_produk }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="jenis" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Jenis Perubahan --</option>
                    <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>Stok Masuk (Pasokan)</option>
                    <option value="pesanan" {{ request('jenis') == 'pesanan' ? 'selected' : '' }}>Pengurangan Pesanan</option>
                    <option value="batal" {{ request('jenis') == 'batal' ? 'selected' : '' }}>Pengembalian Batal</option>
                    <option value="koreksi" {{ request('jenis') == 'koreksi' ? 'selected' : '' }}>Koreksi Manual</option>
                </select>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.products.stock-history') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
            <div class="col-md-3 text-end">
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary rounded-pill px-3">
                    <i class="fas fa-boxes me-1"></i> Kembali ke Produk
                </a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle text-center mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="text-start ps-4">Waktu & Tanggal</th>
                        <th class="text-start">Nama Produk Buah</th>
                        <th>Jenis Perubahan</th>
                        <th>Perubahan Jumlah</th>
                        <th class="text-start">Keterangan / Alasan</th>
                        <th class="text-end pe-4">Diubah Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $h)
                    <tr>
                        <td class="text-start ps-4 text-muted small">
                            {{ $h->created_at->format('d/m/Y H:i') }} WIB
                        </td>
                        <td class="text-start fw-bold text-dark">
                            {{ $h->produk->nama_produk ?? 'Buah (Dihapus)' }}
                        </td>
                        <td>
                            @php
                                $badgeClass = match($h->jenis) {
                                    'masuk' => 'bg-success',
                                    'pesanan' => 'bg-primary',
                                    'batal' => 'bg-info text-dark',
                                    'koreksi' => 'bg-warning text-dark',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} text-uppercase px-2 py-1">
                                {{ $h->jenis }}
                            </span>
                        </td>
                        <td>
                            @if($h->jumlah > 0)
                                <span class="fw-bold text-success fs-6">+{{ floatval($h->jumlah) }} {{ $h->produk->satuan ?? 'kg' }}</span>
                            @else
                                <span class="fw-bold text-danger fs-6">{{ floatval($h->jumlah) }} {{ $h->produk->satuan ?? 'kg' }}</span>
                            @endif
                        </td>
                        <td class="text-start text-muted small">
                            {{ $h->keterangan ?: '-' }}
                        </td>
                        <td class="text-end pe-4">
                            <span class="badge bg-light text-dark border">
                                <i class="fas fa-user-edit me-1 text-primary"></i> {{ $h->user->name ?? 'Sistem' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-5 text-center text-muted">
                            Belum ada riwayat pergerakan stok.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $history->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

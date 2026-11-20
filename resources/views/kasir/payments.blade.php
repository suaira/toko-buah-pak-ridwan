@extends('layouts.kasir')

@section('title', 'Validasi Pembayaran - Panel Kasir')
@section('page_title', 'Validasi Pembayaran Customer')

@section('content')
<div class="card border-0 rounded-4 shadow-sm">
    <div class="card-body p-4">
        <!-- Filter Bar -->
        <form action="{{ route('kasir.payments') }}" method="GET" class="row g-3 align-items-center mb-4">
            <div class="col-md-4">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Status Pembayaran --</option>
                    <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu Verifikasi Kasir</option>
                    <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas (Terverifikasi)</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak / Invalid</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="metode" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Metode --</option>
                    <option value="transfer" {{ request('metode') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                    <option value="cod" {{ request('metode') == 'cod' ? 'selected' : '' }}>COD (Bayar di Tempat)</option>
                    <option value="tunai" {{ request('metode') == 'tunai' ? 'selected' : '' }}>Tunai di Toko</option>
                </select>
            </div>
            <div class="col-md-2">
                <a href="{{ route('kasir.payments') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle text-center mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="text-start ps-3">Kode Pesanan</th>
                        <th class="text-start">Pelanggan</th>
                        <th>Metode</th>
                        <th>Total Tagihan</th>
                        <th>Bukti Transfer</th>
                        <th>Status</th>
                        <th>Pemeriksa</th>
                        <th class="text-end pe-3">Aksi Validasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $p)
                    <tr class="{{ $p->status === 'menunggu' && $p->bukti_transfer ? 'table-warning bg-opacity-25' : '' }}">
                        <td class="text-start ps-3 fw-bold">
                            <a href="{{ route('kasir.orders.detail', $p->id_pesanan) }}" class="text-dark">
                                {{ $p->pesanan->kode_pesanan ?? '-' }}
                            </a>
                            <div class="text-muted small">{{ $p->created_at->format('d/m/Y H:i') }}</div>
                        </td>
                        <td class="text-start">
                            <div class="fw-bold text-dark">{{ $p->pesanan->pelanggan->nama_pelanggan ?? 'Pembeli Toko' }}</div>
                            <small class="text-muted">{{ $p->pesanan->pelanggan->no_hp ?? '-' }}</small>
                        </td>
                        <td>
                            <span class="badge {{ $p->metode == 'transfer' ? 'bg-primary' : 'bg-success' }} text-uppercase">
                                {{ $p->metode }}
                            </span>
                        </td>
                        <td class="fw-bold text-dark fs-6">
                            Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}
                        </td>
                        <td>
                            @if($p->bukti_transfer)
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalBukti{{ $p->id_pembayaran }}">
                                    <i class="fas fa-image me-1"></i> Lihat Bukti
                                </button>

                                <!-- Modal Bukti Transfer -->
                                <div class="modal fade" id="modalBukti{{ $p->id_pembayaran }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 overflow-hidden border-0 shadow">
                                            <div class="modal-header bg-light">
                                                <h5 class="modal-title fw-bold">Bukti Transfer - {{ $p->pesanan->kode_pesanan ?? '' }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center p-4">
                                                <img src="{{ asset('storage/transfer_proofs/' . $p->bukti_transfer) }}" class="img-fluid rounded border shadow-sm mb-3" style="max-height: 400px;" alt="Bukti Transfer">
                                                <div class="fw-bold text-dark">Nominal Tagihan: Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</div>
                                            </div>
                                            <div class="modal-footer bg-light d-flex justify-content-between">
                                                <form action="{{ route('kasir.payments.status', $p->id_pembayaran) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="ditolak">
                                                    <button type="submit" class="btn btn-danger rounded-pill px-4" onclick="return confirm('Tolak bukti transfer ini?')">
                                                        <i class="fas fa-times me-1"></i> Tolak
                                                    </button>
                                                </form>
                                                <form action="{{ route('kasir.payments.status', $p->id_pembayaran) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="lunas">
                                                    <button type="submit" class="btn btn-success rounded-pill px-4 text-white fw-bold">
                                                        <i class="fas fa-check me-1"></i> Konfirmasi Lunas
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted small">- Tidak ada -</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $p->status == 'lunas' ? 'bg-success' : ($p->status == 'ditolak' ? 'bg-danger' : 'bg-warning text-dark') }} rounded-pill px-3 py-1">
                                {{ strtoupper($p->status) }}
                            </span>
                        </td>
                        <td>
                            <small class="text-muted">{{ $p->kasir->name ?? '-' }}</small>
                        </td>
                        <td class="text-end pe-3">
                            @if($p->status !== 'lunas')
                                <form action="{{ route('kasir.payments.status', $p->id_pembayaran) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="lunas">
                                    <button type="submit" class="btn btn-sm btn-success text-white rounded-pill px-3 fw-bold" title="Konfirmasi Lunas">
                                        <i class="fas fa-check"></i> Lunas
                                    </button>
                                </form>
                            @endif
                            @if($p->status !== 'ditolak')
                                <form action="{{ route('kasir.payments.status', $p->id_pembayaran) }}" method="POST" class="d-inline" onsubmit="return confirm('Tolak pembayaran ini?')">
                                    @csrf
                                    <input type="hidden" name="status" value="ditolak">
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" title="Tolak">
                                        <i class="fas fa-times"></i> Tolak
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-5 text-center text-muted">
                            Belum ada catatan pembayaran.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $payments->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

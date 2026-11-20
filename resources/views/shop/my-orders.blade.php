@extends('layouts.shop')

@section('title', 'Pesanan Saya - Toko Buah Pak Ridwan')

@section('content')
<!-- Single Page Header start -->
<div class="container-fluid page-header py-5 single-page-header">
    <h1 class="text-center text-white display-6 fw-bold">Riwayat Pesanan Saya</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Beranda</a></li>
        <li class="breadcrumb-item active text-white">Pesanan Saya</li>
    </ol>
</div>
<!-- Single Page Header End -->

<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">Daftar Pesanan Anda</h2>
                <p class="text-muted mb-0">Pantau proses pengiriman dan status pembayaran buah pesanan Anda di sini.</p>
            </div>
            <a href="{{ route('shop') }}" class="btn btn-outline-primary rounded-pill px-4 py-2">
                <i class="fas fa-plus me-1"></i> Pesan Buah Lagi
            </a>
        </div>

        @if($orders->isEmpty())
            <div class="text-center py-5 bg-light rounded-4 border shadow-sm">
                <i class="fas fa-receipt fa-4x text-muted mb-3"></i>
                <h4 class="fw-bold text-dark">Belum Ada Riwayat Pesanan</h4>
                <p class="text-muted">Anda belum pernah melakukan pemesanan buah.</p>
                <a href="{{ route('shop') }}" class="btn btn-primary rounded-pill px-4 py-2 text-white fw-bold">
                    Mulai Berbelanja
                </a>
            </div>
        @else
            <div class="row g-4">
                @foreach($orders as $order)
                <div class="col-12">
                    <div class="card border rounded-4 shadow-sm overflow-hidden">
                        <div class="card-header bg-light py-3 px-4 d-flex flex-wrap justify-content-between align-items-center border-bottom">
                            <div class="d-flex align-items-center gap-3">
                                <span class="fw-bold text-dark fs-5"><i class="fas fa-receipt text-primary me-2"></i>{{ $order->kode_pesanan }}</span>
                                <span class="text-muted small">| {{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                                <span class="badge {{ $order->sumber == 'online' ? 'bg-info text-dark' : 'bg-secondary text-white' }} text-uppercase">{{ $order->sumber }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                                <span class="{{ $order->status_badge }} px-3 py-2 rounded-pill fs-6">
                                    {{ $order->status_label }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-7">
                                    <h6 class="fw-bold text-muted mb-2">Item Buah yang Dipesan:</h6>
                                    <ul class="list-unstyled mb-0">
                                        @foreach($order->details as $detail)
                                        <li class="d-flex justify-content-between py-1 border-bottom-dashed">
                                            <span>
                                                <strong>{{ $detail->produk->nama_produk ?? 'Buah' }}</strong> 
                                                <small class="text-muted">({{ floatval($detail->jumlah) }} {{ $detail->produk->satuan ?? 'kg' }} x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }})</small>
                                            </span>
                                            <span class="fw-bold text-dark">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-md-5 border-start ps-md-4">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Metode Pengambilan:</span>
                                        <span class="fw-bold text-dark text-uppercase">{{ $order->metode_ambil == 'antar' ? 'Antar Kurir ke Rumah' : 'Ambil di Toko' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Metode Pembayaran:</span>
                                        <span class="fw-bold text-dark text-uppercase">{{ $order->pembayaran->metode ?? '-' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Status Pembayaran:</span>
                                        <span class="badge {{ optional($order->pembayaran)->status == 'lunas' ? 'bg-success' : (optional($order->pembayaran)->status == 'ditolak' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                            {{ strtoupper(optional($order->pembayaran)->status ?? 'Belum') }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between pt-2 border-top">
                                        <span class="fw-bold fs-6 text-dark">Total Pembayaran:</span>
                                        <span class="fw-bold fs-5 text-primary">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</span>
                                    </div>

                                    <div class="d-flex gap-2 mt-3">
                                        <a href="{{ route('orders.detail', $order->id_pesanan) }}" class="btn btn-outline-primary rounded-pill flex-grow-1">
                                            <i class="fas fa-eye me-1"></i> Rincian & Lacak
                                        </a>

                                        {{-- Tombol Batalkan Pesanan (Sesuai PDF: hanya boleh jika status masih 'menunggu'!) --}}
                                        @if($order->status === 'menunggu')
                                            <form action="{{ route('orders.cancel', $order->id_pesanan) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan #{{ $order->kode_pesanan }}?')">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger rounded-pill">
                                                    <i class="fas fa-times me-1"></i> Batalkan
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection

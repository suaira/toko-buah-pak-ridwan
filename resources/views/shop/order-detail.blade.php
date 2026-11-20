@extends('layouts.shop')

@section('title', 'Detail Pesanan #' . $order->kode_pesanan . ' - Toko Buah Pak Ridwan')

@section('styles')
<style>
    .track-step {
        position: relative;
        text-align: center;
        flex: 1;
    }
    .track-step::before {
        content: '';
        position: absolute;
        top: 20px;
        left: -50%;
        width: 100%;
        height: 4px;
        background-color: #e9ecef;
        z-index: 1;
    }
    .track-step:first-child::before {
        display: none;
    }
    .track-step.active::before {
        background-color: #81c408;
    }
    .track-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background-color: #e9ecef;
        color: #6c757d;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        position: relative;
        z-index: 2;
        font-size: 1.1rem;
        margin-bottom: 8px;
        transition: all 0.3s;
    }
    .track-step.active .track-icon {
        background-color: #81c408;
        color: #ffffff;
        box-shadow: 0 0 0 5px rgba(129, 196, 8, 0.2);
    }
</style>
@endsection

@section('content')
<!-- Single Page Header start -->
<div class="container-fluid page-header py-5 single-page-header">
    <h1 class="text-center text-white display-6 fw-bold">Rincian & Tracking Pesanan</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('orders.my') }}" class="text-white">Pesanan Saya</a></li>
        <li class="breadcrumb-item active text-white">{{ $order->kode_pesanan }}</li>
    </ol>
</div>
<!-- Single Page Header End -->

<div class="container-fluid py-5">
    <div class="container py-4">
        <!-- Header Bar -->
        <div class="card border rounded-4 shadow-sm mb-4">
            <div class="card-body p-4 d-flex flex-wrap justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold text-dark mb-1">
                        <i class="fas fa-receipt text-primary me-2"></i>Pesanan #{{ $order->kode_pesanan }}
                    </h3>
                    <p class="text-muted mb-0">
                        Waktu Pemesanan: <strong>{{ $order->created_at->format('d F Y, H:i') }} WIB</strong> | 
                        Sumber: <span class="badge bg-secondary text-uppercase">{{ $order->sumber }}</span>
                    </p>
                </div>
                <div class="mt-3 mt-md-0 d-flex gap-2 align-items-center">
                    <span class="{{ $order->status_badge }} px-3 py-2 fs-6 rounded-pill">
                        Status: {{ $order->status_label }}
                    </span>

                    @if($order->status === 'menunggu')
                        <form action="{{ route('orders.cancel', $order->id_pesanan) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger rounded-pill px-3">
                                <i class="fas fa-times me-1"></i> Batalkan Pesanan
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tracking Visual Timeline -->
        @if(!in_array($order->status, ['dibatalkan', 'ditolak']))
            @php
                $statusHierarchy = [
                    'menunggu' => 1,
                    'dikonfirmasi' => 2,
                    'diproses' => 3,
                    'siap' => 4,
                    'dikirim' => 4,
                    'selesai' => 5
                ];
                $currentLevel = $statusHierarchy[$order->status] ?? 1;
            @endphp
            <div class="card border rounded-4 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-4"><i class="fas fa-route text-primary me-2"></i>Status Alur Pengiriman</h5>
                    <div class="d-flex justify-content-between my-3">
                        <div class="track-step {{ $currentLevel >= 1 ? 'active' : '' }}">
                            <div class="track-icon"><i class="fas fa-clock"></i></div>
                            <div class="fw-bold small">Menunggu</div>
                            <div class="text-muted" style="font-size: 0.75rem;">Verifikasi Toko</div>
                        </div>
                        <div class="track-step {{ $currentLevel >= 2 ? 'active' : '' }}">
                            <div class="track-icon"><i class="fas fa-check"></i></div>
                            <div class="fw-bold small">Dikonfirmasi</div>
                            <div class="text-muted" style="font-size: 0.75rem;">Kasir Menerima</div>
                        </div>
                        <div class="track-step {{ $currentLevel >= 3 ? 'active' : '' }}">
                            <div class="track-icon"><i class="fas fa-box"></i></div>
                            <div class="fw-bold small">Diproses</div>
                            <div class="text-muted" style="font-size: 0.75rem;">Sortir & Kemas</div>
                        </div>
                        <div class="track-step {{ $currentLevel >= 4 ? 'active' : '' }}">
                            <div class="track-icon">
                                <i class="fas {{ $order->metode_ambil == 'antar' ? 'fa-shipping-fast' : 'fa-store' }}"></i>
                            </div>
                            <div class="fw-bold small">{{ $order->metode_ambil == 'antar' ? 'Sedang Diantar' : 'Siap Diambil' }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $order->metode_ambil == 'antar' ? 'Kurir Menuju Lokasi' : 'Ambil di Toko' }}</div>
                        </div>
                        <div class="track-step {{ $currentLevel >= 5 ? 'active' : '' }}">
                            <div class="track-icon"><i class="fas fa-star"></i></div>
                            <div class="fw-bold small">Selesai</div>
                            <div class="text-muted" style="font-size: 0.75rem;">Pesanan Diterima</div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-danger border-danger rounded-4 p-4 shadow-sm mb-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-ban fa-3x text-danger me-3"></i>
                    <div>
                        <h4 class="alert-heading fw-bold">Pesanan Ini Berstatus {{ strtoupper($order->status) }}</h4>
                        <p class="mb-0">
                            @if($order->status == 'dibatalkan')
                                Anda telah membatalkan pesanan ini sebelum diproses oleh toko. Stok produk telah dikembalikan secara otomatis.
                            @else
                                Pesanan ditolak oleh pihak toko. Silakan hubungi kasir atau buat pesanan baru.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="row g-4">
            <!-- Rincian Produk Buah -->
            <div class="col-lg-8">
                <div class="card border rounded-4 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-shopping-basket text-primary me-2"></i>Rincian Produk Buah</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Nama Produk</th>
                                        <th class="text-center">Kuantitas</th>
                                        <th class="text-end">Harga Satuan</th>
                                        <th class="text-end">Potongan</th>
                                        <th class="text-end pe-4">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->details as $item)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                @php
                                                    $imgUrl = asset('fruitables/img/' . ($item->produk->gambar ?? 'fruite-item-5.jpg'));
                                                    if ($item->produk && file_exists(public_path('storage/products/' . $item->produk->gambar))) {
                                                        $imgUrl = asset('storage/products/' . $item->produk->gambar);
                                                    }
                                                @endphp
                                                <img src="{{ $imgUrl }}" class="rounded me-3 border" style="width: 50px; height: 50px; object-fit: cover;" alt="">
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ $item->produk->nama_produk ?? 'Buah' }}</h6>
                                                    <small class="text-muted">{{ $item->produk->kategori->nama_kategori ?? 'Buah Segar' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center fw-bold">{{ floatval($item->jumlah) }} {{ $item->produk->satuan ?? 'kg' }}</td>
                                        <td class="text-end">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                        <td class="text-end">
                                            @if($item->potongan > 0)
                                                <span class="text-danger fw-bold">- Rp {{ number_format($item->potongan, 0, ',', '.') }}</span>
                                                <small class="d-block text-muted">({{ floatval($item->persen_diskon) }}%)</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4 fw-bold text-dark">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="border-top">
                                    <tr>
                                        <td colspan="4" class="text-end text-muted pt-3">Subtotal Buah:</td>
                                        <td class="text-end pe-4 pt-3 fw-bold">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    @if($order->total_diskon > 0)
                                    <tr>
                                        <td colspan="4" class="text-end text-success"><i class="fas fa-gift me-1"></i> Total Diskon Promo:</td>
                                        <td class="text-end pe-4 text-success fw-bold">- Rp {{ number_format($order->total_diskon, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td colspan="4" class="text-end text-muted">Ongkos Kirim ({{ $order->metode_ambil == 'antar' ? ($order->area->nama_area ?? 'Antar') : 'Ambil di Toko' }}):</td>
                                        <td class="text-end pe-4 fw-bold">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr class="border-top border-2">
                                        <td colspan="4" class="text-end fs-5 fw-bold text-dark py-3">Total Pembayaran:</td>
                                        <td class="text-end pe-4 fs-5 fw-bold text-primary py-3">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Informasi Penerima & Pengiriman -->
                <div class="card border rounded-4 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-map-marked-alt text-primary me-2"></i>Informasi Pengiriman</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <small class="text-muted d-block">Nama Pelanggan:</small>
                                <span class="fw-bold text-dark">{{ $order->pelanggan->nama_pelanggan ?? Auth::user()->name }}</span>
                            </div>
                            <div class="col-sm-6">
                                <small class="text-muted d-block">Nomor Telepon / WA:</small>
                                <span class="fw-bold text-dark">{{ $order->pelanggan->no_hp ?? '-' }}</span>
                            </div>
                            <div class="col-sm-6">
                                <small class="text-muted d-block">Metode Pengambilan:</small>
                                <span class="badge {{ $order->metode_ambil == 'antar' ? 'bg-primary' : 'bg-success' }} text-uppercase">
                                    {{ $order->metode_ambil == 'antar' ? 'Antar Kurir ke Rumah' : 'Ambil di Toko' }}
                                </span>
                            </div>
                            <div class="col-sm-6">
                                <small class="text-muted d-block">Area Pengantaran:</small>
                                <span class="fw-bold text-dark">{{ $order->area->nama_area ?? 'Ambil di Toko' }}</span>
                            </div>
                            @if($order->metode_ambil == 'antar')
                            <div class="col-12">
                                <small class="text-muted d-block">Alamat Pengantaran:</small>
                                <div class="p-3 bg-light rounded-3 mt-1 text-dark border">{{ $order->alamat_antar ?: 'Alamat belum diisi.' }}</div>
                            </div>
                            @endif
                            @if($order->catatan)
                            <div class="col-12">
                                <small class="text-muted d-block">Catatan Pelanggan:</small>
                                <div class="p-3 bg-light rounded-3 mt-1 text-muted fst-italic border">{{ $order->catatan }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pembayaran & Upload Bukti Transfer -->
            <div class="col-lg-4">
                <div class="card border rounded-4 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-credit-card text-primary me-2"></i>Status Pembayaran</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <small class="text-muted d-block">Metode Pembayaran:</small>
                            <h6 class="fw-bold text-uppercase text-dark mt-1">
                                <i class="fas {{ optional($order->pembayaran)->metode == 'transfer' ? 'fa-university' : 'fa-hand-holding-usd' }} me-1 text-primary"></i>
                                {{ optional($order->pembayaran)->metode ?? 'Transfer' }}
                            </h6>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Status Verifikasi:</small>
                            <span class="badge {{ optional($order->pembayaran)->status == 'lunas' ? 'bg-success' : (optional($order->pembayaran)->status == 'ditolak' ? 'bg-danger' : 'bg-warning text-dark') }} fs-6 px-3 py-2 mt-1">
                                {{ strtoupper(optional($order->pembayaran)->status ?? 'MENUNGGU') }}
                            </span>
                        </div>

                        @if(optional($order->pembayaran)->dibayar_pada)
                            <div class="mb-3">
                                <small class="text-muted d-block">Waktu Pembayaran Diverifikasi:</small>
                                <span class="text-dark small">{{ $order->pembayaran->dibayar_pada->format('d M Y, H:i') }} WIB</span>
                            </div>
                        @endif

                        <hr class="my-3">

                        <!-- Instruksi Transfer & Form Upload Bukti -->
                        @if(optional($order->pembayaran)->metode === 'transfer')
                            <div class="mb-4">
                                <h6 class="fw-bold text-dark mb-2">Rekening Transfer Toko:</h6>
                                <div class="p-3 bg-light rounded-3 border">
                                    <div class="text-muted small">Bank Central Asia (BCA)</div>
                                    <div class="fw-bold fs-5 text-primary">823-019-2819</div>
                                    <div class="small text-dark">a.n <strong>RIDWAN</strong></div>
                                </div>
                            </div>

                            @if(optional($order->pembayaran)->bukti_transfer)
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">Bukti Transfer yang Diunggah:</small>
                                    <a href="{{ asset('storage/transfer_proofs/' . $order->pembayaran->bukti_transfer) }}" target="_blank">
                                        <img src="{{ asset('storage/transfer_proofs/' . $order->pembayaran->bukti_transfer) }}" class="img-fluid rounded border shadow-sm w-100" style="max-height: 250px; object-fit: contain;" alt="Bukti Transfer">
                                    </a>
                                    <small class="text-muted d-block mt-1 text-center font-italic">Klik gambar untuk melihat resolusi penuh</small>
                                </div>
                            @endif

                            @if(optional($order->pembayaran)->status !== 'lunas' && !in_array($order->status, ['dibatalkan', 'ditolak']))
                                <form action="{{ route('orders.pay', $order->id_pesanan) }}" method="POST" enctype="multipart/form-data" class="mt-3">
                                    @csrf
                                    <label class="form-label fw-bold text-dark small">
                                        {{ optional($order->pembayaran)->bukti_transfer ? 'Unggah Ulang Bukti Transfer:' : 'Unggah Bukti Transfer Sekarang:' }}
                                    </label>
                                    <input type="file" name="bukti_transfer" class="form-control form-control-sm mb-2" accept="image/*" required>
                                    <button type="submit" class="btn btn-primary rounded-pill w-100 btn-sm py-2 fw-bold text-white shadow-sm">
                                        <i class="fas fa-upload me-1"></i> Kirim Bukti Transfer
                                    </button>
                                </form>
                            @endif
                        @else
                            <div class="alert alert-info border-info rounded-3 small">
                                <i class="fas fa-info-circle me-1"></i> Pembayaran COD: Harap siapkan uang pas sebesar <strong>Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</strong> untuk diserahkan ke kurir saat buah diantar.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="d-grid">
                    <a href="{{ route('orders.my') }}" class="btn btn-outline-secondary rounded-pill py-2">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Pesanan Saya
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

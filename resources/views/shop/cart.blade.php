@extends('layouts.shop')

@section('title', 'Keranjang Belanja - Toko Buah Pak Ridwan')

@section('content')
<!-- Single Page Header start -->
<div class="container-fluid page-header py-5 single-page-header">
    <h1 class="text-center text-white display-6 fw-bold">Keranjang Belanja</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('shop') }}" class="text-white">Katalog Buah</a></li>
        <li class="breadcrumb-item active text-white">Keranjang</li>
    </ol>
</div>
<!-- Single Page Header End -->

<!-- Cart Page Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        @if($cartItems->isEmpty())
            <div class="text-center py-5 bg-light rounded-4 border shadow-sm">
                <i class="fas fa-shopping-basket fa-4x text-muted mb-3"></i>
                <h3 class="fw-bold text-dark">Keranjang Belanja Anda Masih Kosong</h3>
                <p class="text-muted">Ayo pilih buah segar pilihan Anda di katalog toko kami!</p>
                <a href="{{ route('shop') }}" class="btn btn-primary rounded-pill px-4 py-2 mt-2 fw-bold text-white">
                    <i class="fas fa-apple-alt me-2"></i> Belanja Buah Sekarang
                </a>
            </div>
        @else
            <div class="table-responsive shadow-sm rounded-4 overflow-hidden border">
                <table class="table align-middle text-center mb-0 bg-white">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th scope="col" class="py-3">Foto Buah</th>
                            <th scope="col" class="py-3 text-start">Nama Produk</th>
                            <th scope="col" class="py-3">Harga Satuan</th>
                            <th scope="col" class="py-3">Jumlah Beli</th>
                            <th scope="col" class="py-3">Diskon Promo</th>
                            <th scope="col" class="py-3">Subtotal</th>
                            <th scope="col" class="py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartCalculations['items'] as $item)
                        <tr>
                            <th scope="row">
                                <div class="d-flex align-items-center justify-content-center">
                                    @php
                                        $imgUrl = asset('fruitables/img/' . ($item['produk']->gambar ?: 'fruite-item-5.jpg'));
                                        if (file_exists(public_path('storage/products/' . $item['produk']->gambar))) {
                                            $imgUrl = asset('storage/products/' . $item['produk']->gambar);
                                        }
                                    @endphp
                                    <img src="{{ $imgUrl }}" class="img-fluid rounded-circle border shadow-sm" style="width: 75px; height: 75px; object-fit: cover;" alt="{{ $item['produk']->nama_produk }}">
                                </div>
                            </th>
                            <td class="text-start">
                                <h6 class="mb-0 fw-bold text-dark">{{ $item['produk']->nama_produk }}</h6>
                                <small class="text-muted">{{ $item['produk']->kategori->nama_kategori ?? 'Buah Segar' }}</small>
                                @if($item['produk']->stok < $item['jumlah'])
                                    <div class="text-danger small mt-1"><i class="fas fa-exclamation-triangle"></i> Melebihi sisa stok (stok: {{ $item['produk']->stok }})</div>
                                @endif
                            </td>
                            <td>
                                <p class="mb-0 fw-bold text-dark">Rp {{ number_format($item['harga_satuan'], 0, ',', '.') }}</p>
                                <small class="text-muted">/{{ $item['produk']->satuan }}</small>
                            </td>
                            <td>
                                <form action="{{ route('cart.update') }}" method="POST" class="d-inline-flex justify-content-center align-items-center">
                                    @csrf
                                    <input type="hidden" name="id_keranjang" value="{{ $item['cart_id'] }}">
                                    <div class="input-group quantity" style="width: 140px;">
                                        <button type="button" class="btn btn-sm btn-minus rounded-circle bg-light border" onclick="updateQty(this, -1)">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                        <input type="number" name="jumlah" class="form-control form-control-sm text-center border-0 fw-bold qty-input" value="{{ floatval($item['jumlah']) }}" min="1" max="{{ $item['produk']->stok }}" step="1" onchange="this.form.submit()">
                                        <button type="button" class="btn btn-sm btn-plus rounded-circle bg-light border" onclick="updateQty(this, 1)">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-outline-success ms-2 rounded-circle" title="Perbarui"><i class="fas fa-check"></i></button>
                                </form>
                            </td>
                            <td>
                                @if($item['potongan'] > 0)
                                    <span class="badge bg-danger mb-1">-{{ floatval($item['persen_diskon']) }}%</span>
                                    <div class="text-danger fw-bold small">- Rp {{ number_format($item['potongan'], 0, ',', '.') }}</div>
                                    <small class="text-muted d-block" style="font-size: 0.7rem;">{{ $item['promo']->nama_promo ?? 'Promo' }}</small>
                                @else
                                    @if($item['promo'])
                                        <span class="text-muted small">Beli min. {{ floatval($item['promo']->min_jumlah) }} {{ $item['produk']->satuan }} utk diskon {{ floatval($item['promo']->persen_diskon) }}%</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                <p class="mb-0 fw-bold text-primary fs-6">
                                    Rp {{ number_format($item['subtotal_setelah_diskon'], 0, ',', '.') }}
                                </p>
                                @if($item['potongan'] > 0)
                                    <small class="text-muted text-decoration-line-through">Rp {{ number_format($item['subtotal_kotor'], 0, ',', '.') }}</small>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('cart.remove', $item['cart_id']) }}" method="POST" onsubmit="return confirm('Hapus buah {{ $item['produk']->nama_produk }} dari keranjang?')">
                                    @csrf
                                    <button type="submit" class="btn btn-md rounded-circle bg-light border text-danger" title="Hapus dari keranjang">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Ringkasan Total & Tombol Checkout -->
            <div class="row g-4 justify-content-end mt-4">
                <div class="col-lg-6 col-md-12">
                    <div class="bg-light rounded-4 p-4 border shadow-sm">
                        <h4 class="fw-bold mb-3 text-dark"><i class="fas fa-tags me-2 text-warning"></i>Informasi Promo Toko</h4>
                        <p class="text-muted small">
                            Toko Buah Pak Ridwan menerapkan <strong>Diskon Otomatis</strong>. Jika kuantitas pembelian buah Anda telah memenuhi syarat promo minimal kuantitas yang ditentukan oleh Admin/Pak Ridwan, potongan harga langsung terpotong secara real-time pada keranjang belanja Anda.
                        </p>
                        <a href="{{ route('shop') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 text-dark">
                            <i class="fas fa-plus me-1"></i> Tambah Buah Lainnya
                        </a>
                    </div>
                </div>

                <div class="col-sm-8 col-md-7 col-lg-6 col-xl-5">
                    <div class="bg-light rounded-4 p-4 border shadow-sm">
                        <h4 class="mb-4 fw-bold text-dark">Ringkasan Belanja</h4>
                        
                        <div class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                            <span class="text-muted">Total Subtotal:</span>
                            <span class="fw-bold text-dark">Rp {{ number_format($cartCalculations['subtotal'], 0, ',', '.') }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                            <span class="text-success"><i class="fas fa-gift me-1"></i> Total Potongan Promo:</span>
                            <span class="fw-bold text-success">- Rp {{ number_format($cartCalculations['total_diskon'], 0, ',', '.') }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                            <span class="text-muted">Ongkos Kirim:</span>
                            <span class="text-muted small font-italic">Dihitung saat checkout</span>
                        </div>

                        <div class="d-flex justify-content-between my-4 pt-2 border-top">
                            <h5 class="fw-bold mb-0 text-dark">Total Pembayaran:</h5>
                            <h4 class="fw-bold mb-0 text-primary">Rp {{ number_format($cartCalculations['total_bersih'], 0, ',', '.') }}</h4>
                        </div>

                        <a href="{{ route('checkout') }}" class="btn btn-primary rounded-pill px-4 py-3 text-uppercase text-white fw-bold w-100 shadow">
                            Lanjut ke Checkout <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
<!-- Cart Page End -->
@endsection

@section('scripts')
<script>
    function updateQty(btn, delta) {
        const input = btn.closest('.input-group').querySelector('.qty-input');
        const max = parseFloat(input.getAttribute('max')) || 999;
        let current = parseFloat(input.value) || 1;
        let nextVal = current + delta;
        if (nextVal >= 1 && nextVal <= max) {
            input.value = nextVal;
            input.form.submit();
        }
    }
</script>
@endsection

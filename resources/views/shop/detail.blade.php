@extends('layouts.shop')

@section('title', $produk->nama_produk . ' - Toko Buah Pak Ridwan')

@section('content')
<!-- Single Page Header start -->
<div class="container-fluid page-header py-5 single-page-header">
    <h1 class="text-center text-white display-6 fw-bold">Detail Buah</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('shop') }}" class="text-white">Katalog Buah</a></li>
        <li class="breadcrumb-item active text-white">{{ $produk->nama_produk }}</li>
    </ol>
</div>
<!-- Single Page Header End -->

<!-- Single Product Start -->
<div class="container-fluid py-5 mt-4">
    <div class="container py-5">
        <div class="row g-4 mb-5">
            <div class="col-lg-8 col-xl-9">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="border rounded-4 overflow-hidden position-relative shadow-sm">
                            @php
                                $imgUrl = asset('fruitables/img/' . ($produk->gambar ?: 'fruite-item-5.jpg'));
                                if (file_exists(public_path('storage/products/' . $produk->gambar))) {
                                    $imgUrl = asset('storage/products/' . $produk->gambar);
                                }
                            @endphp
                            <img src="{{ $imgUrl }}" class="img-fluid rounded w-100" alt="{{ $produk->nama_produk }}" style="min-height: 350px; max-height: 450px; object-fit: cover;">
                            
                            @if($produk->active_promo)
                                <div class="badge-promo fs-6">
                                    <i class="fas fa-percent me-1"></i> Promo Diskon {{ floatval($produk->active_promo->persen_diskon) }}%
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <span class="badge bg-secondary text-dark text-uppercase px-3 py-2 rounded-pill fw-bold">{{ $produk->kategori->nama_kategori ?? 'Buah Segar' }}</span>
                        <h2 class="fw-bold text-dark mt-2 mb-3">{{ $produk->nama_produk }}</h2>
                        <h4 class="fw-bold text-primary mb-3">
                            Rp {{ number_format($produk->harga, 0, ',', '.') }}
                            <small class="text-muted fs-6 font-monospace">/ {{ $produk->satuan }}</small>
                        </h4>

                        <!-- Status Stok -->
                        <div class="mb-3">
                            @if($produk->stok <= 0)
                                <span class="badge bg-danger p-2"><i class="fas fa-times-circle me-1"></i> Stok Habis (Sedang Restock)</span>
                            @else
                                <span class="badge bg-success p-2"><i class="fas fa-check-circle me-1"></i> Stok Tersedia: <strong>{{ floatval($produk->stok) }} {{ $produk->satuan }}</strong></span>
                            @endif
                        </div>

                        <!-- Promo Info Alert -->
                        @if($produk->active_promo)
                            <div class="alert alert-warning border-warning rounded-3 shadow-sm py-2 px-3 mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-tag fa-2x text-warning me-3"></i>
                                    <div>
                                        <strong class="text-dark">{{ $produk->active_promo->nama_promo }}</strong>
                                        <p class="mb-0 text-muted small">
                                            Dapatkan potongan <strong>{{ floatval($produk->active_promo->persen_diskon) }}%</strong> dengan minimal pembelian <strong>{{ floatval($produk->active_promo->min_jumlah) }} {{ $produk->satuan }}</strong>. Diskon otomatis dihitung di keranjang!
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <p class="mb-4 text-muted">
                            {{ $produk->deskripsi ?: 'Buah segar kualitas terbaik dari Toko Buah Pak Ridwan. Disortir dengan standar ketat untuk menjamin rasa manis, kandungan air melimpah, dan kesegaran maksimal saat dinikmati keluarga Anda.' }}
                        </p>

                        <!-- Form Tambah ke Keranjang -->
                        @if($produk->stok > 0)
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_produk" value="{{ $produk->id_produk }}">
                                
                                <div class="input-group quantity mb-4" style="width: 180px;">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-sm btn-minus rounded-circle bg-light border" onclick="decreaseQty()">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <input type="number" id="qtyInput" name="jumlah" class="form-control form-control-sm text-center border-0 fw-bold fs-5" value="1" min="1" max="{{ $produk->stok }}" step="{{ $produk->satuan == 'kg' ? '1' : '1' }}">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-sm btn-plus rounded-circle bg-light border" onclick="increaseQty()">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary border border-secondary rounded-pill px-4 py-2 mb-4 text-white fw-bold shadow-sm">
                                        <i class="fa fa-shopping-bag me-2"></i> Tambah ke Keranjang
                                    </button>
                                    <a href="{{ route('shop') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 mb-4">
                                        Pilih Buah Lain
                                    </a>
                                </div>
                            </form>
                        @else
                            <button class="btn btn-secondary rounded-pill px-4 py-2 mb-4" disabled>
                                <i class="fas fa-ban me-2"></i> Stok Habis
                            </button>
                        @endif

                        <!-- Feature highlights -->
                        <div class="row g-2 border-top pt-3 text-muted small">
                            <div class="col-6"><i class="fas fa-shield-alt text-primary me-2"></i>Garansi Buah Segar</div>
                            <div class="col-6"><i class="fas fa-truck text-primary me-2"></i>Antar Seluruh Pekanbaru</div>
                            <div class="col-6"><i class="fas fa-store text-primary me-2"></i>Bisa Ambil di Toko</div>
                            <div class="col-6"><i class="fas fa-money-bill-wave text-primary me-2"></i>Bisa Transfer / COD</div>
                        </div>
                    </div>

                    <!-- Tabs Informasi Tambahan -->
                    <div class="col-lg-12 mt-4">
                        <nav>
                            <div class="nav nav-tabs mb-3">
                                <button class="nav-link active border-white border-bottom-0 fw-bold" type="button" role="tab" id="nav-about-tab" data-bs-toggle="tab" data-bs-target="#nav-about" aria-controls="nav-about" aria-selected="true">Deskripsi & Kualitas</button>
                                <button class="nav-link border-white border-bottom-0 fw-bold" type="button" role="tab" id="nav-mission-tab" data-bs-toggle="tab" data-bs-target="#nav-mission" aria-controls="nav-mission" aria-selected="false">Tips Penyimpanan</button>
                            </div>
                        </nav>
                        <div class="tab-content mb-5 bg-light p-4 rounded-4 border">
                            <div class="tab-pane active" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                                <p>Semua buah di Toko Buah Pak Ridwan melalui proses sortir ketat setiap pagi. Kami memastikan tingkat kematangan yang pas saat buah sampai ke tangan Anda, sehingga siap disantap langsung atau disimpan beberapa hari.</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li><i class="fas fa-check text-success me-2"></i> Kualitas Grade Super (Pilihan Utama)</li>
                                            <li><i class="fas fa-check text-success me-2"></i> Rasa manis alami, bukan suntikan pengawet</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li><i class="fas fa-check text-success me-2"></i> Dikemas higienis dengan jaring pelindung buah</li>
                                            <li><i class="fas fa-check text-success me-2"></i> Aman untuk dikonsumsi anak-anak dan lansia</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="nav-mission" role="tabpanel" aria-labelledby="nav-mission-tab">
                                <p>Agar kesegaran buah bertahan maksimal di rumah:</p>
                                <ul>
                                    <li>Simpan di suhu ruang jika buah belum terlalu matang.</li>
                                    <li>Masukkan ke dalam laci khusus buah di kulkas bersuhu 4°C - 8°C untuk menjaga kerenyahannya.</li>
                                    <li>Cuci buah dengan air mengalir sesaat sebelum dikonsumsi agar tidak cepat lembab.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Kategori & Produk Terkait -->
            <div class="col-lg-4 col-xl-3">
                <div class="row g-4 fruite">
                    <div class="col-lg-12">
                        <div class="mb-4 bg-light p-4 rounded-4 border">
                            <h4 class="mb-3 fw-bold">Kategori Lain</h4>
                            <ul class="list-unstyled fruite-categorie">
                                @foreach($kategoriList as $kat)
                                <li class="mb-2">
                                    <div class="d-flex justify-content-between fruite-name">
                                        <a href="{{ route('shop', ['kategori' => $kat->id_kategori]) }}" class="text-dark">
                                            <i class="fas fa-apple-alt me-2 text-primary"></i>{{ $kat->nama_kategori }}
                                        </a>
                                        <span class="badge bg-light text-muted border">{{ $kat->produk_count }}</span>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Produk Terkait -->
                    <div class="col-lg-12">
                        <h4 class="mb-3 fw-bold">Buah Terkait</h4>
                        @foreach($relatedProducts as $rel)
                        <div class="d-flex align-items-center justify-content-start mb-3 bg-light p-2 rounded-3 border">
                            <div class="rounded me-3 overflow-hidden" style="width: 80px; height: 80px;">
                                @php
                                    $relImg = asset('fruitables/img/' . ($rel->gambar ?: 'fruite-item-1.jpg'));
                                    if (file_exists(public_path('storage/products/' . $rel->gambar))) {
                                        $relImg = asset('storage/products/' . $rel->gambar);
                                    }
                                @endphp
                                <img src="{{ $relImg }}" class="img-fluid rounded w-100 h-100" style="object-fit: cover;" alt="{{ $rel->nama_produk }}">
                            </div>
                            <div>
                                <h6 class="mb-1 fw-bold"><a href="{{ route('shop.detail', $rel->id_produk) }}" class="text-dark">{{ $rel->nama_produk }}</a></h6>
                                <div class="d-flex mb-1">
                                    <h6 class="text-primary fw-bold mb-0">Rp {{ number_format($rel->harga, 0, ',', '.') }}</h6>
                                </div>
                                <a href="{{ route('shop.detail', $rel->id_produk) }}" class="btn btn-sm btn-outline-primary py-0 px-2 rounded-pill" style="font-size: 0.75rem;">Lihat</a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Single Product End -->
@endsection

@section('scripts')
<script>
    function decreaseQty() {
        const input = document.getElementById('qtyInput');
        let current = parseFloat(input.value) || 1;
        if (current > 1) {
            input.value = current - 1;
        }
    }

    function increaseQty() {
        const input = document.getElementById('qtyInput');
        const max = parseFloat(input.getAttribute('max')) || 999;
        let current = parseFloat(input.value) || 1;
        if (current < max) {
            input.value = current + 1;
        }
    }
</script>
@endsection

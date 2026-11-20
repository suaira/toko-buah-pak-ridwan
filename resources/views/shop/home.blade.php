@extends('layouts.shop')

@section('title', 'Toko Buah Pak Ridwan - Buah Segar Pilihan Setiap Hari')

@section('content')
<!-- Hero Start -->
<div class="container-fluid py-5 mb-5 hero-header">
    <div class="container py-5">
        <div class="row g-5 align-items-center">
            <div class="col-md-12 col-lg-7">
                <h4 class="mb-3 text-secondary fw-bold text-uppercase" style="letter-spacing: 2px;">100% Buah Alami & Segar</h4>
                <h1 class="mb-4 display-4 text-primary fw-extrabold">Buah Segar Pilihan Berkualitas Untuk Keluarga Sehat</h1>
                <p class="fs-5 text-muted mb-4">Nikmati kelezatan aneka ragam buah lokal dan impor pilihan di Pekanbaru. Dipetik segar, disortir higienis, dan diantar langsung ke depan pintu rumah Anda!</p>
                <div class="position-relative mx-auto" style="max-width: 550px;">
                    <form action="{{ route('shop') }}" method="GET">
                        <input class="form-control border-2 border-secondary w-75 py-3 px-4 rounded-pill" type="text" name="q" placeholder="Cari buah kesukaan Anda...">
                        <button type="submit" class="btn btn-primary border-2 border-secondary py-3 px-4 position-absolute rounded-pill text-white h-100" style="top: 0; right: 25%;">Cari Buah</button>
                    </form>
                </div>
            </div>
            <div class="col-md-12 col-lg-5">
                <div id="carouselId" class="carousel slide position-relative shadow-lg rounded-4 overflow-hidden" data-bs-ride="carousel">
                    <div class="carousel-inner" role="listbox">
                        <div class="carousel-item active rounded">
                            <img src="{{ asset('fruitables/img/hero-img-1.png') }}" class="img-fluid w-100 h-100 bg-secondary rounded" alt="First slide">
                            <a href="{{ route('shop') }}" class="btn px-4 py-2 text-white rounded bg-primary position-absolute top-50 start-50 translate-middle fw-bold">Buah Impor</a>
                        </div>
                        <div class="carousel-item rounded">
                            <img src="{{ asset('fruitables/img/hero-img-2.jpg') }}" class="img-fluid w-100 h-100 rounded" alt="Second slide">
                            <a href="{{ route('shop') }}" class="btn px-4 py-2 text-white rounded bg-primary position-absolute top-50 start-50 translate-middle fw-bold">Buah Lokal</a>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselId" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselId" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Hero End -->

<!-- Featurs Section Start -->
<div class="container-fluid featurs py-5">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="featurs-item text-center rounded bg-light p-4 shadow-sm h-100">
                    <div class="featurs-icon btn-square rounded-circle bg-secondary mb-4 mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-car-side fa-3x text-white"></i>
                    </div>
                    <div class="featurs-content text-center">
                        <h5 class="fw-bold">Pengantaran Cepat</h5>
                        <p class="mb-0 text-muted">Layanan antar langsung untuk seluruh wilayah Pekanbaru</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="featurs-item text-center rounded bg-light p-4 shadow-sm h-100">
                    <div class="featurs-icon btn-square rounded-circle bg-secondary mb-4 mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-user-shield fa-3x text-white"></i>
                    </div>
                    <div class="featurs-content text-center">
                        <h5 class="fw-bold">Jaminan Segar 100%</h5>
                        <p class="mb-0 text-muted">Buah disortir ketat, manis, matang pas dan tanpa bahan pengawet</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="featurs-item text-center rounded bg-light p-4 shadow-sm h-100">
                    <div class="featurs-icon btn-square rounded-circle bg-secondary mb-4 mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-percent fa-3x text-white"></i>
                    </div>
                    <div class="featurs-content text-center">
                        <h5 class="fw-bold">Promo Spesial</h5>
                        <p class="mb-0 text-muted">Dapatkan potongan diskon otomatis setiap beli dalam jumlah tertentu</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="featurs-item text-center rounded bg-light p-4 shadow-sm h-100">
                    <div class="featurs-icon btn-square rounded-circle bg-secondary mb-4 mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-phone-alt fa-3x text-white"></i>
                    </div>
                    <div class="featurs-content text-center">
                        <h5 class="fw-bold">Pelayanan Ramah</h5>
                        <p class="mb-0 text-muted">Siap melayani pesanan online maupun datang langsung ke toko</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Featurs Section End -->

<!-- Fruits Shop Section Start -->
<div class="container-fluid fruite py-5">
    <div class="container py-5">
        <div class="tab-class text-center">
            <div class="row g-4">
                <div class="col-lg-4 text-start">
                    <h1 class="fw-bold text-dark mb-0">Koleksi Buah Pilihan</h1>
                    <p class="text-muted">Pilih buah segar favorit Anda hari ini</p>
                </div>
                <div class="col-lg-8 text-end">
                    <ul class="nav nav-pills d-inline-flex text-center mb-5">
                        <li class="nav-item">
                            <a class="d-flex m-2 py-2 bg-light rounded-pill active" data-bs-toggle="pill" href="#tab-all">
                                <span class="text-dark px-3 fw-bold">Semua Buah</span>
                            </a>
                        </li>
                        @foreach($kategoriList as $kat)
                        <li class="nav-item">
                            <a class="d-flex py-2 m-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-kat-{{ $kat->id_kategori }}">
                                <span class="text-dark px-3 fw-bold">{{ $kat->nama_kategori }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="tab-content text-start">
                <!-- Tab Semua Buah -->
                <div id="tab-all" class="tab-pane fade show p-0 active">
                    <div class="row g-4">
                        @foreach($featuredProducts as $produk)
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="rounded position-relative fruite-item border border-secondary">
                                <div class="fruite-img position-relative overflow-hidden">
                                    @php
                                        $imgUrl = asset('fruitables/img/' . ($produk->gambar ?: 'fruite-item-5.jpg'));
                                        if (file_exists(public_path('storage/products/' . $produk->gambar))) {
                                            $imgUrl = asset('storage/products/' . $produk->gambar);
                                        }
                                    @endphp
                                    <img src="{{ $imgUrl }}" class="img-fluid w-100 rounded-top" alt="{{ $produk->nama_produk }}">
                                    
                                    {{-- Badge Stok --}}
                                    @if($produk->stok <= 0)
                                        <span class="badge bg-danger badge-stock"><i class="fas fa-times-circle me-1"></i> Habis</span>
                                    @else
                                        <span class="badge bg-success badge-stock"><i class="fas fa-check-circle me-1"></i> Stok {{ $produk->stok }} {{ $produk->satuan }}</span>
                                    @endif

                                    {{-- Badge Promo --}}
                                    @if($produk->active_promo)
                                        <span class="badge-promo">
                                            <i class="fas fa-tag me-1"></i> Diskon {{ floatval($produk->active_promo->persen_diskon) }}%
                                        </span>
                                    @endif
                                </div>
                                <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px; display: none;">{{ $produk->kategori->nama_kategori ?? 'Buah' }}</div>
                                <div class="p-4 border-top-0 rounded-bottom">
                                    <small class="text-secondary fw-bold text-uppercase">{{ $produk->kategori->nama_kategori ?? 'Buah Segar' }}</small>
                                    <h5 class="fw-bold text-dark mt-1 mb-2">{{ $produk->nama_produk }}</h5>
                                    <p class="text-muted small mb-3 text-truncate">{{ $produk->deskripsi ?: 'Buah segar kualitas nomor 1 dengan rasa manis alami.' }}</p>
                                    
                                    <div class="d-flex justify-content-between flex-lg-wrap align-items-center">
                                        <div>
                                            <p class="text-dark fs-5 fw-bold mb-0">Rp {{ number_format($produk->harga, 0, ',', '.') }} <span class="fs-6 text-muted font-monospace">/{{ $produk->satuan }}</span></p>
                                        </div>
                                    </div>

                                    <div class="d-grid mt-3">
                                        <a href="{{ route('shop.detail', $produk->id_produk) }}" class="btn border border-secondary rounded-pill px-3 text-primary">
                                            <i class="fa fa-shopping-bag me-2 text-primary"></i> Lihat Detail & Beli
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Tab Per Kategori -->
                @foreach($kategoriList as $kat)
                <div id="tab-kat-{{ $kat->id_kategori }}" class="tab-pane fade show p-0">
                    <div class="row g-4">
                        @php
                            $katProducts = $featuredProducts->where('id_kategori', $kat->id_kategori);
                        @endphp
                        @forelse($katProducts as $produk)
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="rounded position-relative fruite-item border border-secondary">
                                <div class="fruite-img position-relative overflow-hidden">
                                    @php
                                        $imgUrl = asset('fruitables/img/' . ($produk->gambar ?: 'fruite-item-5.jpg'));
                                        if (file_exists(public_path('storage/products/' . $produk->gambar))) {
                                            $imgUrl = asset('storage/products/' . $produk->gambar);
                                        }
                                    @endphp
                                    <img src="{{ $imgUrl }}" class="img-fluid w-100 rounded-top" alt="{{ $produk->nama_produk }}">
                                    @if($produk->stok <= 0)
                                        <span class="badge bg-danger badge-stock">Habis</span>
                                    @endif
                                    @if($produk->active_promo)
                                        <span class="badge-promo"><i class="fas fa-tag me-1"></i> Diskon {{ floatval($produk->active_promo->persen_diskon) }}%</span>
                                    @endif
                                </div>
                                <div class="p-4 border-top-0 rounded-bottom">
                                    <small class="text-secondary fw-bold text-uppercase">{{ $kat->nama_kategori }}</small>
                                    <h5 class="fw-bold text-dark mt-1 mb-2">{{ $produk->nama_produk }}</h5>
                                    <p class="text-dark fs-5 fw-bold mb-2">Rp {{ number_format($produk->harga, 0, ',', '.') }} <span class="fs-6 text-muted">/{{ $produk->satuan }}</span></p>
                                    <a href="{{ route('shop.detail', $produk->id_produk) }}" class="btn border border-secondary rounded-pill px-3 text-primary w-100">
                                        <i class="fa fa-shopping-bag me-2 text-primary"></i> Detail & Beli
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 py-5 text-center text-muted">
                            <i class="fas fa-box-open fa-3x mb-3 text-secondary"></i>
                            <p>Belum ada produk di kategori {{ $kat->nama_kategori }}.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-5 text-center">
                <a href="{{ route('shop') }}" class="btn btn-primary rounded-pill px-5 py-3 fw-bold text-white shadow">
                    Lihat Semua Produk Buah Kami <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- Fruits Shop Section End -->

<!-- Banner Promo Start -->
@if($activePromos->isNotEmpty())
<div class="container-fluid banner bg-secondary my-5">
    <div class="container py-5">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <div class="py-4">
                    <h1 class="display-4 text-white fw-bold">Promo Spesial Buah Segar</h1>
                    <p class="fw-normal display-6 text-dark mb-4">Diskon otomatis langsung di keranjang!</p>
                    <div class="bg-white p-4 rounded-4 shadow-sm mb-4">
                        @foreach($activePromos as $promo)
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <span class="badge bg-danger fs-6 p-2 me-3"><i class="fas fa-gift me-1"></i> Diskon {{ floatval($promo->persen_diskon) }}%</span>
                            <div>
                                <h6 class="mb-1 fw-bold text-dark">{{ $promo->nama_promo }}</h6>
                                <p class="mb-0 text-muted small">
                                    Berlaku untuk: <strong>{{ $promo->produk ? $promo->produk->nama_produk : 'Semua Buah' }}</strong> (Min. beli {{ floatval($promo->min_jumlah) }} {{ $promo->produk ? $promo->produk->satuan : 'kg/item' }})
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('shop') }}" class="banner-btn btn border-2 border-white rounded-pill text-dark py-3 px-5 fw-bold">BELANJA PROMO SEKARANG</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="position-relative">
                    <img src="{{ asset('fruitables/img/baner-1.png') }}" class="img-fluid w-100 rounded" alt="Promo Buah">
                    <div class="d-flex align-items-center justify-content-center bg-white rounded-circle position-absolute" style="width: 140px; height: 140px; top: 0; left: 0;">
                        <h1 style="font-size: 80px;" class="text-danger fw-bold">%</h1>
                        <div class="d-flex flex-column">
                            <span class="h4 mb-0 text-dark fw-bold">Hemat</span>
                            <span class="h5 text-muted mb-0">Hingga 15%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!-- Banner Promo End -->

<!-- Testimonial Section Start -->
<div class="container-fluid testimonial py-5">
    <div class="container py-5">
        <div class="testimonial-header text-center">
            <h4 class="text-primary fw-bold text-uppercase" style="letter-spacing: 2px;">Testimonial Pelanggan</h4>
            <h1 class="display-5 mb-5 text-dark fw-bold">Apa Kata Pelanggan Toko Pak Ridwan?</h1>
        </div>
        <div class="owl-carousel testimonial-carousel">
            <div class="testimonial-item img-border-radius bg-light rounded p-4 shadow-sm">
                <div class="position-relative">
                    <i class="fa fa-quote-right fa-2x text-secondary position-absolute" style="bottom: 30px; right: 0;"></i>
                    <div class="mb-4 pb-4 border-bottom border-secondary">
                        <p class="mb-0">"Buah apel fuji dan jeruk mandor nya segar banget! Diantar tepat waktu ke Rumbai, kemasannya rapi dan buahnya manis semua tanpa ada yang cacat. Recomended banget!"</p>
                    </div>
                    <div class="d-flex align-items-center flex-nowrap">
                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 60px; height: 60px; font-size: 1.3rem;">
                            SZ
                        </div>
                        <div class="ms-1">
                            <h5 class="m-0 pb-1-text fw-bold">Syafiqah Zahra</h5>
                            <p class="m-0 text-muted small">Pelanggan Setia - Sail</p>
                            <div class="d-flex pe-5 text-warning small">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="testimonial-item img-border-radius bg-light rounded p-4 shadow-sm">
                <div class="position-relative">
                    <i class="fa fa-quote-right fa-2x text-secondary position-absolute" style="bottom: 30px; right: 0;"></i>
                    <div class="mb-4 pb-4 border-bottom border-secondary">
                        <p class="mb-0">"Sangat praktis pesan lewat web Toko Buah Pak Ridwan. Diskonnya langsung kepotong otomatis di keranjang. Pelayanan Pak Ridwan dan kasirnya juga ramah."</p>
                    </div>
                    <div class="d-flex align-items-center flex-nowrap">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 60px; height: 60px; font-size: 1.3rem;">
                            PS
                        </div>
                        <div class="ms-1">
                            <h5 class="m-0 pb-1-text fw-bold">Puti Salwa</h5>
                            <p class="m-0 text-muted small">Pelanggan - Rumbai</p>
                            <div class="d-flex pe-5 text-warning small">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="testimonial-item img-border-radius bg-light rounded p-4 shadow-sm">
                <div class="position-relative">
                    <i class="fa fa-quote-right fa-2x text-secondary position-absolute" style="bottom: 30px; right: 0;"></i>
                    <div class="mb-4 pb-4 border-bottom border-secondary">
                        <p class="mb-0">"Parcel buah untuk hantaran acaranya cantik sekali. Buah segar dan warna-warni menarik. Pasti bakal langganan terus di sini."</p>
                    </div>
                    <div class="d-flex align-items-center flex-nowrap">
                        <div class="bg-success rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 60px; height: 60px; font-size: 1.3rem;">
                            ZM
                        </div>
                        <div class="ms-1">
                            <h5 class="m-0 pb-1-text fw-bold">Zulfika Madinatul Ilmi</h5>
                            <p class="m-0 text-muted small">Pelanggan - Sukajadi</p>
                            <div class="d-flex pe-5 text-warning small">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Testimonial Section End -->
@endsection

@extends('layouts.shop')

@section('title', 'Katalog Buah Segar - Toko Buah Pak Ridwan')

@section('content')
<!-- Single Page Header start -->
<div class="container-fluid page-header py-5 single-page-header">
    <h1 class="text-center text-white display-6 fw-bold">Katalog Buah Segar</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Beranda</a></li>
        <li class="breadcrumb-item active text-white">Katalog Buah</li>
    </ol>
</div>
<!-- Single Page Header End -->

<!-- Fruits Shop Start-->
<div class="container-fluid fruite py-5">
    <div class="container py-5">
        <h1 class="mb-4 fw-bold">Pilihan Buah Segar Pak Ridwan</h1>
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="row g-4">
                    <div class="col-xl-3">
                        <!-- Search Form -->
                        <form action="{{ route('shop') }}" method="GET">
                            <div class="input-group w-100 mx-auto d-flex">
                                <input type="search" name="q" value="{{ request('q') }}" class="form-control p-3" placeholder="Cari nama buah..." aria-describedby="search-icon-1">
                                <button type="submit" id="search-icon-1" class="input-group-text p-3 bg-primary text-white border-0"><i class="fa fa-search"></i></button>
                            </div>
                            @if(request('kategori'))
                                <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                            @endif
                            @if(request('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif
                        </form>
                    </div>
                    <div class="col-xl-6"></div>
                    <div class="col-xl-3">
                        <div class="bg-light ps-3 py-3 rounded d-flex justify-content-between mb-4 border">
                            <label for="fruits" class="my-auto fw-bold text-muted small">Urutkan:</label>
                            <form action="{{ route('shop') }}" method="GET" id="sortForm" class="d-inline">
                                @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
                                @if(request('kategori')) <input type="hidden" name="kategori" value="{{ request('kategori') }}"> @endif
                                <select id="fruits" name="sort" class="border-0 form-select-sm bg-light me-3 fw-bold" onchange="document.getElementById('sortForm').submit()">
                                    <option value="" {{ request('sort') == '' ? 'selected' : '' }}>Terbaru</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A - Z</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Sidebar Filter -->
                    <div class="col-lg-3">
                        <div class="row g-4">
                            <!-- Kategori -->
                            <div class="col-lg-12">
                                <div class="mb-3 bg-light p-4 rounded-4 border">
                                    <h4 class="mb-3 fw-bold text-dark">Kategori Buah</h4>
                                    <ul class="list-unstyled fruite-categorie">
                                        <li class="mb-2">
                                            <div class="d-flex justify-content-between fruite-name">
                                                <a href="{{ route('shop', array_merge(request()->except('kategori', 'page'))) }}" class="{{ !request('kategori') ? 'text-primary fw-bold' : 'text-dark' }}">
                                                    <i class="fas fa-apple-alt me-2"></i>Semua Buah
                                                </a>
                                                <span class="badge bg-secondary text-dark">{{ \App\Models\Produk::where('aktif', 1)->count() }}</span>
                                            </div>
                                        </li>
                                        @foreach($kategoriList as $kat)
                                        <li class="mb-2">
                                            <div class="d-flex justify-content-between fruite-name">
                                                <a href="{{ route('shop', array_merge(request()->except('page'), ['kategori' => $kat->id_kategori])) }}" class="{{ request('kategori') == $kat->id_kategori ? 'text-primary fw-bold' : 'text-dark' }}">
                                                    <i class="fas fa-chevron-right me-2 text-muted small"></i>{{ $kat->nama_kategori }}
                                                </a>
                                                <span class="badge bg-light text-muted border">{{ $kat->produk_count }}</span>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <!-- Banner Promo Aktif -->
                            @if($activePromos->isNotEmpty())
                            <div class="col-lg-12">
                                <div class="bg-primary text-white p-4 rounded-4 shadow-sm">
                                    <h5 class="text-white fw-bold mb-3"><i class="fas fa-tags me-2 text-warning"></i>Promo Aktif Hari Ini</h5>
                                    @foreach($activePromos as $promo)
                                    <div class="mb-3 pb-3 border-bottom border-light">
                                        <div class="fw-bold">{{ $promo->nama_promo }}</div>
                                        <div class="small opacity-75">Diskon {{ floatval($promo->persen_diskon) }}% (Min. {{ floatval($promo->min_jumlah) }} {{ $promo->produk ? $promo->produk->satuan : 'item' }})</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Grid Buah -->
                    <div class="col-lg-9">
                        <div class="row g-4 justify-content-start">
                            @forelse($products as $produk)
                            <div class="col-md-6 col-lg-6 col-xl-4">
                                <div class="rounded position-relative fruite-item border border-secondary h-100 d-flex flex-column">
                                    <div class="fruite-img position-relative overflow-hidden">
                                        @php
                                            $imgUrl = asset('fruitables/img/' . ($produk->gambar ?: 'fruite-item-5.jpg'));
                                            if (file_exists(public_path('storage/products/' . $produk->gambar))) {
                                                $imgUrl = asset('storage/products/' . $produk->gambar);
                                            }
                                        @endphp
                                        <img src="{{ $imgUrl }}" class="img-fluid w-100 rounded-top" alt="{{ $produk->nama_produk }}">
                                        
                                        @if($produk->stok <= 0)
                                            <span class="badge bg-danger badge-stock"><i class="fas fa-times-circle me-1"></i> Habis</span>
                                        @else
                                            <span class="badge bg-success badge-stock"><i class="fas fa-check-circle me-1"></i> Stok {{ floatval($produk->stok) }} {{ $produk->satuan }}</span>
                                        @endif

                                        @if($produk->active_promo)
                                            <span class="badge-promo"><i class="fas fa-tag me-1"></i> Diskon {{ floatval($produk->active_promo->persen_diskon) }}%</span>
                                        @endif
                                    </div>

                                    <div class="p-4 border-top-0 rounded-bottom flex-grow-1 d-flex flex-column justify-content-between">
                                        <div>
                                            <small class="text-secondary fw-bold text-uppercase">{{ $produk->kategori->nama_kategori ?? 'Buah Segar' }}</small>
                                            <h5 class="fw-bold text-dark mt-1 mb-2">{{ $produk->nama_produk }}</h5>
                                            <p class="text-muted small mb-3">{{ Str::limit($produk->deskripsi, 65) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-dark fs-5 fw-bold mb-3">Rp {{ number_format($produk->harga, 0, ',', '.') }} <span class="fs-6 text-muted font-monospace">/{{ $produk->satuan }}</span></p>
                                            <a href="{{ route('shop.detail', $produk->id_produk) }}" class="btn border border-secondary rounded-pill px-3 text-primary w-100 fw-bold">
                                                <i class="fa fa-shopping-bag me-2 text-primary"></i> Detail & Beli
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12 py-5 text-center">
                                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                <h4 class="fw-bold text-dark">Buah tidak ditemukan</h4>
                                <p class="text-muted">Coba cari dengan kata kunci lain atau pilih kategori yang berbeda.</p>
                                <a href="{{ route('shop') }}" class="btn btn-outline-primary rounded-pill px-4">Reset Filter</a>
                            </div>
                            @endforelse
                        </div>

                        <!-- Pagination -->
                        <div class="col-12 mt-5">
                            <div class="d-flex justify-content-center">
                                {{ $products->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Fruits Shop End-->
@endsection

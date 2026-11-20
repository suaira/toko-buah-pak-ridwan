@extends('layouts.admin')

@section('title', 'Ubah Buah - Panel Pemilik')
@section('page_title', 'Perbarui Informasi Produk Buah')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-edit text-primary me-2"></i>Edit: {{ $produk->nama_produk }}</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.products.update', $produk->id_produk) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Produk Buah <sup>*</sup></label>
                        <input type="text" name="nama_produk" class="form-control @error('nama_produk') is-invalid @enderror" value="{{ old('nama_produk', $produk->nama_produk) }}" required>
                        @error('nama_produk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Kategori <sup>*</sup></label>
                            <select name="id_kategori" class="form-select @error('id_kategori') is-invalid @enderror" required>
                                @foreach($kategoriList as $kat)
                                    <option value="{{ $kat->id_kategori }}" {{ old('id_kategori', $produk->id_kategori) == $kat->id_kategori ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Satuan Penjualan <sup>*</sup></label>
                            <select name="satuan" class="form-select @error('satuan') is-invalid @enderror" required>
                                <option value="kg" {{ old('satuan', $produk->satuan) == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                <option value="pcs" {{ old('satuan', $produk->satuan) == 'pcs' ? 'selected' : '' }}>Buah / Pack (pcs)</option>
                            </select>
                            @error('satuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Harga per Satuan (Rp) <sup>*</sup></label>
                            <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga', $produk->harga) }}" min="0" required>
                            @error('harga') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Sisa Stok Fisik</label>
                            <input type="text" class="form-control bg-light" value="{{ floatval($produk->stok) }} {{ $produk->satuan }}" readonly>
                            <small class="text-muted">Untuk menambah atau koreksi stok, gunakan tombol <strong>Stok</strong> pada tabel produk.</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Ganti Foto Buah (Opsional)</label>
                        <div class="d-flex align-items-center mb-2">
                            @php
                                $imgUrl = asset('fruitables/img/' . ($produk->gambar ?: 'fruite-item-5.jpg'));
                                if (file_exists(public_path('storage/products/' . $produk->gambar))) {
                                    $imgUrl = asset('storage/products/' . $produk->gambar);
                                }
                            @endphp
                            <img src="{{ $imgUrl }}" class="rounded me-3 border" style="width: 60px; height: 60px; object-fit: cover;" alt="">
                            <input type="file" name="gambar" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi Buah</label>
                        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" name="aktif" value="1" class="form-check-input" id="checkAktif" {{ $produk->aktif ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="checkAktif">Tampilkan buah ini di katalog toko (Aktif)</label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary rounded-pill px-4">Batal</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 text-white fw-bold shadow">
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Tambah Buah Baru - Panel Pemilik')
@section('page_title', 'Tambah Produk Buah Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-plus-circle text-primary me-2"></i>Formulir Buah Baru</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Produk Buah <sup>*</sup></label>
                        <input type="text" name="nama_produk" class="form-control @error('nama_produk') is-invalid @enderror" value="{{ old('nama_produk') }}" placeholder="Contoh: Apel Fuji Super" required>
                        @error('nama_produk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Kategori <sup>*</sup></label>
                            <select name="id_kategori" class="form-select @error('id_kategori') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoriList as $kat)
                                    <option value="{{ $kat->id_kategori }}" {{ old('id_kategori') == $kat->id_kategori ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Satuan Penjualan <sup>*</sup></label>
                            <select name="satuan" class="form-select @error('satuan') is-invalid @enderror" required>
                                <option value="kg" {{ old('satuan') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                <option value="pcs" {{ old('satuan') == 'pcs' ? 'selected' : '' }}>Buah / Pack (pcs)</option>
                            </select>
                            @error('satuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Harga per Satuan (Rp) <sup>*</sup></label>
                            <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga') }}" placeholder="Contoh: 35000" min="0" required>
                            @error('harga') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Stok Awal <sup>*</sup></label>
                            <input type="number" step="0.1" name="stok" class="form-control @error('stok') is-invalid @enderror" value="{{ old('stok', 0) }}" placeholder="Contoh: 50" min="0" required>
                            @error('stok') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Foto Buah</label>
                        <input type="file" name="gambar" class="form-control @error('gambar') is-invalid @enderror" accept="image/*">
                        <small class="text-muted">Format JPG, PNG, atau WEBP maksimal 2MB. Jika kosong, sistem menggunakan foto standar.</small>
                        @error('gambar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi Buah</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Informasi rasa, tingkat kemanisan, asal buah, dll...">{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" name="aktif" value="1" class="form-check-input" id="checkAktif" checked>
                        <label class="form-check-label fw-bold" for="checkAktif">Tampilkan langsung buah ini di katalog toko (Aktif)</label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary rounded-pill px-4">Batal</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 text-white fw-bold shadow">
                            <i class="fas fa-save me-1"></i> Simpan Produk Buah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

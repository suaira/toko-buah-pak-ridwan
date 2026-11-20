@extends('layouts.admin')

@section('title', 'Kategori Buah - Panel Pemilik')
@section('page_title', 'Kelola Kategori Buah')

@section('content')
<div class="row g-4">
    <!-- Form Tambah Kategori -->
    <div class="col-lg-4">
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-plus-circle text-primary me-2"></i>Tambah Kategori</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Kategori <sup>*</sup></label>
                        <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Buah Tropis" required>
                    </div>
                    <button type="submit" class="btn btn-primary rounded-pill w-100 py-2 fw-bold text-white shadow-sm">
                        <i class="fas fa-save me-1"></i> Simpan Kategori
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Kategori -->
    <div class="col-lg-8">
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-folder text-primary me-2"></i>Daftar Kategori Buah</h5>
                <span class="badge bg-light text-muted border">Total: {{ $categories->count() }} Kategori</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 text-center">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-start ps-4">Nama Kategori</th>
                                <th>Jumlah Buah Terdaftar</th>
                                <th>Dibuat Tanggal</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $kat)
                            <tr>
                                <td class="text-start ps-4 fw-bold text-dark">
                                    <i class="fas fa-tag text-secondary me-2"></i>{{ $kat->nama_kategori }}
                                </td>
                                <td>
                                    <span class="badge bg-primary rounded-pill px-3 py-1">{{ $kat->produk_count }} Produk</span>
                                </td>
                                <td class="text-muted small">{{ $kat->created_at->format('d/m/Y') }}</td>
                                <td class="text-end pe-4">
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-circle me-1" data-bs-toggle="modal" data-bs-target="#modalEditKat{{ $kat->id_kategori }}" title="Ubah Nama">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    @if($kat->produk_count == 0)
                                    <form action="{{ route('admin.categories.destroy', $kat->id_kategori) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Hapus Kategori">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif

                                    <!-- Modal Edit Kategori -->
                                    <div class="modal fade" id="modalEditKat{{ $kat->id_kategori }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content rounded-4 border-0 shadow text-start">
                                                <div class="modal-header bg-light">
                                                    <h5 class="modal-title fw-bold">Edit Kategori</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.categories.update', $kat->id_kategori) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body p-4">
                                                        <label class="form-label fw-bold">Nama Kategori:</label>
                                                        <input type="text" name="nama_kategori" class="form-control" value="{{ $kat->nama_kategori }}" required>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary rounded-pill px-4 text-white fw-bold">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-4 text-muted">Belum ada kategori.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

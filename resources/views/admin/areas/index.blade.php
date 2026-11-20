@extends('layouts.admin')

@section('title', 'Area Antar & Ongkir - Panel Pemilik')
@section('page_title', 'Kelola Area Pengantaran & Ongkos Kirim')

@section('content')
<div class="row g-4">
    <!-- Form Tambah Area -->
    <div class="col-lg-4">
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-plus-circle text-primary me-2"></i>Tambah Area Baru</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.areas.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Kecamatan / Wilayah <sup>*</sup></label>
                        <input type="text" name="nama_area" class="form-control" placeholder="Contoh: Rumbai, Sail, Bukit Raya" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tarif Ongkos Kirim (Rp) <sup>*</sup></label>
                        <input type="number" name="ongkir" class="form-control" placeholder="Contoh: 12000" min="0" required>
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" name="aktif" value="1" class="form-check-input" id="checkAreaAktif" checked>
                        <label class="form-check-label fw-bold" for="checkAreaAktif">Aktifkan pilihan area ini saat checkout</label>
                    </div>

                    <button type="submit" class="btn btn-primary rounded-pill w-100 py-2 fw-bold text-white shadow-sm">
                        <i class="fas fa-save me-1"></i> Simpan Area Antar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Area Antar -->
    <div class="col-lg-8">
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-map-marked-alt text-primary me-2"></i>Daftar Area Antar Pekanbaru</h5>
                <span class="badge bg-light text-muted border">Total: {{ $areas->count() }} Area</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 text-center">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-start ps-4">Nama Wilayah / Kecamatan</th>
                                <th>Tarif Ongkir</th>
                                <th>Status Antar</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($areas as $area)
                            <tr>
                                <td class="text-start ps-4 fw-bold text-dark">
                                    <i class="fas fa-map-pin text-danger me-2"></i>{{ $area->nama_area }}
                                </td>
                                <td>
                                    <span class="fw-bold text-dark fs-6">Rp {{ number_format($area->ongkir, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $area->aktif ? 'bg-success' : 'bg-secondary' }} rounded-pill px-3 py-1">
                                        {{ $area->aktif ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-circle me-1" data-bs-toggle="modal" data-bs-target="#modalEditArea{{ $area->id_area }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form action="{{ route('admin.areas.destroy', $area->id_area) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus area ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                    <!-- Modal Edit Area -->
                                    <div class="modal fade" id="modalEditArea{{ $area->id_area }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content rounded-4 border-0 shadow text-start">
                                                <div class="modal-header bg-light">
                                                    <h5 class="modal-title fw-bold">Edit Area & Ongkir</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.areas.update', $area->id_area) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body p-4">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Nama Area:</label>
                                                            <input type="text" name="nama_area" class="form-control" value="{{ $area->nama_area }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Tarif Ongkir (Rp):</label>
                                                            <input type="number" name="ongkir" class="form-control" value="{{ $area->ongkir }}" min="0" required>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="aktif" value="1" class="form-check-input" id="checkA{{ $area->id_area }}" {{ $area->aktif ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="checkA{{ $area->id_area }}">Status Aktif</label>
                                                        </div>
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
                                <td colspan="4" class="py-4 text-muted">Belum ada area antar yang didaftarkan.</td>
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

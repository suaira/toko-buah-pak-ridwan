<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV & Management System - UTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .cv-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 30px;
        }

        .profile-img {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #0d6efd;
        }
    </style>
</head>

<body class="py-5">

    <div class="container">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- PRATINJAU CV (KIRI) -->
            <div class="col-lg-5">
                <div class="cv-card text-center">
                    @if (isset($biodata) && $biodata->foto)
                        <img src="{{ asset('storage/' . $biodata->foto) }}" class="profile-img mb-3" alt="Foto Profil">
                    @else
                        <div
                            class="profile-img mb-3 d-flex align-items-center justify-content-center bg-secondary text-white mx-auto">
                            <span>No Photo</span>
                        </div>
                    @endif

                    <h3 class="fw-bold text-dark">{{ $biodata->nama_lengkap ?? 'Nama Anda' }}</h3>
                    <p class="text-primary fw-semibold mb-1">NIM: {{ $biodata->nim ?? '-' }}</p>
                    <hr>

                    <div class="text-start">
                        <p class="mb-1"><strong>Email:</strong> {{ $biodata->email ?? '-' }}</p>
                        <p class="mb-1"><strong>No HP:</strong> {{ $biodata->no_hp ?? '-' }}</p>
                        <p class="mb-1"><strong>Tanggal Lahir:</strong> {{ $biodata->tanggal_lahir ?? '-' }}</p>
                        <p class="mb-1"><strong>Jenis Kelamin:</strong> {{ $biodata->jenis_kelamin ?? '-' }}</p>
                    </div>

                    <hr>
                    <h5 class="fw-bold text-start text-dark">Daftar Pengalaman</h5>
                    <div class="text-start">
                        @forelse($pengalaman as $item)
                            <div class="mb-3 border-bottom pb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold mb-0 text-primary">{{ $item->posisi }}</h6>
                                    <form action="{{ route('pengalaman.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger py-0"
                                            onclick="return confirm('Hapus pengalaman ini?')">&times;</button>
                                    </form>
                                </div>
                                <small class="text-muted">{{ $item->organisasi }} | {{ $item->periode }}</small>
                                <p class="mb-0 small mt-1">{{ $item->deskripsi }}</p>
                            </div>
                        @empty
                            <p class="text-muted small">Belum ada pengalaman ditambahkan.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- FORM MANAGEMENT CRUD (KANAN) -->
            <div class="col-lg-7">
                <!-- FORM BIODATA & FOTO -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white fw-bold">
                        1. Kelola Biodata & Foto Profil (CRUD)
                    </div>
                    <div class="card-body">
                        <form
                            action="{{ isset($biodata) ? route('biodata.update', $biodata->id) : route('biodata.store') }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @if (isset($biodata))
                                @method('PUT')
                            @endif

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" class="form-control"
                                        value="{{ $biodata->nama_lengkap ?? '' }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">NIM</label>
                                    <input type="text" name="nim" class="form-control"
                                        value="{{ $biodata->nim ?? '' }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="form-control"
                                        value="{{ $biodata->tanggal_lahir ?? '' }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-select" required>
                                        <option value="Perempuan"
                                            {{ isset($biodata) && $biodata->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>
                                            Perempuan</option>
                                        <option value="Laki-laki"
                                            {{ isset($biodata) && $biodata->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>
                                            Laki-laki</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ $biodata->email ?? '' }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">No HP</label>
                                    <input type="text" name="no_hp" class="form-control"
                                        value="{{ $biodata->no_hp ?? '' }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Foto Profil (Bonus Upload Gambar)</label>
                                    <input type="file" name="foto" class="form-control">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3 w-100">Simpan Data Biodata</button>
                        </form>
                    </div>
                </div>

                <!-- FORM PENGALAMAN -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-dark text-white fw-bold">
                        2. Tambah Pengalaman (Bonus Tabel > 1)
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pengalaman.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Posisi / Judul</label>
                                    <input type="text" name="posisi" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Organisasi / Event</label>
                                    <input type="text" name="organisasi" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Periode</label>
                                    <input type="text" name="periode" class="form-control"
                                        placeholder="Contoh: 2024 - 2025" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Deskripsi Singkat</label>
                                    <textarea name="deskripsi" class="form-control" rows="2" required></textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-dark mt-3 w-100">Tambah Pengalaman</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

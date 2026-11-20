@extends('layouts.kasir')

@section('title', 'Detail Pesanan #' . $order->kode_pesanan . ' - Panel Kasir')
@section('page_title', 'Rincian Transaksi Pesanan')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-body p-4 d-flex flex-wrap justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold text-dark mb-1">
                        <i class="fas fa-receipt text-primary me-2"></i>{{ $order->kode_pesanan }}
                    </h3>
                    <p class="text-muted mb-0">
                        Tanggal: <strong>{{ $order->created_at->format('d M Y, H:i') }} WIB</strong> | 
                        Kasir Penanggung Jawab: <strong>{{ $order->kasir->name ?? 'Belum Ditugaskan' }}</strong>
                    </p>
                </div>
                <div class="d-flex align-items-center gap-2 mt-3 mt-md-0">
                    <span class="{{ $order->status_badge }} px-3 py-2 fs-6 rounded-pill">
                        {{ $order->status_label }}
                    </span>
                    <button onclick="window.print()" class="btn btn-outline-dark rounded-pill px-3">
                        <i class="fas fa-print me-1"></i> Cetak Struk
                    </button>
                    <a href="{{ route('kasir.orders') }}" class="btn btn-light border rounded-pill px-3">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Quick Action -->
    <div class="col-12">
        <div class="card border-0 rounded-4 shadow-sm bg-light">
            <div class="card-body p-4">
                <form action="{{ route('kasir.orders.status', $order->id_pesanan) }}" method="POST" class="row g-3 align-items-center">
                    @csrf
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-dark mb-0"><i class="fas fa-tasks text-primary me-2"></i>Perbarui Status Alur:</label>
                    </div>
                    <div class="col-md-5">
                        <select name="status" class="form-select fw-bold">
                            <option value="dikonfirmasi" {{ $order->status == 'dikonfirmasi' ? 'selected' : '' }}>1. Dikonfirmasi (Pesanan Diterima Toko)</option>
                            <option value="diproses" {{ $order->status == 'diproses' ? 'selected' : '' }}>2. Diproses (Sedang Ditimbang & Dikemas)</option>
                            <option value="siap" {{ $order->status == 'siap' ? 'selected' : '' }}>3. Siap Diambil di Toko (Untuk Metode Ambil)</option>
                            <option value="dikirim" {{ $order->status == 'dikirim' ? 'selected' : '' }}>4. Sedang Diantar Kurir (Untuk Metode Antar)</option>
                            <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>5. Selesai (Barang Diterima & Lunas)</option>
                            <option value="ditolak" {{ $order->status == 'ditolak' ? 'selected' : '' }}>6. Ditolak / Dibatalkan Toko</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold text-white shadow-sm">
                            <i class="fas fa-save me-1"></i> Simpan Status Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Rincian Produk Buah -->
    <div class="col-lg-8">
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-shopping-basket text-primary me-2"></i>Daftar Buah yang Dipesan</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Nama Produk Buah</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Potongan Promo</th>
                                <th class="text-end pe-4">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->details as $item)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $item->produk->nama_produk ?? 'Buah' }}</div>
                                    <small class="text-muted">{{ $item->produk->kategori->nama_kategori ?? 'Buah' }}</small>
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
                                <td colspan="4" class="text-end text-success"><i class="fas fa-gift me-1"></i> Diskon Promo:</td>
                                <td class="text-end pe-4 text-success fw-bold">- Rp {{ number_format($order->total_diskon, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td colspan="4" class="text-end text-muted">Ongkir ({{ $order->metode_ambil == 'antar' ? ($order->area->nama_area ?? 'Antar') : 'Ambil di Toko' }}):</td>
                                <td class="text-end pe-4 fw-bold">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="border-top border-2">
                                <td colspan="4" class="text-end fs-5 fw-bold text-dark py-3">Total Tagihan:</td>
                                <td class="text-end pe-4 fs-5 fw-bold text-primary py-3">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Info Penerima & Pengiriman -->
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-map-marked-alt text-primary me-2"></i>Informasi Pemesan & Pengantaran</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Nama Pembeli:</small>
                        <span class="fw-bold text-dark">{{ $order->pelanggan->nama_pelanggan ?? ($order->catatan ?: 'Pembeli Langsung') }}</span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Kontak WhatsApp / No HP:</small>
                        <span class="fw-bold text-dark">{{ $order->pelanggan->no_hp ?? '-' }}</span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Metode Pengambilan:</small>
                        <span class="badge {{ $order->metode_ambil == 'antar' ? 'bg-primary' : 'bg-success' }} text-uppercase">
                            {{ $order->metode_ambil == 'antar' ? 'Antar Kurir ke Rumah' : 'Ambil di Toko' }}
                        </span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Area Antar (Pekanbaru):</small>
                        <span class="fw-bold text-dark">{{ $order->area->nama_area ?? 'Ambil di Toko' }}</span>
                    </div>
                    @if($order->metode_ambil == 'antar')
                    <div class="col-12">
                        <small class="text-muted d-block">Alamat Pengantaran Lengkap:</small>
                        <div class="p-3 bg-light rounded-3 mt-1 text-dark border">{{ $order->alamat_antar ?: 'Alamat belum dicantumkan' }}</div>
                    </div>
                    @endif
                    @if($order->catatan)
                    <div class="col-12">
                        <small class="text-muted d-block">Catatan Pemesan:</small>
                        <div class="p-3 bg-light rounded-3 mt-1 text-muted fst-italic border">{{ $order->catatan }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Validasi Pembayaran Side Card -->
    <div class="col-lg-4">
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-money-check-alt text-primary me-2"></i>Status Pembayaran</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <small class="text-muted d-block">Metode Pembayaran:</small>
                    <span class="fw-bold text-dark fs-6 text-uppercase">
                        <i class="fas {{ optional($order->pembayaran)->metode == 'transfer' ? 'fa-university' : 'fa-hand-holding-usd' }} text-primary me-1"></i>
                        {{ optional($order->pembayaran)->metode ?? '-' }}
                    </span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Status Verifikasi:</small>
                    <span class="badge {{ optional($order->pembayaran)->status == 'lunas' ? 'bg-success' : (optional($order->pembayaran)->status == 'ditolak' ? 'bg-danger' : 'bg-warning text-dark') }} fs-6 px-3 py-2 mt-1">
                        {{ strtoupper(optional($order->pembayaran)->status ?? 'MENUNGGU') }}
                    </span>
                </div>

                @if(optional($order->pembayaran)->bukti_transfer)
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Bukti Transfer Customer:</small>
                        <a href="{{ asset('storage/transfer_proofs/' . $order->pembayaran->bukti_transfer) }}" target="_blank">
                            <img src="{{ asset('storage/transfer_proofs/' . $order->pembayaran->bukti_transfer) }}" class="img-fluid rounded border shadow-sm w-100" style="max-height: 250px; object-fit: contain;" alt="Bukti Transfer">
                        </a>
                        <small class="text-muted d-block mt-1 text-center font-italic">Klik gambar untuk melihat resolusi penuh</small>
                    </div>
                @else
                    @if(optional($order->pembayaran)->metode == 'transfer')
                        <div class="alert alert-secondary small py-2">Customer belum mengunggah foto bukti transfer.</div>
                    @endif
                @endif

                @if($order->pembayaran)
                    <div class="d-grid gap-2 mt-4">
                        @if($order->pembayaran->status !== 'lunas')
                            <form action="{{ route('kasir.payments.status', $order->pembayaran->id_pembayaran) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="lunas">
                                <button type="submit" class="btn btn-success w-100 py-2 rounded-pill fw-bold text-white shadow-sm">
                                    <i class="fas fa-check-circle me-1"></i> Konfirmasi Lunas
                                </button>
                            </form>
                        @endif

                        @if($order->pembayaran->status !== 'ditolak')
                            <form action="{{ route('kasir.payments.status', $order->pembayaran->id_pembayaran) }}" method="POST" onsubmit="return confirm('Tolak pembayaran ini?')">
                                @csrf
                                <input type="hidden" name="status" value="ditolak">
                                <button type="submit" class="btn btn-outline-danger w-100 py-2 rounded-pill">
                                    <i class="fas fa-times-circle me-1"></i> Tolak Pembayaran
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

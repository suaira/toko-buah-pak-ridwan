@extends('layouts.shop')

@section('title', 'Checkout Pembelian - Toko Buah Pak Ridwan')

@section('content')
<!-- Single Page Header start -->
<div class="container-fluid page-header py-5 single-page-header">
    <h1 class="text-center text-white display-6 fw-bold">Checkout Pembelian</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('cart') }}" class="text-white">Keranjang</a></li>
        <li class="breadcrumb-item active text-white">Checkout</li>
    </ol>
</div>
<!-- Single Page Header End -->

<!-- Checkout Page Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <h1 class="mb-4 fw-bold">Selesaikan Pesanan Buah Anda</h1>

        <form action="{{ route('checkout.post') }}" method="POST" id="checkoutForm">
            @csrf
            <div class="row g-5">
                <!-- Data Pengantaran & Pembeli -->
                <div class="col-md-12 col-lg-6 col-xl-7">
                    <div class="bg-light p-4 rounded-4 border shadow-sm mb-4">
                        <h4 class="mb-4 fw-bold text-dark"><i class="fas fa-user-check me-2 text-primary"></i>Informasi Penerima</h4>

                        <div class="row">
                            <div class="col-md-12 form-item mb-3">
                                <label class="form-label my-1 fw-bold">Nama Lengkap Penerima <sup>*</sup></label>
                                <input type="text" name="nama_pelanggan" class="form-control" value="{{ old('nama_pelanggan', $pelanggan->nama_pelanggan ?? Auth::user()->name) }}" readonly>
                            </div>
                        </div>

                        <div class="form-item mb-3">
                            <label class="form-label my-1 fw-bold">Nomor WhatsApp / HP Aktif <sup>*</sup></label>
                            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $pelanggan->no_hp ?? '') }}" placeholder="Contoh: 081234567890" required>
                            <small class="text-muted">Nomor ini digunakan kurir / kasir untuk konfirmasi pesanan.</small>
                        </div>

                        <hr class="my-4">

                        <h4 class="mb-3 fw-bold text-dark"><i class="fas fa-truck-loading me-2 text-primary"></i>Metode Pengambilan</h4>
                        <div class="mb-4">
                            <div class="form-check form-check-inline me-4">
                                <input class="form-check-input" type="radio" name="metode_ambil" id="metodeAntar" value="antar" checked onchange="toggleDeliveryMode()">
                                <label class="form-check-label fw-bold text-dark" for="metodeAntar">
                                    <i class="fas fa-motorcycle text-primary me-1"></i> Antar ke Rumah (Kurir Toko)
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="metode_ambil" id="metodeAmbil" value="ambil" onchange="toggleDeliveryMode()">
                                <label class="form-check-label fw-bold text-dark" for="metodeAmbil">
                                    <i class="fas fa-store text-success me-1"></i> Ambil Sendiri di Toko
                                </label>
                            </div>
                        </div>

                        <!-- Section Antar ke Rumah -->
                        <div id="deliverySection">
                            <div class="form-item mb-3">
                                <label class="form-label my-1 fw-bold">Pilih Wilayah / Area Antar Pekanbaru <sup>*</sup></label>
                                <select name="id_area" id="areaSelect" class="form-select" onchange="updateOngkir()">
                                    <option value="" data-ongkir="0">-- Pilih Kecamatan / Wilayah --</option>
                                    @foreach($deliveryAreas as $area)
                                        <option value="{{ $area->id_area }}" data-ongkir="{{ $area->ongkir }}">
                                            {{ $area->nama_area }} (Ongkir: Rp {{ number_format($area->ongkir, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Tarif ongkir sesuai area pengantaran resmi Toko Pak Ridwan.</small>
                            </div>

                            <div class="form-item mb-3">
                                <label class="form-label my-1 fw-bold">Alamat Lengkap Pengantaran <sup>*</sup></label>
                                <textarea name="alamat_antar" id="alamatAntar" class="form-control" rows="3" placeholder="Nama Jalan, Nomor Rumah, RT/RW, Patokan / Gang...">{{ old('alamat_antar', $pelanggan->alamat ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- Info Ambil di Toko -->
                        <div id="pickupSection" class="d-none">
                            <div class="alert alert-success border-success rounded-3">
                                <i class="fas fa-info-circle me-2"></i> Anda memilih <strong>Ambil Sendiri di Toko</strong> (Bebas Ongkos Kirim).
                                <br><small>Lokasi Toko: Jl. Umban Sari / Sail, Pekanbaru. Pesanan akan disiapkan oleh kasir kami setelah dikonfirmasi.</small>
                            </div>
                        </div>

                        <div class="form-item mb-3">
                            <label class="form-label my-1 fw-bold">Catatan Khusus untuk Toko (Opsional)</label>
                            <input type="text" name="catatan" class="form-control" placeholder="Contoh: Tolak yang terlalu lembek, antar jam 2 siang, dll." maxlength="255">
                        </div>
                    </div>
                </div>

                <!-- Rincian Pesanan & Pembayaran -->
                <div class="col-md-12 col-lg-6 col-xl-5">
                    <div class="bg-light p-4 rounded-4 border shadow-sm">
                        <h4 class="mb-4 fw-bold text-dark"><i class="fas fa-receipt me-2 text-primary"></i>Ringkasan Pesanan</h4>
                        
                        <div class="table-responsive">
                            <table class="table mb-0 align-middle">
                                <thead class="border-bottom">
                                    <tr>
                                        <th scope="col" class="pb-2">Buah</th>
                                        <th scope="col" class="pb-2 text-center">Jumlah</th>
                                        <th scope="col" class="pb-2 text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartCalculations['items'] as $item)
                                    <tr>
                                        <td class="py-2">
                                            <span class="fw-bold text-dark">{{ $item['produk']->nama_produk }}</span>
                                            @if($item['potongan'] > 0)
                                                <small class="badge bg-danger d-block w-fit mt-1">Diskon {{ floatval($item['persen_diskon']) }}%</small>
                                            @endif
                                        </td>
                                        <td class="py-2 text-center">{{ floatval($item['jumlah']) }} {{ $item['produk']->satuan }}</td>
                                        <td class="py-2 text-end fw-bold text-dark">
                                            Rp {{ number_format($item['subtotal_setelah_diskon'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                    
                                    <tr class="border-top">
                                        <td colspan="2" class="py-2 text-muted">Subtotal Buah:</td>
                                        <td class="py-2 text-end fw-bold">Rp {{ number_format($cartCalculations['subtotal'], 0, ',', '.') }}</td>
                                    </tr>
                                    @if($cartCalculations['total_diskon'] > 0)
                                    <tr>
                                        <td colspan="2" class="py-2 text-success"><i class="fas fa-gift me-1"></i> Potongan Promo:</td>
                                        <td class="py-2 text-end text-success fw-bold">- Rp {{ number_format($cartCalculations['total_diskon'], 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td colspan="2" class="py-2 text-muted">Ongkos Kirim:</td>
                                        <td class="py-2 text-end fw-bold text-dark" id="ongkirDisplay">Rp 0</td>
                                    </tr>
                                    <tr class="border-top border-2">
                                        <td colspan="2" class="py-3 fs-5 fw-bold text-dark">Total Pembayaran:</td>
                                        <td class="py-3 text-end fs-5 fw-bold text-primary" id="totalBayarDisplay">
                                            Rp {{ number_format($cartCalculations['total_bersih'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <hr class="my-4">

                        <!-- Pilihan Metode Pembayaran -->
                        <h5 class="fw-bold mb-3 text-dark"><i class="fas fa-wallet me-2 text-primary"></i>Pilih Metode Pembayaran</h5>
                        
                        <div class="form-check mb-3 p-3 bg-white rounded-3 border">
                            <input class="form-check-input ms-0 me-3" type="radio" name="metode_bayar" id="bayarTransfer" value="transfer" checked>
                            <label class="form-check-label fw-bold text-dark" for="bayarTransfer">
                                <i class="fas fa-university text-primary me-2"></i> Transfer Bank Manual
                                <div class="text-muted small fw-normal mt-1">
                                    Transfer ke Rekening Toko (BCA: <strong>823-019-2819</strong> a.n Ridwan). Anda dapat mengunggah struk/bukti transfer setelah checkout.
                                </div>
                            </label>
                        </div>

                        <div class="form-check mb-4 p-3 bg-white rounded-3 border">
                            <input class="form-check-input ms-0 me-3" type="radio" name="metode_bayar" id="bayarCOD" value="cod">
                            <label class="form-check-label fw-bold text-dark" for="bayarCOD">
                                <i class="fas fa-hand-holding-usd text-success me-2"></i> Bayar di Tempat (COD / Tunai)
                                <div class="text-muted small fw-normal mt-1">
                                    Bayar tunai langsung kepada kurir saat buah sampai, atau bayar di kasir saat mengambil di toko.
                                </div>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill text-white fw-bold shadow fs-5">
                            <i class="fas fa-check-circle me-2"></i> Buat Pesanan Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Checkout Page End -->
@endsection

@section('scripts')
<script>
    const subtotalBersih = {{ $cartCalculations['total_bersih'] }};

    function toggleDeliveryMode() {
        const isAntar = document.getElementById('metodeAntar').checked;
        const deliverySection = document.getElementById('deliverySection');
        const pickupSection = document.getElementById('pickupSection');
        const areaSelect = document.getElementById('areaSelect');
        const alamatAntar = document.getElementById('alamatAntar');

        if (isAntar) {
            deliverySection.classList.remove('d-none');
            pickupSection.classList.add('d-none');
            areaSelect.setAttribute('required', 'required');
            alamatAntar.setAttribute('required', 'required');
            updateOngkir();
        } else {
            deliverySection.classList.add('d-none');
            pickupSection.classList.remove('d-none');
            areaSelect.removeAttribute('required');
            alamatAntar.removeAttribute('required');
            
            // Set ongkir 0
            document.getElementById('ongkirDisplay').innerText = 'Rp 0 (Ambil di Toko)';
            document.getElementById('totalBayarDisplay').innerText = 'Rp ' + subtotalBersih.toLocaleString('id-ID');
        }
    }

    function updateOngkir() {
        const isAntar = document.getElementById('metodeAntar').checked;
        if (!isAntar) return;

        const select = document.getElementById('areaSelect');
        const selectedOption = select.options[select.selectedIndex];
        const ongkir = parseFloat(selectedOption.getAttribute('data-ongkir')) || 0;

        document.getElementById('ongkirDisplay').innerText = 'Rp ' + ongkir.toLocaleString('id-ID');
        const total = subtotalBersih + ongkir;
        document.getElementById('totalBayarDisplay').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    // Inisialisasi awal
    document.addEventListener('DOMContentLoaded', function() {
        toggleDeliveryMode();
    });
</script>
@endsection

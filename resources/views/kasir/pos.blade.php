@extends('layouts.kasir')

@section('title', 'Kasir POS Toko - Toko Buah Pak Ridwan')
@section('page_title', 'Kasir POS - Penjualan Langsung di Toko')

@section('content')
<div class="row g-4">
    <!-- Panel Kiri: Katalog Buah Cepat -->
    <div class="col-lg-7 col-xl-8">
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="row g-3 align-items-center mb-4">
                    <div class="col-md-7">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" id="posSearch" class="form-control" placeholder="Ketik nama buah untuk filter cepat..." onkeyup="filterFruit()">
                        </div>
                    </div>
                    <div class="col-md-5 text-end">
                        <span class="text-muted small">Klik buah untuk menambahkan ke kasir</span>
                    </div>
                </div>

                <div class="row g-3" id="fruitGrid" style="max-height: 650px; overflow-y: auto;">
                    @foreach($products as $p)
                    <div class="col-sm-6 col-md-4 fruit-card" data-name="{{ strtolower($p->nama_produk) }}">
                        <div class="card h-100 border rounded-4 overflow-hidden shadow-sm product-clickable" 
                             style="cursor: pointer; transition: transform 0.2s;"
                             onclick="addToPos({{ $p->id_produk }}, '{{ addslashes($p->nama_produk) }}', {{ $p->harga }}, '{{ $p->satuan }}', {{ $p->stok }}, '{{ $p->active_promo ? $p->active_promo->persen_diskon : 0 }}', '{{ $p->active_promo ? $p->active_promo->min_jumlah : 0 }}')">
                            
                            <div class="position-relative">
                                @php
                                    $imgUrl = asset('fruitables/img/' . ($p->gambar ?: 'fruite-item-5.jpg'));
                                    if (file_exists(public_path('storage/products/' . $p->gambar))) {
                                        $imgUrl = asset('storage/products/' . $p->gambar);
                                    }
                                @endphp
                                <img src="{{ $imgUrl }}" class="card-img-top" style="height: 120px; object-fit: cover;" alt="{{ $p->nama_produk }}">
                                
                                @if($p->active_promo)
                                    <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                                        Diskon {{ floatval($p->active_promo->persen_diskon) }}%
                                    </span>
                                @endif
                                <span class="badge bg-success position-absolute bottom-0 start-0 m-2">
                                    Stok: {{ floatval($p->stok) }} {{ $p->satuan }}
                                </span>
                            </div>

                            <div class="card-body p-3">
                                <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $p->nama_produk }}</h6>
                                <div class="text-primary fw-bold">Rp {{ number_format($p->harga, 0, ',', '.') }} <small class="text-muted">/{{ $p->satuan }}</small></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Panel Kanan: Struk Kasir POS Real-Time -->
    <div class="col-lg-5 col-xl-4">
        <div class="card border-0 rounded-4 shadow-sm sticky-top" style="top: 20px;">
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-shopping-cart text-success me-2"></i>Nota Transaksi</h5>
                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" onclick="clearPos()">
                    <i class="fas fa-trash me-1"></i> Reset
                </button>
            </div>

            <form action="{{ route('kasir.pos.post') }}" method="POST" id="posForm">
                @csrf
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nama Pembeli (Opsional):</label>
                        <input type="text" name="nama_pembeli" class="form-control form-control-sm" placeholder="Pelanggan Offline">
                    </div>

                    <!-- Item List -->
                    <div id="posItemList" style="max-height: 280px; overflow-y: auto;" class="border rounded-3 p-2 mb-3 bg-light">
                        <div id="emptyPosNotice" class="text-center py-4 text-muted small">
                            <i class="fas fa-hand-pointer fa-2x mb-2 text-secondary opacity-50"></i>
                            <div>Belum ada buah dipilih. Klik buah pada katalog di sebelah kiri.</div>
                        </div>
                    </div>

                    <!-- Ringkasan Total & Diskon -->
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-1 small">
                            <span class="text-muted">Subtotal:</span>
                            <span class="fw-bold" id="posSubtotalText">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small text-success">
                            <span>Potongan Diskon:</span>
                            <span class="fw-bold" id="posDiskonText">- Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 border-top pt-2">
                            <h5 class="fw-bold text-dark mb-0">Total Bayar:</h5>
                            <h4 class="fw-bold text-primary mb-0" id="posGrandTotalText">Rp 0</h4>
                        </div>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Metode Pembayaran:</label>
                        <div class="d-flex gap-2">
                            <input type="radio" class="btn-check" name="metode_bayar" id="posPayTunai" value="tunai" checked onchange="calculateChange()">
                            <label class="btn btn-outline-success flex-grow-1 fw-bold btn-sm py-2" for="posPayTunai">
                                <i class="fas fa-money-bill-wave me-1"></i> Tunai (Cash)
                            </label>

                            <input type="radio" class="btn-check" name="metode_bayar" id="posPayTransfer" value="transfer" onchange="calculateChange()">
                            <label class="btn btn-outline-primary flex-grow-1 fw-bold btn-sm py-2" for="posPayTransfer">
                                <i class="fas fa-qrcode me-1"></i> QRIS / Transfer
                            </label>
                        </div>
                    </div>

                    <!-- Input Uang Diterima & Kembalian -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Uang Diterima (Rp):</label>
                        <input type="number" name="nominal_bayar" id="posNominalBayar" class="form-control form-control-lg fw-bold text-success fs-4" placeholder="0" onkeyup="calculateChange()" required>
                    </div>

                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3 mb-4 border">
                        <span class="fw-bold text-muted small">Kembalian:</span>
                        <h5 class="fw-bold text-dark mb-0" id="posKembalianText">Rp 0</h5>
                    </div>

                    <button type="submit" id="btnSubmitPos" class="btn btn-success w-100 py-3 rounded-pill fw-bold text-white shadow fs-5" disabled>
                        <i class="fas fa-check-circle me-2"></i> Proses & Selesaikan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let posItems = {}; // { id: { name, price, unit, stock, promoPercent, promoMin, qty } }

    function filterFruit() {
        const query = document.getElementById('posSearch').value.toLowerCase();
        document.querySelectorAll('.fruit-card').forEach(card => {
            const name = card.getAttribute('data-name');
            card.style.display = name.includes(query) ? '' : 'none';
        });
    }

    function addToPos(id, name, price, unit, stock, promoPercent, promoMin) {
        if (stock <= 0) {
            alert('Stok buah ini habis.');
            return;
        }

        if (posItems[id]) {
            if (posItems[id].qty + 1 > stock) {
                alert('Mencapai batas stok yang tersedia (' + stock + ' ' + unit + ')');
                return;
            }
            posItems[id].qty += 1;
        } else {
            posItems[id] = {
                id: id,
                name: name,
                price: parseFloat(price),
                unit: unit,
                stock: parseFloat(stock),
                promoPercent: parseFloat(promoPercent) || 0,
                promoMin: parseFloat(promoMin) || 0,
                qty: 1
            };
        }
        renderPos();
    }

    function changePosQty(id, delta) {
        if (!posItems[id]) return;
        let newQty = posItems[id].qty + delta;
        if (newQty <= 0) {
            delete posItems[id];
        } else if (newQty > posItems[id].stock) {
            alert('Melebihi sisa stok (' + posItems[id].stock + ' ' + posItems[id].unit + ')');
            return;
        } else {
            posItems[id].qty = newQty;
        }
        renderPos();
    }

    function clearPos() {
        posItems = {};
        renderPos();
    }

    function renderPos() {
        const container = document.getElementById('posItemList');
        const emptyNotice = document.getElementById('emptyPosNotice');
        const keys = Object.keys(posItems);

        if (keys.length === 0) {
            container.innerHTML = '<div id="emptyPosNotice" class="text-center py-4 text-muted small"><i class="fas fa-hand-pointer fa-2x mb-2 text-secondary opacity-50"></i><div>Belum ada buah dipilih. Klik buah pada katalog di sebelah kiri.</div></div>';
            document.getElementById('posSubtotalText').innerText = 'Rp 0';
            document.getElementById('posDiskonText').innerText = '- Rp 0';
            document.getElementById('posGrandTotalText').innerText = 'Rp 0';
            document.getElementById('posKembalianText').innerText = 'Rp 0';
            document.getElementById('btnSubmitPos').disabled = true;
            return;
        }

        let html = '';
        let subtotal = 0;
        let totalDiskon = 0;
        let index = 0;

        keys.forEach(id => {
            const item = posItems[id];
            const itemSubtotal = item.price * item.qty;
            let diskon = 0;
            if (item.promoPercent > 0 && item.qty >= item.promoMin) {
                diskon = (itemSubtotal * item.promoPercent) / 100;
            }
            const netSubtotal = itemSubtotal - diskon;

            subtotal += itemSubtotal;
            totalDiskon += diskon;

            html += `
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom bg-white p-2 rounded mb-1">
                    <div class="flex-grow-1 me-2 overflow-hidden">
                        <div class="fw-bold text-dark text-truncate small">${item.name}</div>
                        <small class="text-muted">Rp ${item.price.toLocaleString('id-ID')} / ${item.unit}</small>
                        ${diskon > 0 ? `<span class="badge bg-danger ms-1 small">-${item.promoPercent}% (-Rp ${diskon.toLocaleString('id-ID')})</span>` : ''}
                    </div>
                    <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 rounded-circle" onclick="changePosQty(${id}, -1)">-</button>
                        <span class="mx-2 fw-bold text-dark">${item.qty}</span>
                        <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 rounded-circle" onclick="changePosQty(${id}, 1)">+</button>
                        <span class="ms-3 fw-bold text-dark text-end" style="min-width: 75px;">Rp ${netSubtotal.toLocaleString('id-ID')}</span>
                    </div>

                    <!-- Hidden input for form submission -->
                    <input type="hidden" name="items[${index}][id_produk]" value="${item.id}">
                    <input type="hidden" name="items[${index}][jumlah]" value="${item.qty}">
                </div>
            `;
            index++;
        });

        container.innerHTML = html;

        const grandTotal = subtotal - totalDiskon;
        document.getElementById('posSubtotalText').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        document.getElementById('posDiskonText').innerText = '- Rp ' + totalDiskon.toLocaleString('id-ID');
        document.getElementById('posGrandTotalText').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');

        // Set nominal uang default jika transfer
        if (document.getElementById('posPayTransfer').checked) {
            document.getElementById('posNominalBayar').value = grandTotal;
        }

        document.getElementById('btnSubmitPos').disabled = false;
        calculateChange();
    }

    function calculateChange() {
        const grandTotalText = document.getElementById('posGrandTotalText').innerText.replace(/[^0-9]/g, '');
        const grandTotal = parseFloat(grandTotalText) || 0;
        const bayar = parseFloat(document.getElementById('posNominalBayar').value) || 0;

        const kembalian = bayar - grandTotal;
        const kembalianEl = document.getElementById('posKembalianText');

        if (kembalian >= 0) {
            kembalianEl.innerText = 'Rp ' + kembalian.toLocaleString('id-ID');
            kembalianEl.className = 'fw-bold text-success mb-0';
            document.getElementById('btnSubmitPos').disabled = grandTotal === 0;
        } else {
            kembalianEl.innerText = 'Kurang Rp ' + Math.abs(kembalian).toLocaleString('id-ID');
            kembalianEl.className = 'fw-bold text-danger mb-0';
            document.getElementById('btnSubmitPos').disabled = true;
        }
    }
</script>
@endsection

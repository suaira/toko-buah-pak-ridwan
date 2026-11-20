@extends('layouts.shop')

@section('title', 'Tentang & Kontak - Toko Buah Pak Ridwan')

@section('content')
<!-- Single Page Header start -->
<div class="container-fluid page-header py-5 single-page-header">
    <h1 class="text-center text-white display-6 fw-bold">Tentang & Kontak Toko</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Beranda</a></li>
        <li class="breadcrumb-item active text-white">Kontak</li>
    </ol>
</div>
<!-- Single Page Header End -->

<!-- Contact Start -->
<div class="container-fluid contact py-5">
    <div class="container py-5">
        <div class="p-5 bg-light rounded-4 border shadow-sm">
            <div class="row g-4">
                <div class="col-12">
                    <div class="text-center mx-auto" style="max-width: 700px;">
                        <h1 class="text-primary fw-bold">Hubungi Toko Buah Pak Ridwan</h1>
                        <p class="mb-4 text-muted">
                            Kami selalu siap menyajikan buah-buahan kualitas terbaik untuk masyarakat Pekanbaru dan sekitarnya. Jika ada pertanyaan mengenai pesanan, ketersediaan buah langka, parsel buah pesanan khusus, atau kerjasama pasokan, silakan hubungi kami.
                        </p>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="h-100 rounded-4 overflow-hidden border">
                        <iframe class="rounded w-100" 
                        style="height: 400px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127672.24726265787!2d101.37896472097654!3d0.5335048128456227!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d5ac0855555555%3A0x70529ef8a1db5b27!2sPoliteknik%20Caltex%20Riau!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid" 
                        loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
                <div class="col-lg-7">
                    <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Terima kasih! Pesan Anda telah terkirim kepada Pak Ridwan.'); this.reset();">
                        <input type="text" class="w-100 form-control border-0 py-3 mb-4" placeholder="Nama Lengkap Anda" required>
                        <input type="email" class="w-100 form-control border-0 py-3 mb-4" placeholder="Alamat Email Anda" required>
                        <input type="text" class="w-100 form-control border-0 py-3 mb-4" placeholder="Nomor Telepon / WhatsApp" required>
                        <textarea class="w-100 form-control border-0 mb-4" rows="5" cols="10" placeholder="Pesan atau pertanyaan seputar pesanan buah..." required></textarea>
                        <button class="w-100 btn form-control border-secondary py-3 bg-white text-primary fw-bold" type="submit">Kirim Pesan Sekarang</button>
                    </form>
                </div>
                <div class="col-lg-5">
                    <div class="d-flex p-4 rounded-4 mb-4 bg-white border shadow-sm">
                        <i class="fas fa-map-marker-alt fa-2x text-primary me-4"></i>
                        <div>
                            <h5 class="fw-bold text-dark">Alamat Toko</h5>
                            <p class="mb-0 text-muted">Jl. Umban Sari / Sail, Kec. Rumbai, Kota Pekanbaru, Riau 28265</p>
                        </div>
                    </div>
                    <div class="d-flex p-4 rounded-4 mb-4 bg-white border shadow-sm">
                        <i class="fas fa-envelope fa-2x text-primary me-4"></i>
                        <div>
                            <h5 class="fw-bold text-dark">Email Resmi</h5>
                            <p class="mb-0 text-muted">admin@tokobuahpakridwan.com</p>
                        </div>
                    </div>
                    <div class="d-flex p-4 rounded-4 mb-4 bg-white border shadow-sm">
                        <i class="fa fa-phone-alt fa-2x text-primary me-4"></i>
                        <div>
                            <h5 class="fw-bold text-dark">Telepon / WhatsApp</h5>
                            <p class="mb-0 text-muted">+62 812-3456-7890 (Layanan Cepat)</p>
                        </div>
                    </div>
                    <div class="d-flex p-4 rounded-4 bg-white border shadow-sm">
                        <i class="fa fa-clock fa-2x text-primary me-4"></i>
                        <div>
                            <h5 class="fw-bold text-dark">Jam Operasional</h5>
                            <p class="mb-0 text-muted">Senin - Minggu: 07.30 - 21.30 WIB</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contact End -->
@endsection

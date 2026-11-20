<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabel Pelanggan
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id('id_pelanggan');
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('nama_pelanggan');
            $table->string('no_hp', 15);
            $table->text('alamat')->nullable();
            $table->timestamps();
        });

        // 2. Tabel Kategori
        Schema::create('kategori', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->string('nama_kategori')->unique();
            $table->timestamps();
        });

        // 3. Tabel Produk
        Schema::create('produk', function (Blueprint $table) {
            $table->id('id_produk');
            $table->foreignId('id_kategori')->constrained('kategori', 'id_kategori')->onDelete('restrict');
            $table->string('nama_produk');
            $table->text('deskripsi')->nullable();
            $table->enum('satuan', ['kg', 'pcs'])->default('kg');
            $table->decimal('harga', 12, 2);
            $table->decimal('stok', 10, 2)->default(0);
            $table->string('gambar')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        // 4. Tabel Promo
        Schema::create('promo', function (Blueprint $table) {
            $table->id('id_promo');
            $table->string('nama_promo');
            $table->foreignId('id_produk')->nullable()->constrained('produk', 'id_produk')->onDelete('cascade');
            $table->decimal('persen_diskon', 5, 2);
            $table->decimal('min_jumlah', 10, 2)->default(0);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        // 5. Tabel Area Antar
        Schema::create('area_antar', function (Blueprint $table) {
            $table->id('id_area');
            $table->string('nama_area');
            $table->decimal('ongkir', 12, 2);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        // 6. Tabel Keranjang
        Schema::create('keranjang', function (Blueprint $table) {
            $table->id('id_keranjang');
            $table->foreignId('id_pelanggan')->constrained('pelanggan', 'id_pelanggan')->onDelete('cascade');
            $table->foreignId('id_produk')->constrained('produk', 'id_produk')->onDelete('cascade');
            $table->decimal('jumlah', 10, 2);
            $table->timestamps();
            $table->unique(['id_pelanggan', 'id_produk']);
        });

        // 7. Tabel Pesanan
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('id_pesanan');
            $table->string('kode_pesanan')->unique();
            $table->foreignId('id_pelanggan')->nullable()->constrained('pelanggan', 'id_pelanggan')->onDelete('set null');
            $table->foreignId('id_kasir')->nullable()->constrained('users', 'id')->onDelete('set null');
            $table->enum('sumber', ['online', 'toko'])->default('online');
            $table->enum('metode_ambil', ['ambil', 'antar'])->default('antar');
            $table->foreignId('id_area')->nullable()->constrained('area_antar', 'id_area')->onDelete('set null');
            $table->text('alamat_antar')->nullable();
            $table->string('catatan')->nullable();
            $table->enum('status', ['menunggu', 'dikonfirmasi', 'diproses', 'siap', 'dikirim', 'selesai', 'dibatalkan', 'ditolak'])->default('menunggu');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('total_diskon', 12, 2)->default(0);
            $table->decimal('ongkir', 12, 2)->default(0);
            $table->decimal('total_bayar', 12, 2);
            $table->timestamps();
        });

        // 8. Tabel Detail Pesanan
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id('id_detail');
            $table->foreignId('id_pesanan')->constrained('pesanan', 'id_pesanan')->onDelete('cascade');
            $table->foreignId('id_produk')->constrained('produk', 'id_produk')->onDelete('restrict');
            $table->decimal('jumlah', 10, 2);
            $table->decimal('harga_satuan', 12, 2);
            $table->foreignId('id_promo')->nullable()->constrained('promo', 'id_promo')->onDelete('set null');
            $table->decimal('persen_diskon', 5, 2)->default(0);
            $table->decimal('potongan', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        // 9. Tabel Pembayaran
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->foreignId('id_pesanan')->unique()->constrained('pesanan', 'id_pesanan')->onDelete('cascade');
            $table->enum('metode', ['transfer', 'cod', 'tunai'])->default('transfer');
            $table->decimal('jumlah_bayar', 12, 2);
            $table->string('bukti_transfer')->nullable();
            $table->enum('status', ['menunggu', 'lunas', 'ditolak'])->default('menunggu');
            $table->foreignId('id_kasir')->nullable()->constrained('users', 'id')->onDelete('set null');
            $table->dateTime('dibayar_pada')->nullable();
            $table->timestamps();
        });

        // 10. Tabel Riwayat Stok
        Schema::create('riwayat_stok', function (Blueprint $table) {
            $table->id('id_riwayat');
            $table->foreignId('id_produk')->constrained('produk', 'id_produk')->onDelete('cascade');
            $table->enum('jenis', ['masuk', 'pesanan', 'batal', 'koreksi']);
            $table->decimal('jumlah', 10, 2);
            $table->string('keterangan')->nullable();
            $table->foreignId('user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_stok');
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('detail_pesanan');
        Schema::dropIfExists('pesanan');
        Schema::dropIfExists('keranjang');
        Schema::dropIfExists('area_antar');
        Schema::dropIfExists('promo');
        Schema::dropIfExists('produk');
        Schema::dropIfExists('kategori');
        Schema::dropIfExists('pelanggan');
    }
};

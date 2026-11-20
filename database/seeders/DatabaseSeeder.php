<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Users
        $admin = DB::table('users')->insertGetId([
            'name' => 'Pak Ridwan (Owner)',
            'email' => 'admin@tokobuahpakridwan.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'aktif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $kasir = DB::table('users')->insertGetId([
            'name' => 'Siti Kasir',
            'email' => 'kasir@tokobuahpakridwan.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'aktif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $cust1 = DB::table('users')->insertGetId([
            'name' => 'Syafiqah Zahra',
            'email' => 'pika@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'aktif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $cust2 = DB::table('users')->insertGetId([
            'name' => 'Puti Salwa',
            'email' => 'salwa@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'aktif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $cust3 = DB::table('users')->insertGetId([
            'name' => 'Zulfika Madinatul Ilmi',
            'email' => 'ilmi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'aktif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Pelanggan
        $pel1 = DB::table('pelanggan')->insertGetId([
            'user_id' => $cust1,
            'nama_pelanggan' => 'Syafiqah Zahra',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Melati No. 12, Sail, Pekanbaru',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $pel2 = DB::table('pelanggan')->insertGetId([
            'user_id' => $cust2,
            'nama_pelanggan' => 'Puti Salwa',
            'no_hp' => '081234567891',
            'alamat' => 'Jl. Umban Sari No. 5, Rumbai, Pekanbaru',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $pel3 = DB::table('pelanggan')->insertGetId([
            'user_id' => $cust3,
            'nama_pelanggan' => 'Zulfika Madinatul Ilmi',
            'no_hp' => '081234567892',
            'alamat' => 'Jl. Sudirman No. 88, Pekanbaru Kota',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Kategori
        $katLokal = DB::table('kategori')->insertGetId([
            'nama_kategori' => 'Buah Lokal',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $katImpor = DB::table('kategori')->insertGetId([
            'nama_kategori' => 'Buah Impor',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $katPaket = DB::table('kategori')->insertGetId([
            'nama_kategori' => 'Paket Buah',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $katOrganik = DB::table('kategori')->insertGetId([
            'nama_kategori' => 'Buah Organik',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Area Antar
        $areas = [
            ['nama_area' => 'Pekanbaru Kota', 'ongkir' => 8000],
            ['nama_area' => 'Sukajadi', 'ongkir' => 10000],
            ['nama_area' => 'Rumbai', 'ongkir' => 10000],
            ['nama_area' => 'Sail', 'ongkir' => 12000],
            ['nama_area' => 'Bukit Raya', 'ongkir' => 12000],
            ['nama_area' => 'Tampan / Binawidya', 'ongkir' => 15000],
            ['nama_area' => 'Marpoyan Damai', 'ongkir' => 12000],
            ['nama_area' => 'Payung Sekaki', 'ongkir' => 15000],
            ['nama_area' => 'Tenayan Raya', 'ongkir' => 18000],
        ];
        foreach ($areas as $area) {
            DB::table('area_antar')->insert(array_merge($area, [
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // 5. Produk Buah
        $p1 = DB::table('produk')->insertGetId([
            'id_kategori' => $katImpor,
            'nama_produk' => 'Apel Fuji Super',
            'deskripsi' => 'Apel Fuji import segar dengan tekstur renyah dan rasa manis alami berair.',
            'satuan' => 'kg',
            'harga' => 38000,
            'stok' => 50,
            'gambar' => 'fruite-item-5.jpg',
            'aktif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $p2 = DB::table('produk')->insertGetId([
            'id_kategori' => $katImpor,
            'nama_produk' => 'Jeruk Mandarin Manis',
            'deskripsi' => 'Jeruk mandarin segar manis dengan bulir tebal dan kaya vitamin C.',
            'satuan' => 'kg',
            'harga' => 32000,
            'stok' => 65,
            'gambar' => 'fruite-item-1.jpg',
            'aktif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $p3 = DB::table('produk')->insertGetId([
            'id_kategori' => $katLokal,
            'nama_produk' => 'Pisang Cavendish Segar',
            'deskripsi' => 'Pisang Cavendish kuning mulus bernutrisi tinggi, manis dan mengenyangkan.',
            'satuan' => 'kg',
            'harga' => 22000,
            'stok' => 40,
            'gambar' => 'fruite-item-3.jpg',
            'aktif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $p4 = DB::table('produk')->insertGetId([
            'id_kategori' => $katImpor,
            'nama_produk' => 'Anggur Red Globe',
            'deskripsi' => 'Anggur merah impor manis tanpa biji, renyah dan segar.',
            'satuan' => 'kg',
            'harga' => 55000,
            'stok' => 30,
            'gambar' => 'fruite-item-2.jpg',
            'aktif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $p5 = DB::table('produk')->insertGetId([
            'id_kategori' => $katLokal,
            'nama_produk' => 'Mangga Harum Manis',
            'deskripsi' => 'Mangga lokal pilihan dengan aroma harum semerbak dan rasa legit manis.',
            'satuan' => 'kg',
            'harga' => 28000,
            'stok' => 45,
            'gambar' => 'fruite-item-4.jpg',
            'aktif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $p6 = DB::table('produk')->insertGetId([
            'id_kategori' => $katLokal,
            'nama_produk' => 'Alpukat Mentega Super',
            'deskripsi' => 'Alpukat mentega daging tebal legit, bebas serat dan gurih manis alami.',
            'satuan' => 'kg',
            'harga' => 35000,
            'stok' => 38,
            'gambar' => 'fruite-item-6.jpg',
            'aktif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $p7 = DB::table('produk')->insertGetId([
            'id_kategori' => $katLokal,
            'nama_produk' => 'Semangka Merah Non-Biji',
            'deskripsi' => 'Semangka merah segar manis, sangat menyegarkan di cuaca terik.',
            'satuan' => 'kg',
            'harga' => 12000,
            'stok' => 80,
            'gambar' => 'vegetable-item-6.jpg',
            'aktif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $p8 = DB::table('produk')->insertGetId([
            'id_kategori' => $katOrganik,
            'nama_produk' => 'Stroberi Organik Ciwidey',
            'deskripsi' => 'Stroberi segar dipetik langsung dari kebun organik tanpa pestisida kimia.',
            'satuan' => 'pcs',
            'harga' => 25000,
            'stok' => 25,
            'gambar' => 'fruite-item-2.jpg',
            'aktif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $p9 = DB::table('produk')->insertGetId([
            'id_kategori' => $katLokal,
            'nama_produk' => 'Melon Madu Golden',
            'deskripsi' => 'Melon madu daging renyah dengan aroma harum madu khas pilihan.',
            'satuan' => 'kg',
            'harga' => 24000,
            'stok' => 35,
            'gambar' => 'vegetable-item-3.jpg',
            'aktif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $p10 = DB::table('produk')->insertGetId([
            'id_kategori' => $katPaket,
            'nama_produk' => 'Paket Parsel Buah Sehat',
            'deskripsi' => 'Paket aneka ragam buah premium kemasan parsel cantik untuk hantaran & kado sehat.',
            'satuan' => 'pcs',
            'harga' => 150000,
            'stok' => 15,
            'gambar' => 'featur-1.jpg',
            'aktif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 6. Promo
        $pr1 = DB::table('promo')->insertGetId([
            'nama_promo' => 'Diskon Beli 3 kg Apel Fuji',
            'id_produk' => $p1,
            'persen_diskon' => 10.00,
            'min_jumlah' => 3.00,
            'tanggal_mulai' => '2026-11-01',
            'tanggal_selesai' => '2026-12-31',
            'aktif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $pr2 = DB::table('promo')->insertGetId([
            'nama_promo' => 'Promo Jeruk Mandarin Manis',
            'id_produk' => $p2,
            'persen_diskon' => 12.50,
            'min_jumlah' => 2.00,
            'tanggal_mulai' => '2026-11-01',
            'tanggal_selesai' => '2026-12-31',
            'aktif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $pr3 = DB::table('promo')->insertGetId([
            'nama_promo' => 'Gebyar Diskon Semua Buah',
            'id_produk' => null,
            'persen_diskon' => 5.00,
            'min_jumlah' => 5.00,
            'tanggal_mulai' => '2026-11-01',
            'tanggal_selesai' => '2026-12-31',
            'aktif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 7. Riwayat Stok Awal
        foreach ([$p1, $p2, $p3, $p4, $p5, $p6] as $pid) {
            DB::table('riwayat_stok')->insert([
                'id_produk' => $pid,
                'jenis' => 'masuk',
                'jumlah' => 50,
                'keterangan' => 'Stok awal toko buah',
                'user_id' => $admin,
                'created_at' => now(),
            ]);
        }

        // 8. Sample Pesanan Selesai
        $pesanan1 = DB::table('pesanan')->insertGetId([
            'kode_pesanan' => 'PSN-20261118-001',
            'id_pelanggan' => $pel1,
            'id_kasir' => $kasir,
            'sumber' => 'online',
            'metode_ambil' => 'antar',
            'id_area' => 4,
            'alamat_antar' => 'Jl. Melati No. 12, Sail, Pekanbaru',
            'catatan' => 'Tolong pilihkan yang matang',
            'status' => 'selesai',
            'subtotal' => 114000,
            'total_diskon' => 11400,
            'ongkir' => 12000,
            'total_bayar' => 114600,
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);

        DB::table('detail_pesanan')->insert([
            'id_pesanan' => $pesanan1,
            'id_produk' => $p1,
            'jumlah' => 3,
            'harga_satuan' => 38000,
            'id_promo' => $pr1,
            'persen_diskon' => 10,
            'potongan' => 11400,
            'subtotal' => 102600,
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);

        DB::table('pembayaran')->insert([
            'id_pesanan' => $pesanan1,
            'metode' => 'transfer',
            'jumlah_bayar' => 114600,
            'bukti_transfer' => 'sample_transfer.jpg',
            'status' => 'lunas',
            'id_kasir' => $kasir,
            'dibayar_pada' => now()->subDays(2),
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);
    }
}

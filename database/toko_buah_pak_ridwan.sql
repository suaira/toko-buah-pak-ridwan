-- SQL Dump untuk Toko Buah Pak Ridwan
-- Dapat langsung di-import di HeidiSQL
-- Target DBMS: MySQL / MariaDB (Laragon)

CREATE DATABASE IF NOT EXISTS `toko_buah_pak_ridwan` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `toko_buah_pak_ridwan`;

-- --------------------------------------------------------
-- 1. Table structure for table `users`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `riwayat_stok`;
DROP TABLE IF EXISTS `pembayaran`;
DROP TABLE IF EXISTS `detail_pesanan`;
DROP TABLE IF EXISTS `pesanan`;
DROP TABLE IF EXISTS `keranjang`;
DROP TABLE IF EXISTS `promo`;
DROP TABLE IF EXISTS `produk`;
DROP TABLE IF EXISTS `kategori`;
DROP TABLE IF EXISTS `area_antar`;
DROP TABLE IF EXISTS `pelanggan`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','kasir','customer') NOT NULL DEFAULT 'customer',
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 2. Table structure for table `pelanggan`
-- --------------------------------------------------------
CREATE TABLE `pelanggan` (
  `id_pelanggan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nama_pelanggan` varchar(255) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pelanggan`),
  UNIQUE KEY `pelanggan_user_id_unique` (`user_id`),
  CONSTRAINT `pelanggan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 3. Table structure for table `kategori`
-- --------------------------------------------------------
CREATE TABLE `kategori` (
  `id_kategori` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_kategori`),
  UNIQUE KEY `kategori_nama_kategori_unique` (`nama_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 4. Table structure for table `produk`
-- --------------------------------------------------------
CREATE TABLE `produk` (
  `id_produk` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_kategori` bigint(20) UNSIGNED NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `satuan` enum('kg','pcs') NOT NULL DEFAULT 'kg',
  `harga` decimal(12,2) NOT NULL,
  `stok` decimal(10,2) NOT NULL DEFAULT 0.00,
  `gambar` varchar(255) DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_produk`),
  KEY `produk_id_kategori_foreign` (`id_kategori`),
  CONSTRAINT `produk_id_kategori_foreign` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 5. Table structure for table `promo`
-- --------------------------------------------------------
CREATE TABLE `promo` (
  `id_promo` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_promo` varchar(255) NOT NULL,
  `id_produk` bigint(20) UNSIGNED DEFAULT NULL,
  `persen_diskon` decimal(5,2) NOT NULL,
  `min_jumlah` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_promo`),
  KEY `promo_id_produk_foreign` (`id_produk`),
  CONSTRAINT `promo_id_produk_foreign` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 6. Table structure for table `area_antar`
-- --------------------------------------------------------
CREATE TABLE `area_antar` (
  `id_area` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_area` varchar(255) NOT NULL,
  `ongkir` decimal(12,2) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_area`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 7. Table structure for table `keranjang`
-- --------------------------------------------------------
CREATE TABLE `keranjang` (
  `id_keranjang` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pelanggan` bigint(20) UNSIGNED NOT NULL,
  `id_produk` bigint(20) UNSIGNED NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_keranjang`),
  UNIQUE KEY `keranjang_pelanggan_produk_unique` (`id_pelanggan`,`id_produk`),
  KEY `keranjang_id_produk_foreign` (`id_produk`),
  CONSTRAINT `keranjang_id_pelanggan_foreign` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE CASCADE,
  CONSTRAINT `keranjang_id_produk_foreign` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 8. Table structure for table `pesanan`
-- --------------------------------------------------------
CREATE TABLE `pesanan` (
  `id_pesanan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_pesanan` varchar(255) NOT NULL,
  `id_pelanggan` bigint(20) UNSIGNED DEFAULT NULL,
  `id_kasir` bigint(20) UNSIGNED DEFAULT NULL,
  `sumber` enum('online','toko') NOT NULL DEFAULT 'online',
  `metode_ambil` enum('ambil','antar') NOT NULL DEFAULT 'antar',
  `id_area` bigint(20) UNSIGNED DEFAULT NULL,
  `alamat_antar` text DEFAULT NULL,
  `catatan` varchar(255) DEFAULT NULL,
  `status` enum('menunggu','dikonfirmasi','diproses','siap','dikirim','selesai','dibatalkan','ditolak') NOT NULL DEFAULT 'menunggu',
  `subtotal` decimal(12,2) NOT NULL,
  `total_diskon` decimal(12,2) NOT NULL DEFAULT 0.00,
  `ongkir` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_bayar` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pesanan`),
  UNIQUE KEY `pesanan_kode_pesanan_unique` (`kode_pesanan`),
  KEY `pesanan_id_pelanggan_foreign` (`id_pelanggan`),
  KEY `pesanan_id_kasir_foreign` (`id_kasir`),
  KEY `pesanan_id_area_foreign` (`id_area`),
  CONSTRAINT `pesanan_id_pelanggan_foreign` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE SET NULL,
  CONSTRAINT `pesanan_id_kasir_foreign` FOREIGN KEY (`id_kasir`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pesanan_id_area_foreign` FOREIGN KEY (`id_area`) REFERENCES `area_antar` (`id_area`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 9. Table structure for table `detail_pesanan`
-- --------------------------------------------------------
CREATE TABLE `detail_pesanan` (
  `id_detail` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pesanan` bigint(20) UNSIGNED NOT NULL,
  `id_produk` bigint(20) UNSIGNED NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `harga_satuan` decimal(12,2) NOT NULL,
  `id_promo` bigint(20) UNSIGNED DEFAULT NULL,
  `persen_diskon` decimal(5,2) NOT NULL DEFAULT 0.00,
  `potongan` decimal(12,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_detail`),
  KEY `detail_pesanan_id_pesanan_foreign` (`id_pesanan`),
  KEY `detail_pesanan_id_produk_foreign` (`id_produk`),
  KEY `detail_pesanan_id_promo_foreign` (`id_promo`),
  CONSTRAINT `detail_pesanan_id_pesanan_foreign` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE,
  CONSTRAINT `detail_pesanan_id_produk_foreign` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`),
  CONSTRAINT `detail_pesanan_id_promo_foreign` FOREIGN KEY (`id_promo`) REFERENCES `promo` (`id_promo`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 10. Table structure for table `pembayaran`
-- --------------------------------------------------------
CREATE TABLE `pembayaran` (
  `id_pembayaran` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pesanan` bigint(20) UNSIGNED NOT NULL,
  `metode` enum('transfer','cod','tunai') NOT NULL DEFAULT 'transfer',
  `jumlah_bayar` decimal(12,2) NOT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL,
  `status` enum('menunggu','lunas','ditolak') NOT NULL DEFAULT 'menunggu',
  `id_kasir` bigint(20) UNSIGNED DEFAULT NULL,
  `dibayar_pada` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pembayaran`),
  UNIQUE KEY `pembayaran_id_pesanan_unique` (`id_pesanan`),
  KEY `pembayaran_id_kasir_foreign` (`id_kasir`),
  CONSTRAINT `pembayaran_id_pesanan_foreign` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE,
  CONSTRAINT `pembayaran_id_kasir_foreign` FOREIGN KEY (`id_kasir`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 11. Table structure for table `riwayat_stok`
-- --------------------------------------------------------
CREATE TABLE `riwayat_stok` (
  `id_riwayat` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_produk` bigint(20) UNSIGNED NOT NULL,
  `jenis` enum('masuk','pesanan','batal','koreksi') NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_riwayat`),
  KEY `riwayat_stok_id_produk_foreign` (`id_produk`),
  KEY `riwayat_stok_user_id_foreign` (`user_id`),
  CONSTRAINT `riwayat_stok_id_produk_foreign` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE,
  CONSTRAINT `riwayat_stok_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- DATA SEED (DATA AWAL LENGKAP)
-- ========================================================

-- Users (Password semua akun: password)
-- Hash bcrypt default untuk string "password": $2y$12$e6mZtD65jWvj0M8IuVkv2u0Vj.U9vY1cT3y81gW6uD75hQ0Bshm8e
-- Password cadangan mudah: password
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `aktif`) VALUES
(1, 'Pak Ridwan (Owner)', 'admin@tokobuahpakridwan.com', '$2y$12$y17fMwqgqU0zZ0pIq3v.kO0yTq7Kx6oV8I1mS60t9N2U4aFmN6Oq2', 'admin', 1),
(2, 'Siti Kasir', 'kasir@tokobuahpakridwan.com', '$2y$12$y17fMwqgqU0zZ0pIq3v.kO0yTq7Kx6oV8I1mS60t9N2U4aFmN6Oq2', 'kasir', 1),
(3, 'Syafiqah Zahra', 'pika@gmail.com', '$2y$12$y17fMwqgqU0zZ0pIq3v.kO0yTq7Kx6oV8I1mS60t9N2U4aFmN6Oq2', 'customer', 1),
(4, 'Puti Salwa', 'salwa@gmail.com', '$2y$12$y17fMwqgqU0zZ0pIq3v.kO0yTq7Kx6oV8I1mS60t9N2U4aFmN6Oq2', 'customer', 1),
(5, 'Zulfika Madinatul Ilmi', 'ilmi@gmail.com', '$2y$12$y17fMwqgqU0zZ0pIq3v.kO0yTq7Kx6oV8I1mS60t9N2U4aFmN6Oq2', 'customer', 1);

-- Data Pelanggan
INSERT INTO `pelanggan` (`id_pelanggan`, `user_id`, `nama_pelanggan`, `no_hp`, `alamat`) VALUES
(1, 3, 'Syafiqah Zahra', '081234567890', 'Jl. Melati No. 12, Sail, Pekanbaru'),
(2, 4, 'Puti Salwa', '081234567891', 'Jl. Umban Sari No. 5, Rumbai, Pekanbaru'),
(3, 5, 'Zulfika Madinatul Ilmi', '081234567892', 'Jl. Sudirman No. 88, Pekanbaru Kota');

-- Kategori
INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Buah Lokal'),
(2, 'Buah Impor'),
(3, 'Paket Buah'),
(4, 'Buah Organik');

-- Area Antar (Ongkir Wilayah Pekanbaru)
INSERT INTO `area_antar` (`id_area`, `nama_area`, `ongkir`, `aktif`) VALUES
(1, 'Pekanbaru Kota', 8000.00, 1),
(2, 'Sukajadi', 10000.00, 1),
(3, 'Rumbai', 10000.00, 1),
(4, 'Sail', 12000.00, 1),
(5, 'Bukit Raya', 12000.00, 1),
(6, 'Tampan / Binawidya', 15000.00, 1),
(7, 'Marpoyan Damai', 12000.00, 1),
(8, 'Payung Sekaki', 15000.00, 1),
(9, 'Tenayan Raya', 18000.00, 1);

-- Produk (Buah)
INSERT INTO `produk` (`id_produk`, `id_kategori`, `nama_produk`, `deskripsi`, `satuan`, `harga`, `stok`, `gambar`, `aktif`) VALUES
(1, 2, 'Apel Fuji Super', 'Apel Fuji import segar dengan tekstur renyah dan rasa manis alami berair.', 'kg', 38000.00, 50.00, 'fruite-item-5.jpg', 1),
(2, 2, 'Jeruk Mandarin Manis', 'Jeruk mandarin segar manis dengan bulir tebal dan kaya vitamin C.', 'kg', 32000.00, 65.00, 'fruite-item-1.jpg', 1),
(3, 1, 'Pisang Cavendish Segar', 'Pisang Cavendish kuning mulus bernutrisi tinggi, manis dan mengenyangkan.', 'kg', 22000.00, 40.00, 'fruite-item-3.jpg', 1),
(4, 2, 'Anggur Red Globe', 'Anggur merah impor manis tanpa biji, renyah dan segar.', 'kg', 55000.00, 30.00, 'fruite-item-2.jpg', 1),
(5, 1, 'Mangga Harum Manis', 'Mangga lokal pilihan dengan aroma harum semerbak dan rasa legit manis.', 'kg', 28000.00, 45.00, 'fruite-item-4.jpg', 1),
(6, 1, 'Alpukat Mentega Super', 'Alpukat mentega daging tebal legit, bebas serat dan gurih manis alami.', 'kg', 35000.00, 38.00, 'fruite-item-6.jpg', 1),
(7, 1, 'Semangka Merah Non-Biji', 'Semangka merah segar manis, sangat menyegarkan di cuaca terik.', 'kg', 12000.00, 80.00, 'vegetable-item-6.jpg', 1),
(8, 4, 'Stroberi Organik Ciwidey', 'Stroberi segar dipetik langsung dari kebun organik tanpa pestisida kimia.', 'pcs', 25000.00, 25.00, 'fruite-item-2.jpg', 1),
(9, 1, 'Melon Madu Golden', 'Melon madu daging renyah dengan aroma harum madu khas pilihan.', 'kg', 24000.00, 35.00, 'vegetable-item-3.jpg', 1),
(10, 3, 'Paket Parsel Buah Sehat', 'Paket aneka ragam buah premium kemasan parsel cantik untuk hantaran & kado sehat.', 'pcs', 150000.00, 15.00, 'featur-1.jpg', 1),
(11, 3, 'Paket Buah Harian Keluarga', 'Paket hemat campuran pisang, apel, dan jeruk untuk konsumsi harian keluarga.', 'pcs', 95000.00, 20.00, 'featur-2.jpg', 1),
(12, 2, 'Kiwi Gold Zespri', 'Kiwi gold dengan rasa manis lembut dan kandungan antioksidan super tinggi.', 'kg', 65000.00, 20.00, 'fruite-item-1.jpg', 1);

-- Promo (Diskon buatan Admin/Owner)
INSERT INTO `promo` (`id_promo`, `nama_promo`, `id_produk`, `persen_diskon`, `min_jumlah`, `tanggal_mulai`, `tanggal_selesai`, `aktif`) VALUES
(1, 'Diskon Beli 3 kg Apel Fuji', 1, 10.00, 3.00, '2026-11-01', '2026-12-31', 1),
(2, 'Promo Jeruk Mandarin Manis', 2, 12.50, 2.00, '2026-11-01', '2026-12-31', 1),
(3, 'Gebyar Diskon Akhir Tahun Semua Buah', NULL, 5.00, 5.00, '2026-11-01', '2026-12-31', 1);

-- Riwayat Stok Awal (Masuk)
INSERT INTO `riwayat_stok` (`id_riwayat`, `id_produk`, `jenis`, `jumlah`, `keterangan`, `user_id`, `created_at`) VALUES
(1, 1, 'masuk', 50.00, 'Stok awal Apel Fuji Super', 1, NOW()),
(2, 2, 'masuk', 65.00, 'Stok awal Jeruk Mandarin', 1, NOW()),
(3, 3, 'masuk', 40.00, 'Stok awal Pisang Cavendish', 1, NOW()),
(4, 4, 'masuk', 30.00, 'Stok awal Anggur Red Globe', 1, NOW()),
(5, 5, 'masuk', 45.00, 'Stok awal Mangga Harum Manis', 1, NOW()),
(6, 6, 'masuk', 38.00, 'Stok awal Alpukat Mentega', 1, NOW());

-- Pesanan Contoh (Online & Selesai)
INSERT INTO `pesanan` (`id_pesanan`, `kode_pesanan`, `id_pelanggan`, `id_kasir`, `sumber`, `metode_ambil`, `id_area`, `alamat_antar`, `catatan`, `status`, `subtotal`, `total_diskon`, `ongkir`, `total_bayar`, `created_at`) VALUES
(1, 'PSN-20261118-001', 1, 2, 'online', 'antar', 4, 'Jl. Melati No. 12, Sail, Pekanbaru', 'Tolong pilihkan yang manis dan matang', 'selesai', 114000.00, 11400.00, 12000.00, 114600.00, '2026-11-18 10:15:00'),
(2, 'PSN-20261119-002', 2, 2, 'online', 'ambil', NULL, NULL, 'Saya ambil jam 2 siang nanti', 'selesai', 70000.00, 0.00, 0.00, 70000.00, '2026-11-19 11:30:00'),
(3, 'PSN-20261120-003', 1, NULL, 'online', 'antar', 4, 'Jl. Melati No. 12, Sail, Pekanbaru', 'Antar sebelum ashar ya', 'menunggu', 87000.00, 0.00, 12000.00, 99000.00, NOW());

-- Detail Pesanan Contoh
INSERT INTO `detail_pesanan` (`id_detail`, `id_pesanan`, `id_produk`, `jumlah`, `harga_satuan`, `id_promo`, `persen_diskon`, `potongan`, `subtotal`) VALUES
(1, 1, 1, 3.00, 38000.00, 1, 10.00, 11400.00, 102600.00),
(2, 2, 6, 2.00, 35000.00, NULL, 0.00, 0.00, 70000.00),
(3, 3, 2, 2.00, 32000.00, 2, 12.50, 8000.00, 56000.00),
(4, 3, 3, 1.00, 22000.00, NULL, 0.00, 0.00, 22000.00);

-- Pembayaran Contoh
INSERT INTO `pembayaran` (`id_pembayaran`, `id_pesanan`, `metode`, `jumlah_bayar`, `bukti_transfer`, `status`, `id_kasir`, `dibayar_pada`, `created_at`) VALUES
(1, 1, 'transfer', 114600.00, 'sample_transfer.jpg', 'lunas', 2, '2026-11-18 10:30:00', '2026-11-18 10:20:00'),
(2, 2, 'cod', 70000.00, NULL, 'lunas', 2, '2026-11-19 14:10:00', '2026-11-19 11:30:00'),
(3, 3, 'transfer', 99000.00, NULL, 'menunggu', NULL, NULL, NOW());

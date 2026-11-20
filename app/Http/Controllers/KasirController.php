<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Kategori;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Promo;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    /**
     * Dashboard Kasir
     */
    public function dashboard()
    {
        $today = now()->toDateString();

        // Pesanan baru yang butuh konfirmasi
        $newOrdersCount = Pesanan::where('status', 'menunggu')->count();
        $processingCount = Pesanan::whereIn('status', ['dikonfirmasi', 'diproses'])->count();
        $readyOrShippedCount = Pesanan::whereIn('status', ['siap', 'dikirim'])->count();

        // Rekap ringkas hari ini
        $todayOrders = Pesanan::whereDate('created_at', $today)->get();
        $todaySales = $todayOrders->where('status', 'selesai')->sum('total_bayar');

        // Pembayaran transfer yang menunggu verifikasi
        $pendingPaymentsCount = Pembayaran::where('status', 'menunggu')
            ->whereNotNull('bukti_transfer')
            ->count();

        $recentOrders = Pesanan::with(['pelanggan', 'pembayaran'])
            ->latest()
            ->take(8)
            ->get();

        return view('kasir.dashboard', compact(
            'newOrdersCount',
            'processingCount',
            'readyOrShippedCount',
            'todaySales',
            'pendingPaymentsCount',
            'recentOrders'
        ));
    }

    /**
     * Mengelola Pesanan Online
     */
    public function orders(Request $request)
    {
        $query = Pesanan::with(['pelanggan', 'pembayaran', 'area', 'kasir']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('sumber')) {
            $query->where('sumber', $request->sumber);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($query) use ($q) {
                $query->where('kode_pesanan', 'like', "%{$q}%")
                      ->orWhereHas('pelanggan', function ($sub) use ($q) {
                          $sub->where('nama_pelanggan', 'like', "%{$q}%")
                              ->orWhere('no_hp', 'like', "%{$q}%");
                      });
            });
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        return view('kasir.orders', compact('orders'));
    }

    /**
     * Detail Pesanan untuk Kasir
     */
    public function orderDetail($id)
    {
        $order = Pesanan::with(['pelanggan', 'details.produk', 'details.promo', 'pembayaran', 'area', 'kasir'])
            ->findOrFail($id);

        return view('kasir.order-detail', compact('order'));
    }

    /**
     * Update Status Pesanan oleh Kasir
     */
    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:dikonfirmasi,diproses,siap,dikirim,selesai,ditolak',
        ]);

        $order = Pesanan::findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->status = $newStatus;
        $order->id_kasir = Auth::id(); // Kasir yang menangani
        $order->save();

        // Jika status diubah jadi 'selesai' dan metode bayar tunai/COD, auto tandai lunas
        if ($newStatus === 'selesai' && $order->pembayaran && $order->pembayaran->status !== 'lunas') {
            $order->pembayaran->status = 'lunas';
            $order->pembayaran->id_kasir = Auth::id();
            $order->pembayaran->dibayar_pada = now();
            $order->pembayaran->save();
        }

        // Jika ditolak oleh kasir, kembalikan stok buah
        if ($newStatus === 'ditolak' && $oldStatus !== 'ditolak' && $oldStatus !== 'dibatalkan') {
            foreach ($order->details as $item) {
                if ($item->produk) {
                    $item->produk->increment('stok', $item->jumlah);

                    RiwayatStok::create([
                        'id_produk' => $item->id_produk,
                        'jenis' => 'batal',
                        'jumlah' => $item->jumlah,
                        'keterangan' => "Pesanan {$order->kode_pesanan} ditolak kasir",
                        'user_id' => Auth::id(),
                        'created_at' => now(),
                    ]);
                }
            }
        }

        return back()->with('success', "Status pesanan #{$order->kode_pesanan} berhasil diubah menjadi " . strtoupper($newStatus));
    }

    /**
     * Transaksi Langsung di Toko (POS / Kasir Offline)
     */
    public function pos()
    {
        $products = Produk::with('kategori')
            ->where('aktif', 1)
            ->where('stok', '>', 0)
            ->orderBy('nama_produk')
            ->get();

        $activePromos = Promo::where('aktif', 1)
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->get();

        return view('kasir.pos', compact('products', 'activePromos'));
    }

    /**
     * Simpan Transaksi POS Langsung di Toko
     */
    public function processPos(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id_produk' => 'required|exists:produk,id_produk',
            'items.*.jumlah' => 'required|numeric|min:0.1',
            'metode_bayar' => 'required|in:tunai,transfer',
            'nominal_bayar' => 'required|numeric|min:0',
            'nama_pembeli' => 'nullable|string|max:100',
        ]);

        $todayPrefix = 'POS-' . date('Ymd') . '-';
        $lastOrderToday = Pesanan::where('kode_pesanan', 'like', $todayPrefix . '%')
            ->orderBy('id_pesanan', 'desc')
            ->first();

        $nextSeq = 1;
        if ($lastOrderToday) {
            $parts = explode('-', $lastOrderToday->kode_pesanan);
            if (isset($parts[2])) {
                $nextSeq = intval($parts[2]) + 1;
            }
        }
        $kodePesanan = $todayPrefix . str_pad($nextSeq, 3, '0', STR_PAD_LEFT);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $totalDiskon = 0;
            $itemsToProcess = [];

            // Validasi stok dan kalkulasi diskon promo
            foreach ($request->items as $itemInput) {
                $produk = Produk::findOrFail($itemInput['id_produk']);
                $jumlah = (float) $itemInput['jumlah'];

                if ($produk->stok < $jumlah) {
                    throw new \Exception("Stok buah {$produk->nama_produk} tidak mencukupi (sisa {$produk->stok} {$produk->satuan}).");
                }

                $hargaSatuan = (float) $produk->harga;
                $itemSubtotal = $hargaSatuan * $jumlah;

                // Cari promo
                $promo = Promo::where('aktif', 1)
                    ->where(function($q) use ($produk) {
                        $q->where('id_produk', $produk->id_produk)->orWhereNull('id_produk');
                    })
                    ->whereDate('tanggal_mulai', '<=', now())
                    ->whereDate('tanggal_selesai', '>=', now())
                    ->orderByRaw('id_produk IS NULL ASC')
                    ->first();

                $persenDiskon = 0;
                $potongan = 0;
                $idPromo = null;

                if ($promo && $promo->isValidFor($jumlah)) {
                    $idPromo = $promo->id_promo;
                    $persenDiskon = (float) $promo->persen_diskon;
                    $potongan = ($itemSubtotal * $persenDiskon) / 100;
                }

                $subtotalSetelahDiskon = $itemSubtotal - $potongan;

                $itemsToProcess[] = [
                    'produk' => $produk,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $hargaSatuan,
                    'id_promo' => $idPromo,
                    'persen_diskon' => $persenDiskon,
                    'potongan' => $potongan,
                    'subtotal' => $subtotalSetelahDiskon,
                ];

                $subtotal += $itemSubtotal;
                $totalDiskon += $potongan;
            }

            $totalBayar = $subtotal - $totalDiskon;

            if ($request->nominal_bayar < $totalBayar) {
                throw new \Exception("Nominal pembayaran tunai kurang dari total belanja (Total: Rp " . number_format($totalBayar, 0, ',', '.') . ").");
            }

            // Simpan pesanan toko
            $pesanan = Pesanan::create([
                'kode_pesanan' => $kodePesanan,
                'id_pelanggan' => null, // Pembelian langsung tanpa akun
                'id_kasir' => Auth::id(),
                'sumber' => 'toko',
                'metode_ambil' => 'ambil',
                'id_area' => null,
                'alamat_antar' => null,
                'catatan' => $request->filled('nama_pembeli') ? "Pembeli: " . $request->nama_pembeli : 'Langsung di toko',
                'status' => 'selesai', // Langsung selesai
                'subtotal' => $subtotal,
                'total_diskon' => $totalDiskon,
                'ongkir' => 0,
                'total_bayar' => $totalBayar,
            ]);

            // Simpan detail dan kurangi stok
            foreach ($itemsToProcess as $itm) {
                DetailPesanan::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'id_produk' => $itm['produk']->id_produk,
                    'jumlah' => $itm['jumlah'],
                    'harga_satuan' => $itm['harga_satuan'],
                    'id_promo' => $itm['id_promo'],
                    'persen_diskon' => $itm['persen_diskon'],
                    'potongan' => $itm['potongan'],
                    'subtotal' => $itm['subtotal'],
                ]);

                $itm['produk']->decrement('stok', $itm['jumlah']);

                RiwayatStok::create([
                    'id_produk' => $itm['produk']->id_produk,
                    'jenis' => 'pesanan',
                    'jumlah' => -$itm['jumlah'],
                    'keterangan' => "Penjualan langsung di toko ({$kodePesanan})",
                    'user_id' => Auth::id(),
                    'created_at' => now(),
                ]);
            }

            // Simpan pembayaran
            Pembayaran::create([
                'id_pesanan' => $pesanan->id_pesanan,
                'metode' => $request->metode_bayar === 'tunai' ? 'tunai' : 'transfer',
                'jumlah_bayar' => $totalBayar,
                'bukti_transfer' => null,
                'status' => 'lunas',
                'id_kasir' => Auth::id(),
                'dibayar_pada' => now(),
            ]);

            DB::commit();

            $kembalian = $request->nominal_bayar - $totalBayar;

            return redirect()->route('kasir.orders.detail', $pesanan->id_pesanan)
                ->with('success', "Transaksi Toko #{$kodePesanan} BERHASIL! Total: Rp " . number_format($totalBayar, 0, ',', '.') . " | Kembalian: Rp " . number_format($kembalian, 0, ',', '.'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Memvalidasi Pembayaran
     */
    public function payments(Request $request)
    {
        $query = Pembayaran::with(['pesanan.pelanggan', 'kasir']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('metode')) {
            $query->where('metode', $request->metode);
        }

        $payments = $query->latest()->paginate(15)->withQueryString();

        return view('kasir.payments', compact('payments'));
    }

    /**
     * Konfirmasi Status Pembayaran (Lunas atau Tolak)
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:lunas,ditolak',
        ]);

        $pembayaran = Pembayaran::with('pesanan')->findOrFail($id);
        $pembayaran->status = $request->status;
        $pembayaran->id_kasir = Auth::id();

        if ($request->status === 'lunas') {
            $pembayaran->dibayar_pada = now();
            // Jika pesanan masih menunggu, otomatis ubah jadi dikonfirmasi
            if ($pembayaran->pesanan && $pembayaran->pesanan->status === 'menunggu') {
                $pembayaran->pesanan->status = 'dikonfirmasi';
                $pembayaran->pesanan->id_kasir = Auth::id();
                $pembayaran->pesanan->save();
            }
        }
        $pembayaran->save();

        return back()->with('success', "Pembayaran untuk pesanan #{$pembayaran->pesanan->kode_pesanan} berhasil ditandai " . strtoupper($request->status));
    }

    /**
     * Memantau Stok & Promo Aktif
     */
    public function stocks()
    {
        $products = Produk::with(['kategori', 'promos'])
            ->orderBy('stok', 'asc')
            ->get();

        $activePromos = Promo::with('produk')
            ->where('aktif', 1)
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->get();

        return view('kasir.stocks', compact('products', 'activePromos'));
    }

    /**
     * Rekap Harian (Laporan Tutup Kasir)
     */
    public function dailyReport(Request $request)
    {
        $date = $request->get('date', now()->toDateString());

        $orders = Pesanan::with(['pelanggan', 'details.produk', 'pembayaran'])
            ->whereDate('created_at', $date)
            ->get();

        $totalTransaksi = $orders->count();
        $transaksiSelesai = $orders->where('status', 'selesai')->count();
        $totalPendapatan = $orders->where('status', 'selesai')->sum('total_bayar');

        $tunaiTotal = $orders->where('status', 'selesai')
            ->filter(fn($o) => optional($o->pembayaran)->metode === 'tunai')
            ->sum('total_bayar');

        $transferTotal = $orders->where('status', 'selesai')
            ->filter(fn($o) => optional($o->pembayaran)->metode === 'transfer')
            ->sum('total_bayar');

        $codTotal = $orders->where('status', 'selesai')
            ->filter(fn($o) => optional($o->pembayaran)->metode === 'cod')
            ->sum('total_bayar');

        return view('kasir.daily-report', compact(
            'date',
            'orders',
            'totalTransaksi',
            'transaksiSelesai',
            'totalPendapatan',
            'tunaiTotal',
            'transferTotal',
            'codTotal'
        ));
    }
}

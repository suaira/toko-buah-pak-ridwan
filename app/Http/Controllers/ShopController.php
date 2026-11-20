<?php

namespace App\Http\Controllers;

use App\Models\AreaAntar;
use App\Models\DetailPesanan;
use App\Models\Kategori;
use App\Models\Keranjang;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Promo;
use App\Models\RiwayatStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    /**
     * Halaman Utama (Fruitables Homepage)
     */
    public function home()
    {
        $kategoriList = Kategori::withCount(['produk' => function ($q) {
            $q->where('aktif', 1);
        }])->get();

        $featuredProducts = Produk::with(['kategori', 'promos'])
            ->where('aktif', 1)
            ->latest()
            ->take(8)
            ->get();

        $activePromos = Promo::with('produk')
            ->where('aktif', 1)
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->get();

        return view('shop.home', compact('kategoriList', 'featuredProducts', 'activePromos'));
    }

    /**
     * Katalog Buah (Shop / Produk)
     */
    public function shop(Request $request)
    {
        $query = Produk::with(['kategori', 'promos'])->where('aktif', 1);

        // Filter Kategori
        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        // Pencarian Keyword
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_produk', 'like', "%{$keyword}%")
                  ->orWhere('deskripsi', 'like', "%{$keyword}%");
            });
        }

        // Filter Rentang Harga
        if ($request->filled('max_price')) {
            $query->where('harga', '<=', $request->max_price);
        }

        // Sorting
        if ($request->sort == 'price_asc') {
            $query->orderBy('harga', 'asc');
        } elseif ($request->sort == 'price_desc') {
            $query->orderBy('harga', 'desc');
        } elseif ($request->sort == 'name_asc') {
            $query->orderBy('nama_produk', 'asc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(9)->withQueryString();
        $kategoriList = Kategori::withCount(['produk' => function ($q) {
            $q->where('aktif', 1);
        }])->get();

        $activePromos = Promo::where('aktif', 1)
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->get();

        return view('shop.index', compact('products', 'kategoriList', 'activePromos'));
    }

    /**
     * Detail Produk Buah
     */
    public function detail($id)
    {
        $produk = Produk::with(['kategori', 'promos'])->where('aktif', 1)->findOrFail($id);

        $relatedProducts = Produk::where('id_kategori', $produk->id_kategori)
            ->where('id_produk', '!=', $produk->id_produk)
            ->where('aktif', 1)
            ->take(4)
            ->get();

        $kategoriList = Kategori::withCount(['produk' => function ($q) {
            $q->where('aktif', 1);
        }])->get();

        return view('shop.detail', compact('produk', 'relatedProducts', 'kategoriList'));
    }

    /**
     * Halaman Keranjang Belanja
     */
    public function cart()
    {
        if (!Auth::check() || !Auth::user()->isCustomer()) {
            return redirect()->route('login')->with('info', 'Silakan login sebagai pelanggan untuk mengakses keranjang belanja.');
        }

        $pelanggan = Auth::user()->pelanggan;
        if (!$pelanggan) {
            return redirect()->route('home')->with('error', 'Profil pelanggan belum lengkap.');
        }

        $cartItems = Keranjang::with('produk.kategori')
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->get();

        // Hitung subtotal dan kalkulasi promo otomatis
        $cartCalculations = $this->calculateCart($cartItems);

        return view('shop.cart', compact('cartItems', 'cartCalculations'));
    }

    /**
     * Tambah item ke Keranjang
     */
    public function addToCart(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isCustomer()) {
            return redirect()->route('login')->with('info', 'Silakan login untuk menambahkan ke keranjang belanja.');
        }

        $request->validate([
            'id_produk' => 'required|exists:produk,id_produk',
            'jumlah' => 'required|numeric|min:0.1',
        ]);

        $pelanggan = Auth::user()->pelanggan;
        $produk = Produk::findOrFail($request->id_produk);

        if ($produk->stok < $request->jumlah) {
            return back()->with('error', "Stok {$produk->nama_produk} tidak mencukupi (sisa {$produk->stok} {$produk->satuan}).");
        }

        $item = Keranjang::where('id_pelanggan', $pelanggan->id_pelanggan)
            ->where('id_produk', $produk->id_produk)
            ->first();

        if ($item) {
            $totalJumlah = $item->jumlah + $request->jumlah;
            if ($produk->stok < $totalJumlah) {
                return back()->with('error', "Total di keranjang melebihi stok yang tersedia (sisa {$produk->stok} {$produk->satuan}).");
            }
            $item->jumlah = $totalJumlah;
            $item->save();
        } else {
            Keranjang::create([
                'id_pelanggan' => $pelanggan->id_pelanggan,
                'id_produk' => $produk->id_produk,
                'jumlah' => $request->jumlah,
            ]);
        }

        return redirect()->route('cart')->with('success', "{$produk->nama_produk} berhasil dimasukkan ke keranjang!");
    }

    /**
     * Update kuantitas item keranjang
     */
    public function updateCart(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isCustomer()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'id_keranjang' => 'required|exists:keranjang,id_keranjang',
            'jumlah' => 'required|numeric|min:0.1',
        ]);

        $pelanggan = Auth::user()->pelanggan;
        $item = Keranjang::where('id_keranjang', $request->id_keranjang)
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->firstOrFail();

        $produk = $item->produk;
        if ($produk->stok < $request->jumlah) {
            return back()->with('error', "Stok {$produk->nama_produk} hanya tersedia {$produk->stok} {$produk->satuan}.");
        }

        $item->jumlah = $request->jumlah;
        $item->save();

        return back()->with('success', 'Jumlah belanja berhasil diperbarui.');
    }

    /**
     * Hapus item dari keranjang
     */
    public function removeFromCart($id)
    {
        if (!Auth::check() || !Auth::user()->isCustomer()) {
            return redirect()->route('login');
        }

        $pelanggan = Auth::user()->pelanggan;
        Keranjang::where('id_keranjang', $id)
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->delete();

        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    /**
     * Halaman Checkout
     */
    public function checkout()
    {
        if (!Auth::check() || !Auth::user()->isCustomer()) {
            return redirect()->route('login')->with('info', 'Silakan login untuk melakukan checkout.');
        }

        $pelanggan = Auth::user()->pelanggan;
        $cartItems = Keranjang::with('produk.kategori')
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('shop')->with('info', 'Keranjang Anda masih kosong. Silakan pilih buah terlebih dahulu.');
        }

        $cartCalculations = $this->calculateCart($cartItems);
        $deliveryAreas = AreaAntar::where('aktif', 1)->get();

        return view('shop.checkout', compact('cartItems', 'cartCalculations', 'pelanggan', 'deliveryAreas'));
    }

    /**
     * Proses Pemesanan / Checkout
     */
    public function processCheckout(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isCustomer()) {
            return redirect()->route('login');
        }

        $request->validate([
            'metode_ambil' => 'required|in:ambil,antar',
            'id_area' => 'nullable|required_if:metode_ambil,antar|exists:area_antar,id_area',
            'alamat_antar' => 'nullable|required_if:metode_ambil,antar|string|max:1000',
            'catatan' => 'nullable|string|max:255',
            'metode_bayar' => 'required|in:transfer,cod',
            'no_hp' => 'required|string|max:15',
        ]);

        $user = Auth::user();
        $pelanggan = $user->pelanggan;

        // Update profil jika no_hp/alamat diisi
        if ($request->filled('no_hp')) {
            $pelanggan->no_hp = $request->no_hp;
        }
        if ($request->filled('alamat_antar') && empty($pelanggan->alamat)) {
            $pelanggan->alamat = $request->alamat_antar;
        }
        $pelanggan->save();

        $cartItems = Keranjang::with('produk')->where('id_pelanggan', $pelanggan->id_pelanggan)->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('shop')->with('error', 'Keranjang belanja kosong.');
        }

        // Cek kembali ketersediaan stok
        foreach ($cartItems as $item) {
            if ($item->produk->stok < $item->jumlah) {
                return redirect()->route('cart')->with('error', "Stok buah {$item->produk->nama_produk} tidak mencukupi (sisa {$item->produk->stok} {$item->produk->satuan}).");
            }
        }

        $cartCalculations = $this->calculateCart($cartItems);

        $ongkir = 0;
        if ($request->metode_ambil === 'antar' && $request->filled('id_area')) {
            $area = AreaAntar::find($request->id_area);
            $ongkir = $area ? (float) $area->ongkir : 0;
        }

        $subtotal = $cartCalculations['subtotal'];
        $totalDiskon = $cartCalculations['total_diskon'];
        $totalBayar = ($subtotal - $totalDiskon) + $ongkir;

        // Kode pesanan format: PSN-YYYYMMDD-XXX
        $todayPrefix = 'PSN-' . date('Ymd') . '-';
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
            // 1. Buat Header Pesanan
            $pesanan = Pesanan::create([
                'kode_pesanan' => $kodePesanan,
                'id_pelanggan' => $pelanggan->id_pelanggan,
                'id_kasir' => null,
                'sumber' => 'online',
                'metode_ambil' => $request->metode_ambil,
                'id_area' => $request->metode_ambil === 'antar' ? $request->id_area : null,
                'alamat_antar' => $request->metode_ambil === 'antar' ? $request->alamat_antar : null,
                'catatan' => $request->catatan,
                'status' => 'menunggu',
                'subtotal' => $subtotal,
                'total_diskon' => $totalDiskon,
                'ongkir' => $ongkir,
                'total_bayar' => $totalBayar,
            ]);

            // 2. Buat Detail Pesanan & Kurangi Stok Produk
            foreach ($cartCalculations['items'] as $itemData) {
                DetailPesanan::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'id_produk' => $itemData['produk']->id_produk,
                    'jumlah' => $itemData['jumlah'],
                    'harga_satuan' => $itemData['harga_satuan'],
                    'id_promo' => $itemData['id_promo'],
                    'persen_diskon' => $itemData['persen_diskon'],
                    'potongan' => $itemData['potongan'],
                    'subtotal' => $itemData['subtotal_setelah_diskon'],
                ]);

                // Kurangi stok
                $itemData['produk']->decrement('stok', $itemData['jumlah']);

                // Catat di riwayat_stok
                RiwayatStok::create([
                    'id_produk' => $itemData['produk']->id_produk,
                    'jenis' => 'pesanan',
                    'jumlah' => -$itemData['jumlah'],
                    'keterangan' => "Pesanan {$kodePesanan} oleh {$pelanggan->nama_pelanggan}",
                    'user_id' => $user->id,
                    'created_at' => now(),
                ]);
            }

            // 3. Buat Pembayaran
            Pembayaran::create([
                'id_pesanan' => $pesanan->id_pesanan,
                'metode' => $request->metode_bayar,
                'jumlah_bayar' => $totalBayar,
                'bukti_transfer' => null,
                'status' => 'menunggu',
                'id_kasir' => null,
                'dibayar_pada' => null,
            ]);

            // 4. Kosongkan keranjang pelanggan
            Keranjang::where('id_pelanggan', $pelanggan->id_pelanggan)->delete();

            DB::commit();

            return redirect()->route('orders.detail', $pesanan->id_pesanan)
                ->with('success', "Pesanan #{$kodePesanan} berhasil dibuat! Silakan lakukan pembayaran jika memilih transfer.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Daftar Pesanan Pelanggan (Pesanan Saya)
     */
    public function myOrders()
    {
        if (!Auth::check() || !Auth::user()->isCustomer()) {
            return redirect()->route('login');
        }

        $pelanggan = Auth::user()->pelanggan;
        $orders = Pesanan::with(['details.produk', 'pembayaran', 'area'])
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->latest()
            ->paginate(10);

        return view('shop.my-orders', compact('orders'));
    }

    /**
     * Detail & Tracking Pesanan
     */
    public function orderDetail($id)
    {
        if (!Auth::check() || !Auth::user()->isCustomer()) {
            return redirect()->route('login');
        }

        $pelanggan = Auth::user()->pelanggan;
        $order = Pesanan::with(['details.produk', 'details.promo', 'pembayaran', 'area', 'kasir'])
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->findOrFail($id);

        return view('shop.order-detail', compact('order'));
    }

    /**
     * Batalkan Pesanan oleh Customer
     * Sesuai Ketentuan PDF: "U (batalkan sebelum dikonfirmasi)"
     */
    public function cancelOrder($id)
    {
        if (!Auth::check() || !Auth::user()->isCustomer()) {
            return redirect()->route('login');
        }

        $pelanggan = Auth::user()->pelanggan;
        $order = Pesanan::with('details.produk')
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->findOrFail($id);

        if ($order->status !== 'menunggu') {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah dikonfirmasi atau diproses oleh pihak toko.');
        }

        DB::beginTransaction();
        try {
            $order->status = 'dibatalkan';
            $order->save();

            // Kembalikan stok buah
            foreach ($order->details as $item) {
                if ($item->produk) {
                    $item->produk->increment('stok', $item->jumlah);

                    RiwayatStok::create([
                        'id_produk' => $item->id_produk,
                        'jenis' => 'batal',
                        'jumlah' => $item->jumlah,
                        'keterangan' => "Pembatalan pesanan {$order->kode_pesanan} oleh pembeli",
                        'user_id' => Auth::id(),
                        'created_at' => now(),
                    ]);
                }
            }

            if ($order->pembayaran) {
                $order->pembayaran->status = 'ditolak';
                $order->pembayaran->save();
            }

            DB::commit();
            return back()->with('success', "Pesanan #{$order->kode_pesanan} berhasil dibatalkan.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Unggah Bukti Transfer
     */
    public function uploadPaymentProof(Request $request, $id)
    {
        if (!Auth::check() || !Auth::user()->isCustomer()) {
            return redirect()->route('login');
        }

        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:3072',
        ]);

        $pelanggan = Auth::user()->pelanggan;
        $order = Pesanan::with('pembayaran')
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->findOrFail($id);

        if (!$order->pembayaran) {
            return back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $filename = 'bukti_' . $order->kode_pesanan . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('transfer_proofs', $filename, 'public');

            $order->pembayaran->bukti_transfer = $filename;
            $order->pembayaran->status = 'menunggu';
            $order->pembayaran->save();

            return back()->with('success', 'Bukti transfer berhasil diunggah! Kasir kami akan segera memvalidasi pembayaran Anda.');
        }

        return back()->with('error', 'Gagal mengunggah file.');
    }

    /**
     * Kontak & Lokasi Toko
     */
    public function contact()
    {
        return view('shop.contact');
    }

    /**
     * Helper perhitungan item keranjang & diskon promo otomatis
     */
    private function calculateCart($cartItems)
    {
        $today = now()->toDateString();
        $items = [];
        $subtotal = 0;
        $totalDiskon = 0;

        foreach ($cartItems as $cart) {
            $produk = $cart->produk;
            $jumlah = (float) $cart->jumlah;
            $hargaSatuan = (float) $produk->harga;
            $itemSubtotal = $hargaSatuan * $jumlah;

            // Cari promo yang sesuai
            $promo = Promo::where('aktif', 1)
                ->where('id_produk', $produk->id_produk)
                ->whereDate('tanggal_mulai', '<=', $today)
                ->whereDate('tanggal_selesai', '>=', $today)
                ->first();

            if (!$promo) {
                // Cek promo global
                $promo = Promo::where('aktif', 1)
                    ->whereNull('id_produk')
                    ->whereDate('tanggal_mulai', '<=', $today)
                    ->whereDate('tanggal_selesai', '>=', $today)
                    ->first();
            }

            $persenDiskon = 0;
            $potongan = 0;
            $idPromo = null;

            if ($promo && $promo->isValidFor($jumlah)) {
                $idPromo = $promo->id_promo;
                $persenDiskon = (float) $promo->persen_diskon;
                $potongan = ($itemSubtotal * $persenDiskon) / 100;
            }

            $subtotalSetelahDiskon = $itemSubtotal - $potongan;

            $items[] = [
                'cart_id' => $cart->id_keranjang,
                'produk' => $produk,
                'jumlah' => $jumlah,
                'harga_satuan' => $hargaSatuan,
                'id_promo' => $idPromo,
                'promo' => $promo,
                'persen_diskon' => $persenDiskon,
                'potongan' => $potongan,
                'subtotal_kotor' => $itemSubtotal,
                'subtotal_setelah_diskon' => $subtotalSetelahDiskon,
            ];

            $subtotal += $itemSubtotal;
            $totalDiskon += $potongan;
        }

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'total_diskon' => $totalDiskon,
            'total_bersih' => $subtotal - $totalDiskon,
        ];
    }
}

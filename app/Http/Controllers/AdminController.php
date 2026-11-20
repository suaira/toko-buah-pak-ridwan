<?php

namespace App\Http\Controllers;

use App\Models\AreaAntar;
use App\Models\DetailPesanan;
use App\Models\Kategori;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Promo;
use App\Models\RiwayatStok;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Dashboard Admin (Pak Ridwan / Owner)
     */
    public function dashboard()
    {
        $totalSales = Pesanan::where('status', 'selesai')->sum('total_bayar');
        $totalOrders = Pesanan::count();
        $totalProducts = Produk::count();
        $totalCustomers = Pelanggan::count();

        // Omzet Hari Ini & Bulan Ini
        $todaySales = Pesanan::where('status', 'selesai')->whereDate('created_at', now()->toDateString())->sum('total_bayar');
        $monthSales = Pesanan::where('status', 'selesai')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total_bayar');

        // Pesanan Terbaru
        $recentOrders = Pesanan::with(['pelanggan', 'pembayaran'])
            ->latest()
            ->take(6)
            ->get();

        // Stok Buah yang Menipis (< 15)
        $lowStocks = Produk::where('stok', '<=', 15)->where('aktif', 1)->take(5)->get();

        // 5 Buah Terlaris (dari detail pesanan yang selesai)
        $bestSellers = DetailPesanan::select('id_produk', DB::raw('SUM(jumlah) as total_terjual'), DB::raw('SUM(subtotal) as total_omzet'))
            ->whereHas('pesanan', fn($q) => $q->where('status', 'selesai'))
            ->groupBy('id_produk')
            ->orderByDesc('total_terjual')
            ->with('produk')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSales',
            'totalOrders',
            'totalProducts',
            'totalCustomers',
            'todaySales',
            'monthSales',
            'recentOrders',
            'lowStocks',
            'bestSellers'
        ));
    }

    /* ========================================================
     * 1. CRUD PRODUK & STOK (TERMASUK STOK MASUK DAN KOREKSI)
     * ======================================================== */
    public function products(Request $request)
    {
        $query = Produk::with(['kategori', 'promos']);

        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        if ($request->filled('q')) {
            $query->where('nama_produk', 'like', "%{$request->q}%");
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        $kategoriList = Kategori::all();

        return view('admin.products.index', compact('products', 'kategoriList'));
    }

    public function createProduct()
    {
        $kategoriList = Kategori::all();
        return view('admin.products.create', compact('kategoriList'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'satuan' => 'required|in:kg,pcs',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'aktif' => 'nullable|boolean',
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = 'buah_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('products', $filename, 'public');
            $gambarPath = $filename;
        }

        $produk = Produk::create([
            'nama_produk' => $validated['nama_produk'],
            'id_kategori' => $validated['id_kategori'],
            'satuan' => $validated['satuan'],
            'harga' => $validated['harga'],
            'stok' => $validated['stok'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'gambar' => $gambarPath,
            'aktif' => $request->has('aktif') ? 1 : 0,
        ]);

        // Catat stok awal ke riwayat_stok
        if ($validated['stok'] > 0) {
            RiwayatStok::create([
                'id_produk' => $produk->id_produk,
                'jenis' => 'masuk',
                'jumlah' => $validated['stok'],
                'keterangan' => 'Stok awal produk baru',
                'user_id' => Auth::id(),
                'created_at' => now(),
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', "Buah {$produk->nama_produk} berhasil ditambahkan!");
    }

    public function editProduct($id)
    {
        $produk = Produk::findOrFail($id);
        $kategoriList = Kategori::all();
        return view('admin.products.edit', compact('produk', 'kategoriList'));
    }

    public function updateProduct(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'satuan' => 'required|in:kg,pcs',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'aktif' => 'nullable|boolean',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = 'buah_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('products', $filename, 'public');
            $produk->gambar = $filename;
        }

        $produk->nama_produk = $validated['nama_produk'];
        $produk->id_kategori = $validated['id_kategori'];
        $produk->satuan = $validated['satuan'];
        $produk->harga = $validated['harga'];
        $produk->deskripsi = $validated['deskripsi'] ?? null;
        $produk->aktif = $request->has('aktif') ? 1 : 0;
        $produk->save();

        return redirect()->route('admin.products.index')->with('success', "Informasi buah {$produk->nama_produk} berhasil diperbarui!");
    }

    public function toggleProductStatus($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->aktif = !$produk->aktif;
        $produk->save();

        $status = $produk->aktif ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Buah {$produk->nama_produk} berhasil {$status}.");
    }

    /**
     * Input Stok Masuk & Koreksi Stok (Tercatat di riwayat_stok)
     */
    public function stockAdjustment(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:produk,id_produk',
            'jenis' => 'required|in:masuk,koreksi',
            'jumlah' => 'required|numeric',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $produk = Produk::findOrFail($request->id_produk);
        $jenis = $request->jenis;
        $jumlah = (float) $request->jumlah;

        if ($jenis === 'masuk') {
            if ($jumlah <= 0) {
                return back()->with('error', 'Jumlah stok masuk harus lebih dari 0.');
            }
            $produk->increment('stok', $jumlah);
            $catatanJumlah = $jumlah;
            $ket = $request->keterangan ?: 'Penambahan stok masuk';
        } else {
            // Koreksi stok (bisa selisih positif/negatif atau set nilai baru)
            $selisih = $jumlah; // jika koreksi menambahkan/mengurangi sejumlah tertentu
            $produk->stok += $selisih;
            if ($produk->stok < 0) $produk->stok = 0;
            $produk->save();
            $catatanJumlah = $selisih;
            $ket = $request->keterangan ?: 'Koreksi penyesuaian stok fisik';
        }

        RiwayatStok::create([
            'id_produk' => $produk->id_produk,
            'jenis' => $jenis,
            'jumlah' => $catatanJumlah,
            'keterangan' => $ket,
            'user_id' => Auth::id(),
            'created_at' => now(),
        ]);

        return back()->with('success', "Stok buah {$produk->nama_produk} berhasil diperbarui. Stok saat ini: {$produk->stok} {$produk->satuan}.");
    }

    public function stockHistory(Request $request)
    {
        $query = RiwayatStok::with(['produk', 'user']);

        if ($request->filled('id_produk')) {
            $query->where('id_produk', $request->id_produk);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $history = $query->latest('id_riwayat')->paginate(20)->withQueryString();
        $products = Produk::orderBy('nama_produk')->get();

        return view('admin.products.stock-history', compact('history', 'products'));
    }

    /* ========================================================
     * 2. CRUD KATEGORI
     * ======================================================== */
    public function categories()
    {
        $categories = Kategori::withCount('produk')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori',
        ]);

        Kategori::create(['nama_kategori' => $request->nama_kategori]);
        return back()->with('success', "Kategori '{$request->nama_kategori}' berhasil ditambahkan.");
    }

    public function updateCategory(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori,' . $id . ',id_kategori',
        ]);

        $kategori->update(['nama_kategori' => $request->nama_kategori]);
        return back()->with('success', "Kategori berhasil diperbarui.");
    }

    public function destroyCategory($id)
    {
        $kategori = Kategori::withCount('produk')->findOrFail($id);
        if ($kategori->produk_count > 0) {
            return back()->with('error', "Kategori tidak dapat dihapus karena masih memiliki {$kategori->produk_count} produk terkait.");
        }

        $kategori->delete();
        return back()->with('success', "Kategori berhasil dihapus.");
    }

    /* ========================================================
     * 3. CRUD PROMO (DISKON BUATAN OWNER)
     * ======================================================== */
    public function promos()
    {
        $promos = Promo::with('produk')->latest()->paginate(15);
        $products = Produk::where('aktif', 1)->orderBy('nama_produk')->get();
        return view('admin.promos.index', compact('promos', 'products'));
    }

    public function storePromo(Request $request)
    {
        $validated = $request->validate([
            'nama_promo' => 'required|string|max:255',
            'id_produk' => 'nullable|exists:produk,id_produk',
            'persen_diskon' => 'required|numeric|min:0.1|max:100',
            'min_jumlah' => 'required|numeric|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'aktif' => 'nullable|boolean',
        ]);

        Promo::create([
            'nama_promo' => $validated['nama_promo'],
            'id_produk' => $validated['id_produk'] ?: null,
            'persen_diskon' => $validated['persen_diskon'],
            'min_jumlah' => $validated['min_jumlah'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'aktif' => $request->has('aktif') ? 1 : 0,
        ]);

        return back()->with('success', "Promo '{$validated['nama_promo']}' berhasil dibuat!");
    }

    public function updatePromo(Request $request, $id)
    {
        $promo = Promo::findOrFail($id);
        $validated = $request->validate([
            'nama_promo' => 'required|string|max:255',
            'id_produk' => 'nullable|exists:produk,id_produk',
            'persen_diskon' => 'required|numeric|min:0.1|max:100',
            'min_jumlah' => 'required|numeric|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'aktif' => 'nullable|boolean',
        ]);

        $promo->update([
            'nama_promo' => $validated['nama_promo'],
            'id_produk' => $validated['id_produk'] ?: null,
            'persen_diskon' => $validated['persen_diskon'],
            'min_jumlah' => $validated['min_jumlah'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'aktif' => $request->has('aktif') ? 1 : 0,
        ]);

        return back()->with('success', "Promo berhasil diperbarui!");
    }

    public function destroyPromo($id)
    {
        $promo = Promo::findOrFail($id);
        $promo->delete();
        return back()->with('success', "Promo berhasil dihapus.");
    }

    /* ========================================================
     * 4. CRUD AREA ANTAR (ONGKIR)
     * ======================================================== */
    public function deliveryAreas()
    {
        $areas = AreaAntar::all();
        return view('admin.areas.index', compact('areas'));
    }

    public function storeDeliveryArea(Request $request)
    {
        $validated = $request->validate([
            'nama_area' => 'required|string|max:255',
            'ongkir' => 'required|numeric|min:0',
            'aktif' => 'nullable|boolean',
        ]);

        AreaAntar::create([
            'nama_area' => $validated['nama_area'],
            'ongkir' => $validated['ongkir'],
            'aktif' => $request->has('aktif') ? 1 : 0,
        ]);

        return back()->with('success', "Area antar '{$validated['nama_area']}' berhasil ditambahkan.");
    }

    public function updateDeliveryArea(Request $request, $id)
    {
        $area = AreaAntar::findOrFail($id);
        $validated = $request->validate([
            'nama_area' => 'required|string|max:255',
            'ongkir' => 'required|numeric|min:0',
            'aktif' => 'nullable|boolean',
        ]);

        $area->update([
            'nama_area' => $validated['nama_area'],
            'ongkir' => $validated['ongkir'],
            'aktif' => $request->has('aktif') ? 1 : 0,
        ]);

        return back()->with('success', "Area antar berhasil diperbarui.");
    }

    public function destroyDeliveryArea($id)
    {
        $area = AreaAntar::findOrFail($id);
        $area->delete();
        return back()->with('success', "Area antar berhasil dihapus.");
    }

    /* ========================================================
     * 5. MANAJEMEN AKUN (KASIR & PELANGGAN)
     * Sesuai PDF: D = nonaktifkan (tanpa hapus data riwayat)
     * ======================================================== */
    public function users(Request $request)
    {
        $tab = $request->get('tab', 'kasir');

        $kasirList = User::where('role', 'kasir')->latest()->get();
        $pelangganList = Pelanggan::with('user')->latest()->get();

        return view('admin.users.index', compact('tab', 'kasirList', 'pelangganList'));
    }

    public function storeKasir(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'kasir',
            'aktif' => true,
        ]);

        return back()->with('success', "Akun kasir '{$validated['name']}' berhasil dibuat!");
    }

    public function updateKasir(Request $request, $id)
    {
        $user = User::where('role', 'kasir')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        return back()->with('success', "Data kasir berhasil diperbarui.");
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        if ($user->isAdmin()) {
            return back()->with('error', 'Akun Pemilik (Admin) tidak dapat dinonaktifkan.');
        }

        $user->aktif = !$user->aktif;
        $user->save();

        $status = $user->aktif ? 'diaktifkan kembali' : 'dinonaktifkan';
        return back()->with('success', "Akun {$user->name} berhasil {$status}.");
    }

    /* ========================================================
     * 6. DAFTAR SEMUA TRANSAKSI & DETAIL
     * ======================================================== */
    public function allOrders(Request $request)
    {
        $query = Pesanan::with(['pelanggan', 'pembayaran', 'kasir', 'area']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('sumber')) {
            $query->where('sumber', $request->sumber);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /* ========================================================
     * 7. LAPORAN PENJUALAN LENGKAP & BUAH TERLARIS
     * ======================================================== */
    public function salesReport(Request $request)
    {
        $period = $request->get('period', 'month'); // day, week, month, custom
        $today = now()->toDateString();

        $query = Pesanan::with(['details.produk', 'pelanggan', 'pembayaran', 'kasir'])
            ->where('status', 'selesai');

        if ($period === 'day') {
            $query->whereDate('created_at', $today);
            $label = 'Hari Ini (' . date('d M Y') . ')';
        } elseif ($period === 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            $label = 'Minggu Ini (' . now()->startOfWeek()->format('d M') . ' - ' . now()->endOfWeek()->format('d M Y') . ')';
        } elseif ($period === 'month') {
            $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            $label = 'Bulan Ini (' . now()->translatedFormat('F Y') . ')';
        } elseif ($period === 'custom') {
            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }
            $label = 'Periode ' . ($request->start_date ?? 'Awal') . ' s/d ' . ($request->end_date ?? 'Sekarang');
        }

        $orders = $query->latest()->get();

        $totalOmzet = $orders->sum('total_bayar');
        $totalDiskon = $orders->sum('total_diskon');
        $totalOngkir = $orders->sum('ongkir');
        $totalTransaksi = $orders->count();

        // Buah Terlaris dalam periode ini
        $orderIds = $orders->pluck('id_pesanan');
        $bestSellers = DetailPesanan::select('id_produk', DB::raw('SUM(jumlah) as total_kg'), DB::raw('SUM(subtotal) as total_omzet_produk'))
            ->whereIn('id_pesanan', $orderIds)
            ->groupBy('id_produk')
            ->orderByDesc('total_kg')
            ->with('produk')
            ->get();

        return view('admin.reports.sales', compact(
            'period',
            'label',
            'orders',
            'totalOmzet',
            'totalDiskon',
            'totalOngkir',
            'totalTransaksi',
            'bestSellers'
        ));
    }
}

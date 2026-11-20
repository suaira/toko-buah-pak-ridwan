<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'id_kategori',
        'nama_produk',
        'deskripsi',
        'satuan',
        'harga',
        'stok',
        'gambar',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'float',
            'stok' => 'float',
            'aktif' => 'boolean',
        ];
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function promos()
    {
        return $this->hasMany(Promo::class, 'id_produk', 'id_produk');
    }

    public function riwayatStok()
    {
        return $this->hasMany(RiwayatStok::class, 'id_produk', 'id_produk');
    }

    /**
     * Cari promo aktif untuk produk ini
     */
    public function getActivePromoAttribute()
    {
        $today = now()->toDateString();
        // Cek promo spesifik untuk produk ini
        $promo = Promo::where('aktif', 1)
            ->where('id_produk', $this->id_produk)
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->first();

        // Jika tidak ada promo spesifik, cek promo global (id_produk null)
        if (!$promo) {
            $promo = Promo::where('aktif', 1)
                ->whereNull('id_produk')
                ->whereDate('tanggal_mulai', '<=', $today)
                ->whereDate('tanggal_selesai', '>=', $today)
                ->first();
        }

        return $promo;
    }
}

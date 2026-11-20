<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    use HasFactory;

    protected $table = 'detail_pesanan';
    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_pesanan',
        'id_produk',
        'jumlah',
        'harga_satuan',
        'id_promo',
        'persen_diskon',
        'potongan',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'float',
            'harga_satuan' => 'float',
            'persen_diskon' => 'float',
            'potongan' => 'float',
            'subtotal' => 'float',
        ];
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class, 'id_promo', 'id_promo');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $table = 'promo';
    protected $primaryKey = 'id_promo';

    protected $fillable = [
        'nama_promo',
        'id_produk',
        'persen_diskon',
        'min_jumlah',
        'tanggal_mulai',
        'tanggal_selesai',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'persen_diskon' => 'float',
            'min_jumlah' => 'float',
            'aktif' => 'boolean',
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function isValidFor($jumlah): bool
    {
        $today = now()->toDateString();
        if (!$this->aktif) return false;
        if ($this->tanggal_mulai && $this->tanggal_mulai->toDateString() > $today) return false;
        if ($this->tanggal_selesai && $this->tanggal_selesai->toDateString() < $today) return false;
        if ($jumlah < $this->min_jumlah) return false;
        return true;
    }
}

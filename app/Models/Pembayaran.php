<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_pesanan',
        'metode',
        'jumlah_bayar',
        'bukti_transfer',
        'status',
        'id_kasir',
        'dibayar_pada',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_bayar' => 'float',
            'dibayar_pada' => 'datetime',
        ];
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'id_kasir', 'id');
    }
}

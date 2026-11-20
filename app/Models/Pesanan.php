<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';

    protected $fillable = [
        'kode_pesanan',
        'id_pelanggan',
        'id_kasir',
        'sumber',
        'metode_ambil',
        'id_area',
        'alamat_antar',
        'catatan',
        'status',
        'subtotal',
        'total_diskon',
        'ongkir',
        'total_bayar',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'float',
            'total_diskon' => 'float',
            'ongkir' => 'float',
            'total_bayar' => 'float',
        ];
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'id_kasir', 'id');
    }

    public function area()
    {
        return $this->belongsTo(AreaAntar::class, 'id_area', 'id_area');
    }

    public function details()
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan', 'id_pesanan');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pesanan', 'id_pesanan');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'menunggu' => 'badge bg-warning text-dark',
            'dikonfirmasi' => 'badge bg-info text-white',
            'diproses' => 'badge bg-primary text-white',
            'siap' => 'badge bg-secondary text-white',
            'dikirim' => 'badge bg-info text-dark',
            'selesai' => 'badge bg-success text-white',
            'dibatalkan' => 'badge bg-danger text-white',
            'ditolak' => 'badge bg-dark text-white',
            default => 'badge bg-secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu' => 'Menunggu Konfirmasi',
            'dikonfirmasi' => 'Dikonfirmasi',
            'diproses' => 'Sedang Diproses',
            'siap' => 'Siap Diambil di Toko',
            'dikirim' => 'Sedang Diantar Kurir',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            'ditolak' => 'Ditolak',
            default => ucfirst($this->status),
        };
    }
}

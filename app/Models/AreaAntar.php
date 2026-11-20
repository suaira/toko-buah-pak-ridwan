<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaAntar extends Model
{
    use HasFactory;

    protected $table = 'area_antar';
    protected $primaryKey = 'id_area';

    protected $fillable = [
        'nama_area',
        'ongkir',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'ongkir' => 'float',
            'aktif' => 'boolean',
        ];
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_area', 'id_area');
    }
}

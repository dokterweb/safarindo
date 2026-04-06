<?php

namespace App\Models;

use App\Models\Pembelian_detail;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['nama_produk', 'standar_stok', 'stok', 'unit_id', 'harga_beli', 'harga_jual', 'catatan', 'foto_produk'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function pembelianDetails()
    {
        return $this->hasMany(Pembelian_detail::class);
    }

    public function keluar_details()
    {
        return $this->hasMany(Keluarproduk_detail::class);
    }
}
    


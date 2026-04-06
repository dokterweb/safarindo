<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembelian_detail extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['pembelian_id', 'produk_id', 'harga', 'qty', 'diskon', 'total'];
    
    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}

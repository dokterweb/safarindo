<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jamaah_paket_produk extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['jamaah_id', 'paket_id', 'produk_id', 'qty_diambil', 'tanggal'];

    
}

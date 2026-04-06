<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Keluarproduk_detail extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['keluarproduk_id', 'produk_id', 'harga', 'qty', 'diskon', 'total'];

      // 🔗 ke parent
      public function keluarproduk()
      {
          return $this->belongsTo(Keluarproduk::class);
      }
  
      // 🔗 ke produk
      public function produk()
      {
          return $this->belongsTo(Produk::class);
      }
}

<?php

namespace App\Models;

use App\Models\Jamaah;
use App\Models\Keluarproduk_detail;
use App\Models\Paket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Keluarproduk extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['jamaah_id', 'paket_id', 'tanggal', 'tax', 'diskon', 'shipping', 'statuskeluar', 'metodekirim', 'grand_total','alamat', 'catatan'];

     // 🔗 relasi ke jamaah
     public function jamaah()
     {
         return $this->belongsTo(Jamaah::class);
     }
 
     // 🔗 relasi ke paket
     public function paket()
     {
         return $this->belongsTo(Paket::class);
     }
 
     // 🔗 relasi ke detail
     public function details()
     {
         return $this->hasMany(Keluarproduk_detail::class);
     }
}

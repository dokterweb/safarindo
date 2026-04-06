<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembelian extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['supplier_id', 'paket_id', 'tanggal', 'tax', 'diskon', 'shipping', 'grand_total','catatan'];

        // 🔥 RELASI
        public function details()
        {
            return $this->hasMany(Pembelian_detail::class);
        }
    
        public function supplier()
        {
            return $this->belongsTo(Supplier::class);
        }
    
        public function paket()
        {
            return $this->belongsTo(Paket::class);
        }

}

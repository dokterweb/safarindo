<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent_transaksi extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['agent_id', 'jamaah_id', 'paket_id', 'jumlah', 'bukti_bayar'];
 
     // 🔥 RELASI
     public function agent()
     {
         return $this->belongsTo(Agent::class);
     }
 
     public function jamaah()
     {
         return $this->belongsTo(Jamaah::class);
     }
 
     public function paket()
     {
         return $this->belongsTo(Paket::class);
     }
     
}

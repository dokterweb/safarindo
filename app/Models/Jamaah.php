<?php

namespace App\Models;

use App\Models\Agent;
use App\Models\Paket;
use App\Models\Pembayaran;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jamaah extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['nik', 'nama_jamaah', 'no_hp', 'kota', 'kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'catatan', 'nama_jamaah_pasport', 'no_pasport', 'penerbit', 'pasport_aktif', 'pasport_expired', 'foto_jamaah', 'foto_ktp', 'foto_kk', 'foto_pasport1', 'foto_pasport2', 'paket_id_draft','paket_id', 'agent_id', 'status', 'lunas'];
    
    

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }
    
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
    
    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function diskons()
    {
        return $this->hasMany(Diskon::class);
    }

    public function keluarproduks()
    {
        return $this->hasMany(Keluarproduk::class);
    }

    
    public function agentTransaksis()
    {
        return $this->hasMany(Agent_transaksi::class);
    }
}

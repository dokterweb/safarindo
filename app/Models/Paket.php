<?php

namespace App\Models;

use App\Models\Hotel;
use App\Models\Maskapai;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paket extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['nama_paket', 'tgl_berangkat', 'jlh_hari', 'status', 'maskapai_id', 'rute', 'lokasi_berangkat', 'kuota', 'jenis_paket', 'hotel_makah_id', 'hotel_madinah_id', 'hotel_transit_id', 'harga_paket', 'include_desc', 'exclude_desc', 'syaratketentuan', 'catatan', 'foto_paket'];
    
    public function maskapai()
    {
        return $this->belongsTo(Maskapai::class);
    }

    public function hotelMakah()
    {
        return $this->belongsTo(Hotel::class, 'hotel_makah_id');
    }

    public function hotelMadinah()
    {
        return $this->belongsTo(Hotel::class, 'hotel_madinah_id');
    }

    public function hotelTransit()
    {
        return $this->belongsTo(Hotel::class, 'hotel_transit_id');
    }

    public function jamaahs()
    {
        return $this->hasMany(Jamaah::class);
    }

    public function keluarproduks()
    {
        return $this->hasMany(Keluarproduk::class);
    }
}

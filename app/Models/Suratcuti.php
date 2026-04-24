<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Suratcuti extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['no_surat', 'paket_id', 'jamaah_id', 'nama_kantor', 'alamat_kantor', 'jabatan', 'catatan'];

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }

    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class);
    }
}

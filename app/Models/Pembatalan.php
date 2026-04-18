<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembatalan extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['jamaah_id', 'paket_id', 'paket_tujuan_id', 'jenis', 'pengembalian_uang', 'keterangan', 'status', 'disetujui_oleh', 'disetujui_pada'];
    
    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class);
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }

    public function paketTujuan()
    {
        return $this->belongsTo(Paket::class, 'paket_tujuan_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}

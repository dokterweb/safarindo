<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengeluaranbulanantrx extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['jumlah', 'pengeluaran_id', 'keterangan'];
    //'jumlah', 'nama_pengeluaran', 'keterangan'

    public function pengeluaran()
    {
        return $this->belongsTo(Pengeluaranbulanan::class, 'pengeluaran_id');
    }
}

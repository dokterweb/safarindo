<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    use SoftDeletes;
    protected $table = 'transaksis';
    protected $fillable = ['group_id','referensi_id','paket_id','jumlah','keterangan'];

    public function group()
    {
        return $this->belongsTo(Group_transaksi::class, 'group_id');
    }

}

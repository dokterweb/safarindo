<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group_transaksi extends Model
{
    protected $table = 'group_transaksi';

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'group_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['nama_supplier', 'no_hp', 'email', 'kota', 'alamat', 'catatan'];

    public function pembelians()
    {
        return $this->hasMany(Pembelian::class);
    }
}

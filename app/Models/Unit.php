<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['nama_unit', 'satuan_unit'];

    public function produks()
    {
        return $this->hasMany(Produk::class);
    }
}

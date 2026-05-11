<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tipe_kamar extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['nama_kamar', 'harga_kamar'];

    public function jamaahs()
    {
        return $this->hasMany(Jamaah::class);
    }
}

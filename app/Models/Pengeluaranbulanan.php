<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengeluaranbulanan extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['nama_pengeluaran'];

    public function pengeluaranbulanantrxs()
    {
        return $this->hasMany(Pengeluaranbulanantrx::class, 'pengeluaran_id');
    }

}

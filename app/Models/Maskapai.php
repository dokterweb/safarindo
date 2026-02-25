<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Maskapai extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['nama_maskapai', 'rute_terbang', 'lama_perjalanan', 'harga_tiket', 'catatan_keberangkatan'];

}
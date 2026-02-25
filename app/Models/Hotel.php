<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hotel extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['nama_hotel', 'lokasi_hotel', 'kontak_hotel', 'email_hotel', 'rating_hotel', 'harga_hotel', 'catatan_hotel'];
    
}

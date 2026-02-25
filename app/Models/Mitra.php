<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mitra extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['nama_mitra', 'no_hp', 'email', 'kota', 'kelamin', 'status', 'alamat', 'catatan'];
    
}

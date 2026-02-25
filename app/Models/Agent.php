<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agent extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['user_id', 'nik', 'no_hp', 'kota', 'kelamin', 'tempat_lahir', 'tanggal_lahir', 'status','alamat', 'catatan'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

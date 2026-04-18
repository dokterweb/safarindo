<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agent extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['user_id', 'nik', 'no_hp', 'kota', 'kelamin', 'tempat_lahir', 'tanggal_lahir','fee_agent', 'status','alamat', 'catatan'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function transaksis()
    {
        return $this->hasMany(Agent_transaksi::class);
    }
    
    public function jamaahs()
    {
        return $this->hasMany(Jamaah::class);
    }
}

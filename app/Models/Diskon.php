<?php

namespace App\Models;

use App\Models\Jamaah;
use App\Models\Paket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Diskon extends Model
{

    use HasFactory, SoftDeletes;
    protected $fillable=['paket_id', 'jamaah_id', 'user_id','jumlah_diskon','status'];
    
    
    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class);
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

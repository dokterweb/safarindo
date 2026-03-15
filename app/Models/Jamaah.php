<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jamaah extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['nik', 'nama_jamaah', 'no_hp', 'kota', 'kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'catatan', 'nama_jamaah_pasport', 'no_pasport', 'penerbit', 'pasport_aktif', 'pasport_expired', 'foto_jamaah', 'foto_ktp', 'foto_kk', 'foto_pasport1', 'foto_pasport2'];
    
   /*  public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    } */

}

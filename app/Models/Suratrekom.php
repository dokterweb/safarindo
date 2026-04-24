<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Suratrekom extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['no_surat', 'jamaah_id', 'paket_id', 'kantor_imigrasi', 'alamat_imigrasi', 'catatan'];

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }

    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class);
    }
    
    public static function generateNoSuratBulanan()
    {
        $now = Carbon::now();

        // Hitung jumlah surat di bulan & tahun yang sama
        $count = self::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();

        // Nomor urut (increment)
        $noUrut = $count + 1;

        // Format 6 digit
        $idFormat = str_pad($noUrut, 6, '0', STR_PAD_LEFT);

        // Bulan romawi
        $bulanRomawi = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];

        $bulan = $bulanRomawi[$now->month];
        $tahun = $now->year;

        return "{$idFormat}/SR/SAU/{$bulan}/{$tahun}";
    }
}

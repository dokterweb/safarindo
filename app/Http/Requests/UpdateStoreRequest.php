<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['admin']);
    }

    public function rules(): array
    {
        return [
            'nama_paket'        => ['required', 'string', 'max:255'],
            'tgl_berangkat'     => ['required','date'],
            'jlh_hari'          => ['required','integer'],
            'status'            => ['required', 'string', 'in:active,completed'], 
            'maskapai_id'       => ['required','integer'],
            'rute'              => ['required', 'string', 'in:direct,transit'], 
            'lokasi_berangkat'  => ['required', 'string', 'max:255'],
            'kuota'             => ['required','integer'],
            'jenis_paket'       => ['required', 'string', 'max:255'],
            'hotel_makah_id'    => ['required','integer'],
            'hotel_madinah_id'  => ['required','integer'],
            'hotel_transit_id'  => ['required','integer'],
            'harga_paket'       => ['required','integer'],
            'include_desc'      => ['sometimes','string','max:65535'],
            'exclude_desc'      => ['sometimes','string','max:65535'],
            'syaratketentuan'   => ['sometimes','string','max:65535'],
            'catatan'           => ['sometimes','string','max:65535'],
            'foto_paket'        => ['sometimes','image','mimes:png,jpg,jpeg'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJamaahRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nik'           => 'required|digits:16|unique:jamaahs,nik',
            'nama_jamaah'   => 'required|string|max:255',
            'no_hp'         => 'required|string|max:15',
            'kota'          => 'required|string|max:255',
            'kelamin'       => 'required|in:laki-laki,perempuan',
            'tempat_lahir'  => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'agent_id'      => 'required|integer',
            'alamat'        => 'required|string',
            'catatan'       => 'nullable|string',
    
            // Passport
            'nama_jamaah_pasport' => 'nullable|string|max:255',
            'no_pasport' => 'nullable|string|max:255|unique:jamaahs,no_pasport',
            'penerbit' => 'nullable|string|max:255',
            'pasport_aktif' => 'nullable|date',
            'pasport_expired' => 'nullable|date|after:pasport_aktif',
    
            // File Upload
            'foto_jamaah' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_ktp' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_kk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_pasport1' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_pasport2' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}

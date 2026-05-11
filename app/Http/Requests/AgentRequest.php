<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['admin']);
    }

    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255', 'unique:users,name'],
            'avatar'    => ['nullable','image','mimes:png,jpg,jpeg'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:6'],
            'nik'       => ['required', 'string', 'max:255'],
            'no_hp'     => ['required', 'string', 'max:255'],
            'kota'      => ['required', 'string', 'max:255'],
            'kelamin'   => ['required', 'in:laki-laki,perempuan'], 
            'tempat_lahir'  => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['required','date'],
            'status'    => ['required', 'in:active,non_active'], 
            'fee_agent' => ['required','integer'],
            'alamat'    => ['required','string','max:255'],
            'catatan'   => ['nullable','string','max:255'],
        ];
    }
}

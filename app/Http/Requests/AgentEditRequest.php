<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AgentEditRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasAnyRole(['admin']);
    }

  
    public function rules(): array
    {
        $agent = $this->route('agent');
        return [
             'name'             => ['required', 'string', 'max:255'],
            'avatar'            => ['nullable','image','mimes:png,jpg,jpeg'],
            'email'             => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($agent->user->id),],
            'password'          => ['nullable', 'string', 'min:6'],
            'nik'               => ['required', 'string', 'max:255'],
            'no_hp'             => ['required', 'string', 'max:255'],
            'kota'              => ['required', 'string', 'max:255'],
            'kelamin'           => ['required', 'string', 'in:laki-laki,perempuan'], 
            'tempat_lahir'      => ['required', 'string', 'max:255'],
            'tanggal_lahir'     => ['required','date'],
            'status'            => ['required', 'string', 'in:active,non_active'], 
            'foto_agent'        => ['nullable','string','max:255'],
            'alamat'            => ['required','string','max:255'],
            'catatan'           => ['nullable','string','max:255'],
        ];
    }
}

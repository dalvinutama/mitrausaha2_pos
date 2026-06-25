<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_toko'          => 'required|string|max:255',
            'tagline'            => 'nullable|string|max:255',
            'alamat'             => 'required|string',
            'telepon'            => 'nullable|string|max:20',
            'email'              => 'nullable|email|max:255',
            'kota_ttd'           => 'required|string|max:100',
            'nama_kepala_gudang' => 'nullable|string|max:100',
            'logo'               => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}

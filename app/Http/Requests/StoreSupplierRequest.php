<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_supplier' => 'required|string|max:255',
            'nama_pic'      => 'required|string|max:255',
            'no_hp'         => 'required|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_supplier.required' => 'Nama Perusahaan wajib diisi!',
            'nama_pic.required'      => 'Nama PIC wajib diisi!',
            'no_hp.required'         => 'Nomor HP wajib diisi!',
        ];
    }
}

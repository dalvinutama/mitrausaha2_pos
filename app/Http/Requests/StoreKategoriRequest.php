<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKategoriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_kategori' => 'required|unique:kategoris,nama_kategori|max:255',
            'prefix_sku'    => 'required|unique:kategoris,prefix_sku|max:10',
            'deskripsi'     => 'nullable',
        ];
    }
}

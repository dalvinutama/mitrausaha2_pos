<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKategoriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_kategori' => 'required|max:255|unique:kategoris,nama_kategori,' . $this->route('id'),
            'prefix_sku'    => 'required|max:10|unique:kategoris,prefix_sku,' . $this->route('id'),
            'deskripsi'     => 'nullable',
        ];
    }
}

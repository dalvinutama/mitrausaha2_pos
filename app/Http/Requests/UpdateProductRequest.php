<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kategori_id'       => 'required|exists:kategoris,id',
            'nama_barang'       => 'required|string|max:255',
            'satuan'            => 'required|string|max:50',
            'harga_beli'        => 'required|numeric|min:0',
            'harga_jual'        => 'required|numeric|min:0',
            'barcode'           => 'nullable|string|unique:products,barcode,' . $this->route('id'),
            'lead_time_hari'    => 'required|integer|min:1',
            'tipe_safety_stock' => 'required|in:manual,otomatis',
            'safety_stock'      => 'nullable|integer|min:0',
        ];
    }
}

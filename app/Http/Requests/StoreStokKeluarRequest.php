<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStokKeluarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kategori_keluar' => 'required',
            'tujuan'          => 'nullable|string|max:255',
            'outbound_date'   => 'required|date',
            'product_id'      => 'required|array',
            'qty'             => 'required|array',
            'price'           => 'required|array',
        ];
    }
}

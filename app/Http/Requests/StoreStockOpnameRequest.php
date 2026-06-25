<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockOpnameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'periode'     => 'required',
            'product_id'  => 'required|array',
            'stok_fisik'  => 'required|array',
        ];
    }
}

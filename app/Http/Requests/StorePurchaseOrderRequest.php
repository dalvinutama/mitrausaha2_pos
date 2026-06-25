<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal'     => 'required|date',
            'product_id'  => 'required|array',
            'qty'         => 'required|array',
            'qty.*'       => 'required|numeric|min:1',
            'price'       => 'required|array',
            'price.*'     => 'required|numeric|min:0',
        ];
    }
}

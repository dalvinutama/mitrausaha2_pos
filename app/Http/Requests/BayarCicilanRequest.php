<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BayarCicilanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nominal'           => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|in:cash,transfer',
            'bukti_pembayaran'  => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        ];
    }
}

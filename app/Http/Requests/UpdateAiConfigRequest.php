<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAiConfigRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'auto_po_active'     => 'required|boolean',
            'daily_check_active' => 'required|boolean',
            'biaya_pesan'        => 'required|numeric|min:0',
        ];
    }
}

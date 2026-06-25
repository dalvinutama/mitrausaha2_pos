<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content'         => 'nullable|string|max:1000',
            'conversation_id' => 'nullable|exists:conversations,id',
            'file'            => 'nullable|file|max:10240',
        ];
    }
}

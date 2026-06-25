<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStokMasukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id'      => 'required|exists:suppliers,id',
            'tanggal'          => 'required|date',
            'no_referensi'     => 'nullable|string|max:255',
            'catatan'          => 'nullable|string',
            'tipe_pembayaran'  => 'required|in:tunai,tempo',
            'tanggal_tempo'    => 'nullable|required_if:tipe_pembayaran,tempo|date',
            'metode_pembayaran'=> 'required|in:cash,transfer',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|mimetypes:image/jpeg,image/png|max:2048',
            'dp_nominal'       => 'nullable|numeric|min:0',
            'po_id'            => 'nullable|exists:transactions,id',
            'product_id'       => 'required|array',
            'qty'              => 'required|array',
            'qty_rusak'        => 'nullable|array',
            'price'            => 'required|array',
        ];
    }
}

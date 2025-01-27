<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'coin' => 'required|string',
            'placea' => 'required|string',
            'amounta' => 'required|numeric',
            'placeb' => 'required|string',
            'amountb' => 'required|numeric',
            'customfeecoin' => 'nullable|string',
            'customfee' => 'nullable|numeric|min:0',
            'customtime' => 'required|date',
            'memo' => 'nullable|string',
        ];
    }
}

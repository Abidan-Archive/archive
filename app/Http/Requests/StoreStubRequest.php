<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStubRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'stubs.*.from' => 'required|numeric|lt:stubs.*.to',
            'stubs.*.to' => 'required|numeric|min:0|gt:stubs.*.from',
            'stubs.*.prompt' => 'nullable',
        ];
    }
}

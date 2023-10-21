<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'unique:events|max:255',
            'date' => 'date',
            'location' => 'max:255',
            'sources' => 'array',
            'sources.*' => 'file|mimes:mgpa,wav,mp4'
        ];
    }
}

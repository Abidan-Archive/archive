<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:events|max:255',
            'date' => 'required|date',
            'location' => 'required|max:255',
            'sources' => 'array',
            'sources.*' => 'file|mimes:mpga,wav,mp4,mpeg,mp3',
        ];
    }
}

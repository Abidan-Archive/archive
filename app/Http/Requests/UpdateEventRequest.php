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
            'name' => 'max:255|unique:events,id,'.$this->event->id,
            'date' => 'date',
            'location' => 'max:255',
            'sources' => 'array',
            'sources.*' => 'file|mimes:mpga,wav,mp4,mpeg,mp3'
        ];
    }
}

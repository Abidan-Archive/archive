<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'event_id' => 'required|exists:events,id',
            'stub_id' => 'exists:stubs,id',
            'dialogues.*.speaker' => 'required|string|max:255',
            'dialogues.*.line' => 'required|string|max:4000',
            'footnote' => 'string|max:255',
            'source_label' => 'required_with:source_href|string|max:255',
            'source_href' => 'sometimes|url|max:255',
            'date' => 'date',
            'tags' => 'array|exists:tags,name',
            'turnstile' => ['required', 'turnstile'],
        ];
    }

    /*
    * Gives alternative names to attributes for error messages
    *
    * @return array<string, string>
    */
    public function attributes(): array
    {
        return [
            'event_id' => 'event',
            'stub_id' => 'stub',
            'dialogues.*.speaker' => 'dialogue speaker',
            'dialogues.*.line' => 'dialogue line',
            'source_label' => 'source name',
            'source_href' => 'source url',
        ];
    }
}

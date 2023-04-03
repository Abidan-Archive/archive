<?php

namespace App\Http\Requests;

use App\Models\User;

class UnlikeRequest extends LikeRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('unlike', $this->likeable());
    }

}

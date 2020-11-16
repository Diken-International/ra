<?php

namespace App\Http\Requests\reviewService;

use Illuminate\Foundation\Http\FormRequest;

class ServiceReviewRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token_review' =>  'required',
        ];
    }
}

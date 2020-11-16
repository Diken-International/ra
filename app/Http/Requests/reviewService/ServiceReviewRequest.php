<?php

namespace App\Http\Requests\reviewService;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class ServiceReviewRequest extends ApiRequest
{

    public function rules()
    {
        return [

            'token_review'  => 'required',
            'star' => 'required|numeric|min:1|max:5',
            'description' => 'required|string',
        ];
    }
}
<?php

namespace App\Http\Requests\ReviewService;

use App\Http\Requests\ApiRequest;

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

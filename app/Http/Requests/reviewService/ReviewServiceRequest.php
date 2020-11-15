<?php

namespace App\Http\Requests\reviewService;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class ReviewServiceRequest extends ApiRequest
{

    public function rules()
    {
        return [

            'service_id' => 'required',
            'review_id'  => 'required',
        ];
    }
}
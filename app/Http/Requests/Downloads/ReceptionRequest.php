<?php

namespace App\Http\Requests\Downloads;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class ReceptionRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'product_user' => 'required',
            'services' => 'array',
            'functionality' => 'required',
            'foam' => 'required'
        ];
    }
}

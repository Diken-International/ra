<?php

namespace App\Http\Requests\Seach_Product;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class SeachProductRequest extends ApiRequest
{

    public function rules()
    {
        return [

            'serial_number' => 'required',
        ];
    }
}
<?php

namespace App\Http\Requests\Client;

use App\Helpers\AvailableHelper;
use App\Http\Requests\ApiRequest;
use App\Models\Products;
use Illuminate\Validation\Rule;

class ProductAddRequest extends ApiRequest
{

    public function rules()
    {
        return [
            'product_id' => ['required', Rule::in((new AvailableHelper())->availableAllEntities(Products::class))],
            'status' => 'required',
            'type_product' => ['required', Rule::in((new AvailableHelper())->availableTypeProducts())]
        ];
    }
}

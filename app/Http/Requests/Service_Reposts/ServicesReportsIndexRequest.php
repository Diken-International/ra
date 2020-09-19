<?php

namespace App\Http\Requests\Service_Reposts;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class ServicesReportsIndexRequest extends ApiRequest
{

    public function rules()
    {
        return [

            'service_begin' => 'required|date',
            'service_end' => 'required|date'
        ];
    }
}

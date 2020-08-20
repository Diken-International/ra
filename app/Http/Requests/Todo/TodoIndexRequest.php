<?php

namespace App\Http\Requests\Todo;

use App\Http\Requests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;

class TodoIndexRequest extends ApiRequest
{

    public function rules()
    {
        return [
            'date' => 'required|date',
            'start_week' => 'required|date',
            'end_week' => 'required|date'
        ];
    }
}

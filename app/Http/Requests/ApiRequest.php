<?php

namespace App\Http\Requests;

use App\Helpers\CustomResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;

class ApiRequest extends FormRequest
{

    public function __construct(
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    )
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->current_user = (Auth::user()) ? Auth::user() : null; // Special for Custom Requests
    }

    public function authorize()
    {
        return true;
    }

    protected function failedValidation(Validator $validator) {

        /**
        $ers = $validator->errors()->getMessages();
        $msg = [];


        foreach ($ers as $e) {
            $msg = array_merge($msg, $e);
        }


        $response = [
            'status'     => 400,
            'data' 		 => [],
            'message' 	 => $msg
        ];

        throw new HttpResponseException(response()->json($response, 200));
         **/

        throw new HttpResponseException(CustomResponse::error(
            'Error al validar la información proporcionada',
            $validator->errors()
        ));

    }

    /**
     * Expects JSON request
     *
     * @return bool
     */
    public function expectsJson() {
        return true;
    }

}

<?php

namespace App\Http\Requests\User;

use App\Helpers\RoleHelper;
use App\Http\Requests\ApiRequest;
use Illuminate\Validation\Rule;

class UserRequest extends ApiRequest
{

    public function rules()
    {
        $method = $this->getMethod();

        return [
            'name'      => 'required',
            'last_name' => 'required',
            'email'     => ['required', 'email', Rule::unique('users')->ignore($this->user_id)],
            'password'  => [
                Rule::requiredIf(function () use ($method){
                    return ($method != 'POST') ? false : true;
                })],
            'role'      => ['required', Rule::in(RoleHelper::$available_roles)]
        ];
    }
}

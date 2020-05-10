<?php

namespace App\Http\Controllers;

use App\Helpers\CustomReponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function welcome(Request $request){

        $user = $request->current_user;

        if ($user->role = 'admin'){
            return CustomReponse::success("Bienvenido Administrador",['user' => $user]);
        }

        return CustomReponse::success("Bienvenido",['user' => $user]);
    }
}

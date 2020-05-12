<?php

namespace App\Http\Controllers;

use App\Helpers\CustomReponse;
use App\Models\BranchOffice;
use App\Models\User;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function welcome(Request $request){

        $user = $request->current_user;

        if ($user->role = 'admin'){
            return CustomReponse::success("Bienvenido Administrador",['user' => $user]);
        }

        return CustomReponse::success("Bienvenido",['user' => $user]);
    }

    public function createAdmin(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password' => 'required|string',
            'branch_office_name' => 'required'
        ]);

        if ($validator->fails()) {
            return CustomReponse::error('Error al validar', $validator->errors());
        }


        try{
            $result = DB::transaction(function () use($request){

                $branch_office = BranchOffice::create(['name' => $request->get('branch_office_name')]);
                $user = User::create([
                    'name' => $request->get('name'),
                    'last_name' => $request->get('last_name'),
                    'email' => $request->get('email'),
                    'password' => bcrypt($request->get('password')),
                    'role' => 'admin',
                    'branch_office_id' => $branch_office->id
                ]);

                return compact('user','branch_office');

            });

            return CustomReponse::success('Administrador creado correctamente', $result);
        }catch (\Exception $exception){
            return CustomReponse::error('No ha sido posible crear el administrador');
        }


    }
}

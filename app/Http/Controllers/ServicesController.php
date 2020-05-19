<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Helpers\CustomReponse;

use App\Models\Services;

class ServicesController extends Controller
{
    //
    public function createService(Request $request){

    	$validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'cost' => 'required',
            'extra_cost' => 'required',
            'total_cost' => 'required',
            'client_id'  => 'required',
            'technical_id' => 'required'

        ]);

        if ($validator->fails()) {
            return CustomReponse::error('Error al validar', $validator->errors());
        }

        
        try{
            $result = DB::transaction(function () use($request){

            	/*
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
                */
                dd( $request->all() );

            });

            return CustomReponse::success('Administrador creado correctamente', $result);
        }catch (\Exception $exception){
            return CustomReponse::error('No ha sido posible crear el administrador');
        }
        

    }
}

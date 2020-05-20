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
    public function store(Request $request){

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

            	$service = Services::create($request->all());
            	return compact('service');

            });

            return CustomReponse::success('Servicio creado correctamente', $result);

        }catch (\Exception $exception){

            return CustomReponse::error('El servicio no ha podido ser creado', $exception->getMessage());

        }
        

    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Helpers\CustomReponse;


use App\Models\Repairs;

class RepairsController extends Controller
{
    //
    public function index(Request $request){

    	$repair = Repairs::all();
    	dd($repair);
    }

    public function store(Request $request){

    	$validator = Validator::make($request->all(), [

            'code' => 'required',
            'name' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            

        ]);

        if ($validator->fails()) {
            return CustomReponse::error('Error al validar', $validator->errors());
        }

        try{

        	$repairs = DB::transaction(function() use($request){

        		$repair = Repairs::create( $request->all() );

        		return compact('repair');

        	});

        	return CustomReponse::success('Reparacion creada correctamente', $repairs);

        }catch(\Exception $exception){

        	return CustomReponse::error('La reparacion no se guardo correctamente', $exception->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Helpers\CustomReponse;

use App\Models\Messages;
use App\Models\Services;

class MessageController extends Controller
{
    //

    public function index(Request $request, $services_id){

        

    	$message = Messages::where([
            'services_id'=>$services_id,
            'branch_office_id'=>$request->current_user->branch_office_id
        ])->get();

    	return $message;
    }

    public function store(Request $request){

    	$validator = Validator::make($request->all(), [

            'message' => 'required',
            'autor_id' => 'required',
            'branch_office_id' => 'required',
            'priority' => 'required',
            'services_id' => 'required'

        ]);

        if ($validator->fails()) {
            return CustomReponse::error('Error al validar', $validator->errors());
        }

        try{

        	$messages = DB::transaction(function() use($request){

        		

        		$service = Services::where([
            		'branch_office_id' => $request->current_user->branch_office_id
        		])->first();

        		
        		$message = Messages::create([
        			
        			'message' => $request->get('message'),
        			'autor_id'=> $request->current_user->id,
                    'branch_office_id'=> $request->current_user->branch_office_id,
        			'priority'=> $request->get('priority'),
        			'services_id' => $service->id,
        		]);

        		return compact('message');
        		
        	});

        	return CustomReponse::success('Mensaje creado correctamente', $messages);

        }catch (\Exception $exception){

        	return CustomReponse::error('El mensaje no se guardo correctamente', $exception->getMessage());

        }

    }

    public function update(Request $request, $id){

    	$toupdate = Messages::where('id',$id)->get();

    	dd($toupdate);

    }
}

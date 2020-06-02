<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Helpers\CustomReponse;

use App\Models\Messages;
use App\Models\Services;
use Illuminate\Validation\Rule;

class MessageController extends Controller
{
    //

    public function index(Request $request, $services_id){

    	$message = Messages::where([
            'services_id'=>$services_id,
            'branch_office_id'=>$request->current_user->branch_office_id
        ])->with(['author:id,name'])->get();

    	return $message;
    }

    public function store(Request $request){

        $services_available = Services::where('branch_office_id', $request->current_user->branch_office_id)
            ->get()
            ->map(function($service){
            return $service->id;
        });

    	$validator = Validator::make($request->all(), [
            'message' => 'required',
            'author_id' => 'required',
            'branch_office_id' => 'required',
            'priority' => 'required',
            'services_id' => ['required', Rule::in($services_available)]

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
        			'author_id'=> $request->current_user->id,
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
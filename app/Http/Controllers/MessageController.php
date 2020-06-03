<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Helpers\CustomReponse;

use App\Models\Messages;
use App\Models\Services;
use App\Models\User;
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

    public function update(Request $request, $services_id, $message_id){

        $services_available_to_update = Services::where('branch_office_id', $request->current_user->branch_office_id)
            ->get()
            ->map(function($service){
                return $service->id;
        });

          
        $author_avilable_to_update = User::where('id',$request->current_user->id)
        ->get()
        ->map(function($author){
                return $author->id;
        });

       
            
        $validator = Validator::make($request->all(), [

            'message' => 'required',
            'author_id' => ['required', Rule::in($author_avilable_to_update)],
            'branch_office_id' => 'required',
            'priority' => 'required',
            'services_id' => ['required', Rule::in($services_available_to_update)]

        ]);

        if ($validator->fails()) {
            return CustomReponse::error('Error al validar', $validator->errors());
        }

        try{

            $message_to_update = DB::transaction(function() use($request, $services_id, $message_id, $author_avilable_to_update){

                $message = Messages::where('id',$message_id)->first();
                
                
                $message->update([
                    'message'    => $request->get('message'),
                    'author_id'  => $author_avilable_to_update[0],
                    'branch_office_id'=> $request->current_user->branch_office_id,
                    'priority'   => $request->get('priority'),
                    'services_id'=> $services_id
                ]);

                return compact('message');
                
            });

            return CustomReponse::success('Mensaje actualizado correctamente', $message_to_update);

        }catch(\Exception $exception){
            return CustomReponse::error('El mensaje no se modifico correctamente', $exception->getMessage());
        }

    }

    public function destroy(Request $request, $services_id, $message_id){

        try{

            $message_to_delete = DB::transaction(function() use($request, $services_id, $message_id){

                $message = Messages::where('id',$message_id)->first();

                $message->delete();

                return compact('message');
            });

            return CustomReponse::success('Mensaje eliminado correctamente', $message_to_delete);

        }catch(\Exception $exception){

             return CustomReponse::error('El mensaje no se elimino correctamente');

        }

        //dd($todelete);

    }
}

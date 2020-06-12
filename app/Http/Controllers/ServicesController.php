<?php

namespace App\Http\Controllers;

use App\Rules\ValidRole;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Helpers\CustomReponse;

use App\Models\Services;

class ServicesController extends Controller
{

    public function index(Request $request){

        $services = Services::with(['client', 'technical'])
            ->where('branch_office_id', $request->current_user->branch_office_id)->get();

        return CustomReponse::success('Servicio encontrado', [ 'services' => $services ]);
        
    }

    public function store(Request $request){

    	$validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'total_cost' => 'required',
            'client_id'  => ['required', new ValidRole('cliente')],
            'technical_id' => ['required', new ValidRole('tecnico')]

        ]);

        if ($validator->fails()) {
            return CustomReponse::error('Error al validar', $validator->errors());
        }

        
        try{
            $result = DB::transaction(function () use($request){



            	$service = Services::create([
            	    'name' => $request->get('name'),
                    'type' => $request->get('type'),
                    'costs' => $request->get('costs', []),
                    'extra_cost' => $request->get('extra_cost'),
                    'total_cost' => $request->get('total_cost'),
                    'client_id' => $request->get('client_id'),
                    'technical_id' => $request->get('technical_id'),
                    'branch_office_id' => $request->current_user->branch_office_id,
                    'address' => $request->get('address'),
                    'postal_code' => $request->get('postal_code'),
                    'state' => $request->get('state'),
                    'municipality' => $request->get('municipality'),
                    'contact_phone' => $request->get('contact_phone'),
                    'progress_status' => $request->get('progress_status'),
                    'description' => $request->get('description')
                 ]);

            	return $service;

            });

            return CustomReponse::success('Servicio creado correctamente', $result);

        }catch (\Exception $exception){

            return CustomReponse::error('El servicio no ha podido ser creado', $exception->getMessage());

        }

    }

    public function show(Request $request, $id){

        $service = Services::where([
            'id' => $id,
            'branch_office_id' => $request->current_user->branch_office_id
        ])->first();

        if (!$service instanceof Services){
            return CustomReponse::error("Servicio no encontrado");
        }

        $service->costs = json_decode($service->costs);

        $service->files = File::where(['model' => str_replace('\\', '/', get_class($service)), 'model_id' => $service->id])->get();

        return CustomReponse::success("Servicio encontrados correctamente", [ 'service' => $service] );

    }

    public function update(Request $request, $id){

        $service = Services::where([
            'id' => $id,
            'branch_office_id' => $request->current_user->branch_office_id
        ])->first();

        if (!$service instanceof Services){
            return CustomReponse::error("Servicio no encontrado");
        }
        
        try{

            $service = DB::transaction(function() use($request, $service){

                $service->update([
                    'name' => $request->get('name', $service->name),
                    'type' => $request->get('type', $service->type),
                    'extra_cost' => $request->get('extra_cost', $service->extra_cost),
                    'total_cost' => $request->get('total_cost', $service->total_cost),
                    'client_id' => $request->get('client_id', $service->client_id),
                    'technical_id' => $request->get('technical_id', $service->technical_id),
                    'costs' => $request->get('costs', $service->costs),
                    'progress_status' => $request->get('progress_status'),
                    'description' => $request->get('description')
                ]);

                return $service;
            });

            return CustomReponse::success('Servicio modificado correctamente', $service);

        }catch(\Exception $exception){
            return CustomReponse::error('No ha sido posible modificar el servicio', $exception->getMessage());
        }

    }


    public function destroy($id){

        try{

            $service = Services::find($id);
            $service->delete();

            return CustomReponse::success("Servicio desactivado correctamente");

        }catch(\Exception $exception){

            return CustomReponse::error('No ha sido posible crear el servicio');

        }

    }
}

<?php

namespace App\Http\Controllers;

use App\Helpers\AvailableHelper;
use App\Models\Products;
use App\Rules\ValidRole;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Helpers\CustomResponse;

use App\Models\Services;
use Illuminate\Validation\Rule;

class ServicesController extends Controller
{

    public function index(Request $request){

        $services = Services::with(['client', 'technical'])
            ->where('branch_office_id', $request->current_user->branch_office_id)->get();

        return CustomResponse::success('Servicio encontrado', [ 'services' => $services ]);

    }

    public function store(Request $request){

        $products_available = (new AvailableHelper)->availableByBranchOffice(Products::class, $request->current_user->branch_office_id);

    	$validator = Validator::make($request->all(), [
            'type' => ['required', Rule::in('preventivo', 'correctivo', 'virtual')],
            'client_id'  => ['required', new ValidRole('cliente')],
            'technical_id' => ['required', new ValidRole('tecnico')],
            'service_start' => 'required|date',
            'service_end' => 'required|date',
            'product_id' => ['required', Rule::in($products_available)]
        ]);

        if ($validator->fails()) {
            return CustomResponse::error('Error al validar', $validator->errors());
        }


        try{
            $result = DB::transaction(function () use($request){

            	$service = Services::create([
            	    'name' => $request->get('name', ''),
                    'type' => $request->get('type'),
                    'costs' => $request->get('costs', []),
                    'extra_cost' => $request->get('extra_cost'),
                    'total_cost' => $request->get('total_cost', 0),
                    'client_id' => $request->get('client_id'),
                    'technical_id' => $request->get('technical_id'),
                    'branch_office_id' => $request->current_user->branch_office_id,
                    'address' => $request->get('address'),
                    'postal_code' => $request->get('postal_code'),
                    'state' => $request->get('state'),
                    'municipality' => $request->get('municipality'),
                    'contact_phone' => $request->get('contact_phone'),
                    'progress_status' => $request->get('progress_status', 50),
                    'description' => $request->get('description'),
                    'service_start' => $request->get('service_start'),
                    'service_end' => $request->get('service_end'),
                    'product_id' => $request->get('product_id')
                 ]);

            	return $service;

            });

            return CustomResponse::success('Servicio creado correctamente', $result);

        }catch (\Exception $exception){

            return CustomResponse::error('El servicio no ha podido ser creado', $exception->getMessage());

        }

    }

    public function show(Request $request, $id){

        $service = Services::where([
            'id' => $id,
            'branch_office_id' => $request->current_user->branch_office_id
        ])->first();

        if (!$service instanceof Services){
            return CustomResponse::error("Servicio no encontrado");
        }

        $service->files = File::where(['model' => str_replace('\\', '/', get_class($service)), 'model_id' => $service->id])->get();

        return CustomResponse::success("Servicio encontrados correctamente", [ 'service' => $service] );

    }

    public function update(Request $request, $id){

        $service = Services::where([
            'id' => $id,
            'branch_office_id' => $request->current_user->branch_office_id
        ])->first();

        if (!$service instanceof Services){
            return CustomResponse::error("Servicio no encontrado");
        }

        $products_available = (new AvailableHelper)->availableByBranchOffice(Products::class, $request->current_user->branch_office_id);

        $validator = Validator::make($request->all(), [
            'type' => ['required', Rule::in('preventivo', 'correctivo', 'virtual')],
            'client_id'  => ['required', new ValidRole('cliente')],
            'technical_id' => ['required', new ValidRole('tecnico')],
            'service_start' => 'required|date',
            'service_end' => 'required|date',
            'product_id' => ['required', Rule::in($products_available)]
        ]);

        if ($validator->fails()) {
            return CustomResponse::error('Error al validar', $validator->errors());
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
                    'progress_status' => $request->get('progress_status', $service->progress_status),
                    'description' => $request->get('description', $service->description),
                    'repairs' => $request->get('repairs', $service->repairs),
                ]);

                return $service;
            });

            return CustomResponse::success('Servicio modificado correctamente', $service);

        }catch(\Exception $exception){
            return CustomResponse::error('No ha sido posible modificar el servicio', $exception->getMessage());
        }

    }


    public function destroy($id){

        try{

            $service = Services::find($id);
            $service->delete();

            return CustomResponse::success("Servicio desactivado correctamente");

        }catch(\Exception $exception){

            return CustomResponse::error('No ha sido posible crear el servicio');

        }

    }
}

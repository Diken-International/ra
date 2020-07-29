<?php

namespace App\Http\Controllers;

use App\Helpers\AvailableHelper;
use App\Models\Products;
use App\Models\ReportService;
use App\Rules\ValidRole;
use App\Models\File;
use Carbon\Carbon;
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

    	$validator = Validator::make($request->all(), [
            'client_id'  => ['required', new ValidRole('cliente')],
            'technical_id' => ['required', new ValidRole('tecnico')],
            'product_user_ids' => 'required|array'
        ]);

        if ($validator->fails()) {
            return CustomResponse::error('Error al validar', $validator->errors());
        }

        try{
            $result = DB::transaction(function () use($request){

            	$service = Services::create([
                    'client_id' => $request->get('client_id'),
                    'technical_id' => $request->get('technical_id'),
                    'branch_office_id' => $request->current_user->branch_office_id
                 ]);

                foreach ($request->get('product_user_ids') as $product_id){
                    $product_service = ReportService::create([
                        'service_id' => $service->id,
                        'product_user_id' => $product_id,
                        'service_start' => Carbon::now()
                    ]);
                }

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
            'client_id'  => ['required', new ValidRole('cliente')],
            'technical_id' => ['required', new ValidRole('tecnico')]
        ]);

        if ($validator->fails()) {
            return CustomResponse::error('Error al validar', $validator->errors());
        }

        try{

            $service = DB::transaction(function() use($request, $service){

                $service->update([
                    'client_id' => $request->get('client_id', $service->client_id),
                    'technical_id' => $request->get('technical_id', $service->technical_id)
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

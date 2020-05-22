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

    public function index(Request $request){

        $service = Services::all();
        //dd($service);
        
        return compact('service');

        return CustomReponse::success('Servicio encontrado', [ 'service' => $service ]);
        
    }

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

    public function show($id){

        $service = Services::findOrFail($id);

        return CustomReponse::success("Servicio encontrados correctamente", [ 'service' => $service] );

    }

    public function update(Request $request, $id){
        
        try{

            $service = DB::transaction(function() use($request, $id){

                $service = Services::findOrFail($id);
                
                $service->update( $request->all() );
                
                return compact('service');
                
                
            });

            return CustomReponse::success('Servicio modificado correctamente', $service);

        }catch(\Exception $exception){
            return CustomReponse::error('No ha sido posible modificar el servicio', $exception->getMessage());
        }

    }


    public function destroy($id){

        try{
            
            $delete = DB::transaction(function() use($id){

                
                $service = Services::findOrFail($id)->delete();

                return compact('delete');

            });
            

            return CustomReponse::success("Administrador desactivado correctamente", $delete);

        }catch(\Exception $exception){

            return CustomReponse::error('No ha sido posible crear el administrador');

        }

    }
}

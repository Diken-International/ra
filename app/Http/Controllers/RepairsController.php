<?php

namespace App\Http\Controllers;

use App\Models\CategoryRepairParts;
use App\Models\ProductRepairParts;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Helpers\CustomReponse;


use App\Models\RepairsParts;

class RepairsController extends Controller
{
    //
    public function index(Request $request){

    	$repairs = RepairsParts::with(['category', 'product'])->get();

    	return CustomReponse::success('Refacciones obtenidas', [
    	    'repairs' => $repairs
        ]);
    }

    public function store(Request $request){

    	$validator = Validator::make($request->all(), [


            'code' => 'required',
            'parts_num' => 'required',
            'number_diken' => 'required',
            'category_repair_parts_id' => 'required',
            'product_repair_parts_id' => 'required',
            'name' => 'required',
            'features' => 'required',
            'quantity' => 'required',
            'number_of_part' => 'required',

        ]);

        if ($validator->fails()) {
            return CustomReponse::error('Error al validar', $validator->errors());
        }

        try{

        	$repair_parts = DB::transaction(function() use($request){
                //dd( $request->all() );
        		$repair_part = RepairsParts::create( $request->all() );

        		return compact('repair_part');

        	});

        	return CustomReponse::success('Pieza de Reparacion creada correctamente', $repair_parts);

        }catch(\Exception $exception){

        	return CustomReponse::error('La reparacion no se guardo correctamente', $exception->getMessage());
        }
    }

    public function update(Request $request, $id){

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

                $repairs = DB::transaction(function() use($request, $id){

                    $repair = Repairs::where('id',$id)->first();

                    $repair->update( $request->all() );

                    return compact('repair');

                });

                return CustomReponse::success('Reparacion corregida correctamente', $repairs);

            }catch(\Exception $exception){

                return CustomReponse::error('La reparacion no se guardo correctamente', $exception->getMessage());
            }

    }

    public function products(Request $request){

        $products = ProductRepairParts::all();

        return CustomReponse::success("Productos obtenidos correctamente", ['products' => $products]);

    }

    public function categories(Request $request){

        $categories = CategoryRepairParts::all();

        return CustomReponse::success("Categorías obtenidos correctamente", ['categories' => $categories]);

    }
}

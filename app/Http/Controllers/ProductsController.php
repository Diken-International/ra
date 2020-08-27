<?php

namespace App\Http\Controllers;

use App\Helpers\PaginatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Helpers\CustomResponse;

/* Models */
use App\Models\Products;
use App\Models\Category;

class ProductsController extends Controller
{
    //
    public function index(Request $request){

    	$products = Products::all();

    	$data = PaginatorHelper::create($products, $request);

    	return CustomResponse::success("Productos obtenidos correctamente", $data);

    }

    public function store(Request $request){

    	$validator = Validator::make($request->all(), [


            'code' => 'required',
            'name' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'specifications_operation' => 'required',
            'specifications_desing' => 'required',
            'benefits' => 'required',
            'cost' => 'required',
            'price' => 'required',


        ]);

        if ($validator->fails()) {
            return CustomResponse::error('Error al validar', $validator->errors());
        }

        try{

        	$products = DB::transaction(function() use($request){
        		//
                $data = collect( $request->all() )
                        ->put('branch_office_id', $request->current_user->branch_office_id);

                       
        		$products = Products::create( $data->all() );
                
        		return compact('products');

        	});

        	return CustomResponse::success('Producto creado correctamente', $products);

        }catch(\Exception $exception){


        	return CustomResponse::error('El producto no se guardo correctamente', $exception->getMessage());
        }

    }

    public function update(Request $request, $product_id){

    	$validator = Validator::make($request->all(), [


            'code' => 'required',
            'name' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'specifications_operation' => 'required',
            'specifications_desing' => 'required',
            'benefits' => 'required',
            'cost' => 'required',
            'price' => 'required',


        ]);

        if ($validator->fails()) {
            return CustomResponse::error('Error al validar', $validator->errors());
        }

        try{

        	$products = DB::transaction(function() use($request, $product_id){

        		$product = Products::where('id',$product_id)->first();

        		$product->update( $request->all() );

        		return compact('product');
        	});

        	return CustomResponse::success('Producto modificado correctamente', $products);

        }catch(\Exception $exception){

        	return CustomResponse::error('El producto no se guardo correctamente', $exception->getMessage());

        }


    }

    public function destroy(Request $request, $product_id){

    	try{

    		$products_to_delete = DB::transaction(function() use($request, $product_id){

        		$product = Products::where('id',$product_id)->first();

        		$product->delete();

        		return compact('product');
        	});

        	return CustomResponse::success('Producto desactivado correctamente', $products_to_delete);

    	}catch(\Exception $exception){

    		return CustomResponse::error('El producto no se desactivo correctamente', $exception->getMessage());
    	}

    }

}

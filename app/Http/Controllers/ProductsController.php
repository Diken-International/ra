<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Helpers\CustomReponse;

/* Models */
use App\Models\Products;

class ProductsController extends Controller
{
    //
    public function index(Request $request){

    	$products = Products::all();

    	return $products;

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
            return CustomReponse::error('Error al validar', $validator->errors());
        }

        try{

        	$products = DB::transaction(function() use($request){
        		//
        		$products = Products::create( $request->all() );

        		return compact('products');

        	});

        	return CustomReponse::success('Producto creado correctamente', $products);

        }catch(\Exception $exception){

        	
        	return CustomReponse::error('El producto no se guardo correctamente', $exception->getMessage());
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
            return CustomReponse::error('Error al validar', $validator->errors());
        }
        try{

        	$products = DB::transaction(function() use($request, $product_id){

        		$product = Products::where('id',$product_id)->first();

        		$product->update( $request->all() );

        		return compact('product');
        	});

        	return CustomReponse::success('Producto modificado correctamente', $products);

        }catch(\Exception $exception){

        	return CustomReponse::error('El producto no se guardo correctamente', $exception->getMessage());

        }

    }

    public function destroy(Request $request, $product_id){

    	try{

    		$products_to_delete = DB::transaction(function() use($request, $product_id){

        		$product = Products::where('id',$product_id)->first();

        		$product->delete();

        		return compact('product');
        	});

        	return CustomReponse::success('Producto desactivado correctamente', $products_to_delete);

    	}catch(\Exception $exception){

    		return CustomReponse::error('El producto no se desactivo correctamente', $exception->getMessage());
    	}

    }

}

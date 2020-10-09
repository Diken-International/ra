<?php

namespace App\Http\Controllers;

use App\Helpers\PaginatorHelper;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Helpers\CustomResponse;
use App\Helpers\ModelHelper;
use App\Http\Requests\Seach_Product\SeachProductRequest;

/* Models */
use App\Models\Products;
use App\Models\Category;
use App\Models\ProductUser;

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
            'benefits' => 'required'


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

            Bugsnag::notifyException($exception);
        	return CustomResponse::error('El producto no se guardo correctamente', $exception->getMessage());
        }

    }

    public function show(Request $request, $product_id){

        $product = ModelHelper::findEntity(Products::class, $product_id);

        return CustomResponse::success("Producto obetenido correctamente", ['product' => $product]);

    }

    public function update(Request $request, $product_id){

    	$validator = Validator::make($request->all(), [


            'code' => 'required',
            'name' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'specifications_operation' => 'required',
            'specifications_desing' => 'required',
            'benefits' => 'required'


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
            Bugsnag::notifyException($exception);
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

            Bugsnag::notifyException($exception);
    		return CustomResponse::error('El producto no se desactivo correctamente', $exception->getMessage());
    	}

    }

    public function listServiesProduct(SeachProductRequest $request){

        $product_seach = ProductUser::select('report_services.service_id','report_services.id','report_services.status','product_user.last_service')
        ->join('report_services','product_user.id','=','report_services.product_user_id')
        ->where([ 
            'serial_number' => $request->get('serial_number')
            
        ])
        ->where('report_services.status', '<>', 'terminado')
        ->where('report_services.status', '<>', 'cancelado')
        ->get();

        $data = PaginatorHelper::create($product_seach, $request);

        return CustomResponse::success("Data encontrada correctamente", $data );
    }

}

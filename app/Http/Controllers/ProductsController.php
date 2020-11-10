<?php

namespace App\Http\Controllers;

use App\Helpers\PaginatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Helpers\CustomResponse;
use App\Helpers\ModelHelper;
use App\Http\Requests\Seach_Product\SeachProductRequest;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/* Models */
use App\Models\Products;
use App\Models\Category;
use App\Models\ProductUser;
use App\Models\Reports;

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

    public function listServiesProduct(SeachProductRequest $request){

        
        $report_seach = Reports::whereRaw(
            "(product_serial_number = ?)",
            [$request->get('serial_number')]
        );

        $report = $report_seach->get();

        $data = PaginatorHelper::create($report, $request);

        return CustomResponse::success("Data encontrada correctamente", $data );
        
    }

    public function domSerialNumber(Request $request){
        
        
        $number = ProductUser::select(
            'products.code',
            'products.name',
            'product_user.serial_number',
            'branch_offices.name as Sucursal',
            'product_user.product_type',
            'category.name as Category',
            'users.company_name as Compania '

        )
        ->join('products','product_user.product_id','=','products.id')

        ->join('branch_offices','products.branch_office_id','=','branch_offices.id')

        ->join('category','products.category_id','=','category.id')

        ->join('users', 'product_user.user_id','=','users.id')

        ->where([
            'serial_number' => $request->get('serial_number')
        ])

        ->first();

        
        
        $pdf = \PDF::loadView('formats.serial_number', compact('number'));
            
        return $pdf->stream("$number->serial_number.pdf");
        

    }

}

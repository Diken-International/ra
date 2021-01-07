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

    public function nextService(Request $request){

        $query = DB::table('product_user')
            ->select([
                'client.id as client_id',
                DB::raw('CONCAT(client.business_name, \' - \' ,client.company_name) as client_business_name'),
                'product_user.next_service as product_next_service',
                'p.name as product_name'
            ])
            ->join('products as p', 'product_user.product_id', 'p.id')
            ->join('users as client', 'product_user.user_id', 'client.id')
            ->where('p.branch_office_id', $request->current_user->branch_office_id)
            ->where('product_user.next_service', '<=', DB::raw('current_date + 7'))
            ->orderBy('product_user.next_service', 'asc')->get();

        $data = PaginatorHelper::create($query, $request);

        return CustomResponse::success("Productos con proximos servicios", $data);

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

        $report_seach = Reports::where("product_serial_number",$request->get('serial_number'));
        $report_seach->where("report_status", "!=", 'terminado');

        $report = $report_seach->get();

        $data = PaginatorHelper::create($report, $request);

        return CustomResponse::success("Data encontrada correctamente", $data );

    }

    public function domSerialNumber(Request $request){

        $validator = Validator::make($request->all(), [
            'serial_number' => 'required'
        ]);

        if ($validator->fails()) {
            return CustomResponse::error('Error al validar', $validator->errors());
        }

        $number = ProductUser::select(
            'products.code',
            'products.name',
            'product_user.serial_number',
            'branch_offices.name as Sucursal',
            'product_user.product_type',
            'category.name as Category',
            'users.company_name'

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

        return base64_encode($pdf->output());


    }

}

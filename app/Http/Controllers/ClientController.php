<?php

namespace App\Http\Controllers;

use App\Helpers\CustomResponse;
use App\Helpers\ModelHelper;
use App\Helpers\PaginatorHelper;
use App\Http\Requests\Client\ProductAddRequest;
use App\Http\Requests\User\UserRequest;
use App\Models\Client;
use App\Models\ProductUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class ClientController extends Controller
{

    public function index(Request $request)
    {

        $users = Client::where([
            'branch_office_id' => $request->current_user->branch_office_id,
            'role' => 'cliente'
        ]);

        if (!empty($request->get('search'))){
            $users->where('name', 'iLIKE', '%'.$request->get('search').'%');
        }

        $users = $users->orderBy('id')->get();

        $data = PaginatorHelper::create($users, $request);

        return CustomResponse::success("Clientes encontrados correctamente", $data );

    }

    public function update(UserRequest $request, $user_id){

        $client = ModelHelper::findEntity(Client::class, $user_id);

        try{

            $client_updated = DB::transaction(function() use($request, $client){

                $client->update($request->all());
                return compact('client');

            });

            return CustomResponse::success("Cliente actualizado correctamente", $client_updated);

        }catch(\Exception $exception){

            return CustomResponse::error('No ha sido posible modificar el cliente', $exception->getMessage());

        }

    }

    public function show(Request $request, $user_id){

        $user = ModelHelper::findEntity(
            Client::class,
            $user_id,
            ['branch_office_id' => $request->current_user->branch_office_id]
        );

        return CustomResponse::success("Usuario obtenido correctamente", ['user' => $user]);

    }

    public function addProduct(ProductAddRequest $request, $user_id){

        $client = ModelHelper::findEntity(Client::class, $user_id);

        try{

            $product_user = ProductUser::create([
                'product_id'        => $request->get('product_id'),
                'user_id'           => $user_id,
                'serial_number'     => Uuid::uuid1(),
                'product_type'      => $request->get('product_type'),
                'status'            => true,
                'period_service'    => 30,
                'next_service'      => Carbon::now()->addDay(30),
                'last_service'      => Carbon::now()
            ]);

            return CustomResponse::success('Producto agregado correctamente', ['user_product' => $product_user]);

        }catch (\Exception $exception){

            return CustomResponse::error('No es posible agregar un producto ha este cliente', $exception->getMessage());

        }

    }

    public function listProduct(Request $request, $user_id){

        $collect_product_user = ProductUser::where('user_id', $user_id)->orderBy('next_service', 'asc')->get();

        $data = PaginatorHelper::create($collect_product_user, $request);

        return CustomResponse::success("Data encontrada correctamente", $data );

    }

    public function detailProduct(Request $request, $user_id, $product_id){

        $product_user = ModelHelper::findEntity(ProductUser::class, $product_id, ['user_id' => $user_id]);

        return CustomResponse::success("Detalle obtenido correctamente", ['product_user' => $product_user]);
    }

}

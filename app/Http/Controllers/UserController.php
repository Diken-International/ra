<?php

namespace App\Http\Controllers;

use App\Exceptions\Api\EntityNotFoundException;
use App\Helpers\CustomResponse;
use App\Helpers\ModelHelper;
use App\Helpers\PaginatorHelper;
use App\Helpers\RoleHelper;
use App\Http\Requests\User\UserRequest;
use App\Models\BranchOffice;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function createAdmin(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return CustomResponse::error('Error al validar', $validator->errors());
        }


        try{
            $result = DB::transaction(function () use($request){

                $branch_office = BranchOffice::create(['name' => $request->get('branch_office_name')]);
                $user = User::create([
                    'name' => $request->get('name'),
                    'last_name' => $request->get('last_name'),
                    'email' => $request->get('email'),
                    'password' => $request->get('password'),
                    'role' => 'admin',
                    'branch_office_id' => $branch_office->id
                ]);

                return compact('user','branch_office');

            });

            return CustomResponse::success('Administrador creado correctamente', $result);

        }catch (\Exception $exception){

            return CustomResponse::error('No ha sido posible crear el usuario');

        }


    }

    /**
     * @param UserRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function store(UserRequest $request){

        try{

            $create = DB::transaction(function() use($request){


                $user = User::create([
                        'name'              => $request->get('name'),
                        'last_name'         => $request->get('last_name'),
                        'second_last_name'  => $request->get('second_last_name'),
                        'email'             => $request->get('email'),
                        'password'          => $request->get('password'),
                        'role'              => $request->get('role'),
                        'branch_office_id'  => $request->current_user->branch_office_id
                ]);

                return compact('user');


            });

            return CustomResponse::success("Usuario creado correctamente", $create);

        }catch(\Exception $exception){
            return CustomResponse::error('No ha sido posible crear el usuario');
        }
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function me()
    {
        return CustomResponse::success(
            "Usuario obtenido correctamente",
            ['user' => auth()->user()]
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $users = User::where([
            'branch_office_id' =>  $request->current_user->branch_office_id
        ])->whereIn('role', ['admin', 'asesor', 'tecnico']);

        if (!empty($request->get('role'))){
            $users->where('role', $request->get('role'));
        }

        if (!empty($request->get('search'))){
            $users->where('name', 'iLIKE', '%'.$request->get('search').'%');
        }

        $users = $users->get();

        $data = PaginatorHelper::create($users, $request);

        return CustomResponse::success("Usuarios encontrados correctamente", $data );

    }

    /**
     * @param Request $request
     * @param $user_id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     * @throws EntityNotFoundException
     */
    public function show(Request $request, $user_id){

        $user = ModelHelper::findEntity(
            User::class,
            $user_id,
            ['branch_office_id' => $request->current_user->branch_office_id]
        );

        return CustomResponse::success("Usuario obtenido correctamente", ['user' => $user]);

    }

    /**
     * @param UserRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     * @throws EntityNotFoundException
     */
    public function update(UserRequest $request, $id){

        $user = ModelHelper::findEntity(User::class, $id);

        try{

            $user_updated = DB::transaction(function() use($request, $user){

                $user->update($request->all());
                return compact('user');

            });

            return CustomResponse::success("Usuario actualizado correctamente", $user_updated);

        }catch(\Exception $exception){
            return CustomResponse::error('No ha sido posible modificar el usuario', $exception->getMessage());
        }

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     * @throws EntityNotFoundException
     */
    public function destroy(Request $request, $id){

        $user = ModelHelper::findEntity(User::class, $id);

        try{

            $delete = DB::transaction(function() use($user){

                $user_used_to_delete = auth()->user()->role;

                if($user_used_to_delete == 'Super_Admin' || $user_used_to_delete == 'admin'){

                    $user->delete();
                }

                return compact('user');

            });

            return CustomResponse::success("Usuario desactivado correctamente", $delete);

        }catch(\Exception $exception){

            return CustomResponse::error('No ha sido posible crear el administrador');

        }


    }



}

<?php

namespace App\Http\Controllers;

use App\Helpers\CustomReponse;
use App\Helpers\RoleHelper;
use App\Models\BranchOffice;
use App\Models\User;
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
            return CustomReponse::error('Error al validar', $validator->errors());
        }


        try{
            $result = DB::transaction(function () use($request){

                $branch_office = BranchOffice::create(['name' => $request->get('branch_office_name')]);
                $user = User::create([
                    'name' => $request->get('name'),
                    'last_name' => $request->get('last_name'),
                    'email' => $request->get('email'),
                    'password' => bcrypt($request->get('password')),
                    'role' => 'admin',
                    'branch_office_id' => $branch_office->id
                ]);

                return compact('user','branch_office');

            });

            return CustomReponse::success('Administrador creado correctamente', $result);

        }catch (\Exception $exception){

            return CustomReponse::error('No ha sido posible crear el usuario');

        }


    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
            'role'    => ['required', Rule::in(RoleHelper::$available_roles)]
        ]);

        if ($validator->fails()) {
            return CustomReponse::error('Error al validar', $validator->errors());
        }
        
        try{

            $create = DB::transaction(function() use($request){

                
                $user = User::create([
                        'name' => $request->get('name'),
                        'last_name' => $request->get('last_name'),
                        'second_last_name' => $request->get('second_last_name'),
                        'email' => $request->get('email'),
                        'password' => bcrypt($request->get('password')),
                        'role' => $request->get('role'),
                        'branch_office_id' => $request->current_user->branch_office_id
                ]);

                return compact('user');
                

            });

            return CustomReponse::success("Usuario creado correctamente", $create);

        }catch(\Exception $exception){
            return CustomReponse::error('No ha sido posible crear el usuario');
        }
    }

    public function me()
    {
        return CustomReponse::success(
            "Usuario obtenido correctamente",
            ['user' => auth()->user()]
        );
    }

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

        return CustomReponse::success("Usuarios encontrados correctamente", [ 'users' => $users] );
        
    }

    public function show(Request $request, $user_id){


        $user = User::where([
            'id' => $user_id,
            'branch_office_id' => $request->current_user->branch_office_id
        ])->first();

        if ($user instanceof User){
            return CustomReponse::success("Usuario obtenido correctamente", ['user' => $user]);
        }

        return CustomReponse::error("El usuario no ha sido obtenido correctamente");
        

    }

    public function update(Request $request, $id){
        
        try{

            $update = DB::transaction(function() use($request, $id){
                //dd(  );
                
                
                
                $user = User::where('id',$id)->first();
               
                
                $user->update($request->all());

                if (!empty($request->get('password'))){
                    $user->password = bcrypt($request->get('password'));
                    $user->save();
                }
                
                return compact('user');
                
            });

            return CustomReponse::success("Administrador actualizados correctamente", $update);

        }catch(\Exception $exception){
            return CustomReponse::error('No ha sido posible modificar el administrador');
        }
        
    }

    public function destroy(Request $request, $id){

        try{
            
            $delete = DB::transaction(function() use($id){

                
                $user = auth()->user()->role;

                if($user == 'Super_Admin' || $user == 'admin'){

                    $user = User::findOrFail($id)->delete();

                }
                    return compact('user');

            });

            return CustomReponse::success("Administrador desactivado correctamente", $delete);

        }catch(\Exception $exception){

            return CustomReponse::error('No ha sido posible crear el administrador');

        }

        
    }   



}

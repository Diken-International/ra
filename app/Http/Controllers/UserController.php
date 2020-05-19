<?php

namespace App\Http\Controllers;

use App\Helpers\CustomReponse;
use App\Models\BranchOffice;
use App\Models\User;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function welcome(Request $request){

        $user = $request->current_user;

        if ($user->role = 'admin'){
            return CustomReponse::success("Bienvenido Administrador",['user' => $user]);
        }

        return CustomReponse::success("Bienvenido",['user' => $user]);
    }

    public function createAdmin(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password' => 'required|string',
            'branch_office_name' => 'required'
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
            return CustomReponse::error('No ha sido posible crear el administrador');
        }


    }

    public function createRole(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password' => 'required|string',
            'role'    => 'required',
            'branch_office_name' => 'required'
        ]);

        if ($validator->fails()) {
            return CustomReponse::error('Error al validar', $validator->errors());
        }

        
        try{

            $create = DB::transaction(function() use($request){
                

                
                $user = auth()->user()->role;

                
                if($user == 'Super_Admin' || $user == 'admin'){

                    $branch_office = BranchOffice::create(['name' => $request->get('branch_office_name')]);

                    $idbranch_office = $branch_office->id;

                    //role = $request->get('role');

                    $user = User::create([
                            'name' => $request->get('name'),
                            'last_name' => $request->get('last_name'),
                            'email' => $request->get('email'),
                            'password' => bcrypt($request->get('password')),
                            'role' => $request->get('role'),
                            'branch_office_id' => $idbranch_office
                    ]);

                    return compact('user','branch_office');
                }


            });

            return CustomReponse::success("Administrador creado correctamente", $create);

        }catch(\Exception $exception){
            return CustomReponse::error('No ha sido posible crear el administrador');
        }
    }

    public function me()
    {
        return CustomReponse::success(
            "Usuario obtenido correctamente",
            ['user' => auth()->user()]
        );
    }

    public function index(){

        
        $user = User::all();

        
        return CustomReponse::success("Usuarios encontrados correctamente", [ 'users' => $user] );
        
        
    }

    public function show(Request $request){
        //dd($request->all() );

        $role = $request->get('role');

        if($role == 'admin'){

            $user = User::where('role','=',$role)->get();

            return CustomReponse::success("Administrador encontrado correctamente", $user);

        }
        

    }

    public function update(Request $request, $id){
        
        try{

            $update = DB::transaction(function() use($request, $id){
                //dd(  );
                
                $user = User::findOrFail($id);

                $user->update([
                    'name' => $request->get('name'),
                    'branch_office_id' => $request->get('branch_office_id'),
                ]);
                
                return compact('user');

            });

            return CustomReponse::success("Administrador actualizados correctamente", $update);

        }catch(\Exception $exception){
            return CustomReponse::error('No ha sido posible crear el administrador');
        }
        
    }

    public function destroy($id){

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

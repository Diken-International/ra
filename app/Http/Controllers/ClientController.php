<?php

namespace App\Http\Controllers;

use App\Helpers\CustomResponse;
use App\Helpers\PaginatorHelper;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;

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

        $users = $users->get();

        $data = PaginatorHelper::create($users, $request);

        return CustomResponse::success("Clientes encontrados correctamente", $data );

    }

}

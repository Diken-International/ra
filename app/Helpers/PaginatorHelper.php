<?php


namespace App\Helpers;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginatorHelper
{

    public static function create($data,Request $request){

        $per_page = (!empty($request->get('limit'))) ?  $request->get('limit') : env('PAGINATION_SIZE', 50);
        $page = (!empty($request->get('page'))) ?  $request->get('page') : 1;

        return self::paginate($data, $per_page, $page);
    }

    private static function paginate($data, $per_page, $page){


        if ($data instanceof Builder){
            $total = $data->count();
            $data_pagination = $data->paginate($per_page, '*', 'page', $page);
            return [
                'total' => $total,
                'page' => $data_pagination->currentPage(),
                'limit' => (int)$per_page,
                'rows' => $data_pagination->items()
            ];
        }

        if ($data instanceof Collection || $data instanceof \Illuminate\Support\Collection){
            $data_pagination = new LengthAwarePaginator($data->forPage($page, $per_page)->values()->all(),
                $data->count(),
                $per_page, $page, []);
            return [
                'total' => count($data),
                'page' => $data_pagination->currentPage(),
                'limit' => (int)$per_page,
                'rows' => $data_pagination->items()
            ];
        }

        return [
            'total' => 0,
            'page' => 0,
            'limit' => 0,
            'rows' => []
        ];

    }
}

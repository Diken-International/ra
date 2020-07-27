<?php

namespace App\Http\Controllers;

use App\Helpers\ModelHelper;
use App\Http\Requests\Downloads\ReceptionRequest;
use App\Models\ProductUser;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
class LettersController extends Controller
{

    public $functionality = [
        'e' => 'Excelente',
        'b' => 'Buena',
        'r' => 'Regular'
    ];

    private function translate($value){
        return $this->functionality[$value];
    }
    public function reception(ReceptionRequest $request){

        $product_user = ModelHelper::findEntity(ProductUser::class, $request->get('product_user'));
        $services = $request->get('services');
        $functionality = $this->translate($request->get('functionality'));
        $foam = $this->translate($request->get('foam'));
	    $pdf = PDF::loadView('example', [
	        'product_user' => $product_user,
            'services' => $services,
            'functionality' => $functionality,
            'foam' => $foam
        ]);

        return base64_encode($pdf->output());
	}

    public function receptionGet(ReceptionRequest $request){

        $pdf = PDF::loadView('example');

        return $pdf->download('invoice.pdf');
    }
}

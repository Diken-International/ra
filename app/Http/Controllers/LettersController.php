<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
class LettersController extends Controller
{
    //
    public function download(){
	    
	 
	    $pdf = \PDF::loadView('example');
	 
	    return $pdf->download('archivo.pdf');
	}
}

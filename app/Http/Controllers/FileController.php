<?php

namespace App\Http\Controllers;

use App\Helpers\CustomReponse;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File as FileFacade;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class FileController extends Controller
{
    public function upload(Request $request){

        $validator = Validator::make($request->all(), [
            'file' => 'required',
            'model' => 'required',
            'model_id' => 'required'
        ]);

        if ($validator->fails()) {
            return CustomReponse::error('Error al validar', $validator->errors());
        }

        $path = Storage::put(
            'images/'.strtolower(class_basename($request->get('model'))).'/'.$request->get('model_id'),
                $request->file('file'));

        
        $type = FileFacade::extension($path);

        
        $file = File::create([
            'model' => $request->get('model'),
            'model_id' => $request->get('model_id'),
            'path' => $path,
            'category'=>$request->get('category'),
            'type' => $type,
        ]);

        return CustomReponse::success("El archivo ha sido subido exitosamente", compact('file'));

    }

    public function show(Request $request, $path){
        if (!Storage::exists($path)){
            return CustomReponse::error("No se encontro la imagen");
        }

        if(FileFacade::extension($path) == 'docx'){

            return response()->download(storage_path('app/'.$path));
            //dd($path);

        }

        $img = Image::make(Storage::get($path));

        if (!empty($request->get('height'))){
            $img->resize(null, $request->get('height'), function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        return $img->response();

    }
}

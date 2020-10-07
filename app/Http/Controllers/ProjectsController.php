<?php

namespace App\Http\Controllers;

use App\Rules\ValidRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Helpers\CustomResponse;
use App\Helpers\ModelHelper;
use App\Helpers\PaginatorHelper;

/* Models */
use App\Models\Projects;

class ProjectsController extends Controller
{
    //
    public function index(Request $request){

    	$project = Projects::all();

    	$data    = PaginatorHelper::create($project, $request);

    	return CustomResponse::success("Proyectos obtenidos correctamente", $data);
    }

    public function store(Request $request){

    	$validator = Validator::make($request->all(), [

            'name' => 'required',
            'technical_id' => ['required', new ValidRole('tecnico')],
            'resources' => 'required',
        ]);

        if ($validator->fails()) {
            return CustomResponse::error('Error al validar', $validator->errors());
        }

        try{

        	$project = DB::transaction(function() use($request){

        		$project = Projects::create( $request->all() );
        		return compact('project');

        	});

        	return CustomResponse::success('Proyecto creado correctamente', $project);

        }catch(\Exception $exception){

        	return CustomResponse::error('El proyecto no se guardo correctamente', $exception->getMessage());

        }

    }

    public function show(Request $request, $project_id){

        $project = ModelHelper::findEntity(Projects::class, $project_id);

        return CustomResponse::success("Proyecto obetenido correctamente", ['project' => $project]);
    }

    public function update(Request $request, $project_id){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'technical_id' => ['required', new ValidRole('tecnico')],
            'resources' => 'required',
        ]);

        if ($validator->fails()) {
            return CustomResponse::error('Error al validar', $validator->errors());
        }

        try{
            $projects = DB::transaction(function() use($request, $project_id){

                $projects = Projects::where('id',$project_id)->first();

                $projects->update( $request->all() );

                return compact('projects');
            });

            return CustomResponse::success("Proyecto modificado correctamente", $projects);

        }catch(\Exception $exception){

            return CustomResponse::error('El proyecto no se modifico correctamente', $exception->getMessage());

        }


    }

    public function destroy(Request $request, $project_id){

        try{

            $projects_to_delete = DB::transaction(function() use($request, $project_id){

                $projects = Projects::where('id',$project_id)->first();

                $projects->delete();

                return compact('projects');
            });

            return CustomResponse::success('Proyecto desactivado correctamente', $projects_to_delete);

        }catch(\Exception $exception){

            return CustomResponse::error('El proyecto no se desactivo correctamente', $exception->getMessage());
        }

    }

}

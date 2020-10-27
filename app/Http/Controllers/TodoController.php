<?php

namespace App\Http\Controllers;

use App\Helpers\CustomResponse;
use App\Helpers\ModelHelper;
use App\Http\Requests\Todo\TodoIndexRequest;
use App\Http\Requests\Todo\TodoRequest;
use App\Models\Activities;
use App\Models\Services;
use App\Models\Todo;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index(TodoIndexRequest $request){

        $todo_day = Activities::where([
            'technical_id'  =>  $request->current_user->id
        ])->whereDate('date_activity', $request->get('date'))->get();

        $todo_week = Activities::where([
            'technical_id'  =>  $request->current_user->id
        ])->whereRaw(
            "(date_activity >= ? AND date_activity <= ?)",
            [$request->get('start_week')." 00:00:00", $request->get('end_week')." 23:59:59"])->get();

        return CustomResponse::success('Actividades obtenidas correctamente', [
            'day' => $todo_day,
            'week' => $todo_week
        ]);

    }

    public function store(TodoRequest $request){

        $data = collect($request->all());
        $data->put('technical_id',$request->current_user->id);

        try {
            $task = Todo::create($data->all());
            return CustomResponse::success('Actividad creada correctamente', ['task' => $task]);
        }catch (\Exception $exception){
            Bugsnag::notifyException($exception);
            return CustomResponse::error('La actividad no fue creada');
        }
    }

    public function update(TodoRequest $request, $todo_id){

        $data = collect($request->all());
        $data->put('technical_id',$request->current_user->id);
        $task = ModelHelper::findEntity(Todo::class, $todo_id);

        try {
            $task->update($data->all());
            return CustomResponse::success('Actividad actualizada correctamente', ['task' => $task]);
        }catch (\Exception $exception){
            Bugsnag::notifyException($exception);
            return CustomResponse::error('La actividad no fue actualizada');
        }
    }
}

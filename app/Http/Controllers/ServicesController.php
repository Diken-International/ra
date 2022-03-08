<?php

namespace App\Http\Controllers;

use App\Events\ServiceComplete;
use App\Helpers\ModelHelper;
use App\Helpers\PaginatorHelper;
use App\Helpers\ServiceHelper;
use App\Helpers\UserHelper;
use App\Models\ProductUser;
use App\Models\ReportService;
use App\Models\User;
use App\Models\CommentReview;
use App\Rules\ValidRole;
use App\Models\File;
use App\Http\Requests\ReviewService\ServiceReviewRequest;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Helpers\CustomResponse;
use App\Models\Services;
use Illuminate\Validation\Rule;

class ServicesController extends Controller
{

    public function index(Request $request){

        $query = Services::with(['client', 'technical'])
            ->where('branch_office_id', $request->current_user->branch_office_id)
            ->orderBy('created_at', 'desc');

        if ($request->current_user->role == 'admin'){
            $services = $query->get();
        }

        if($request->current_user->role == 'tecnico'){

            $query = $query->where('services.technical_id', $request->current_user->id);
            $services = $query->get();
        }

        $data = PaginatorHelper::create($services, $request);

        return CustomResponse::success('Servicio encontrado', $data);

    }

    public function reviews(Request $request){

        $query = DB::table('services')
            ->select([
                'comment_reviews.*',
                'client.business_name as client_business',
                'technical.name as technical_name'
            ])
            ->join(
                'comment_reviews',
                'services.id',
                'comment_reviews.service_id')->join(
                'users as client',
                'services.client_id',
                'client.id'
            )->join('users as technical',
                'services.technical_id',
                'technical.id')
            ->where([
                'services.branch_office_id' => $request->current_user->branch_office_id,
                'comment_reviews.check_revision' => true
            ]);


        if ($request->current_user->role == 'admin'){
            $services = $query->get();
        }

        if($request->current_user->role == 'tecnico'){

            $query = $query->where('services.technical_id', $request->current_user->id);
            $services = $query->get();
        }

        $data = PaginatorHelper::create($services, $request);

        return CustomResponse::success('Servicio encontrado', $data);

    }

    public function store(Request $request){

    	$validator = Validator::make($request->all(), [
            'client_id'         => ['required', new ValidRole('cliente')],
            'technical_id'      => ['required', new ValidRole('tecnico')],
            'type'              => ['required', Rule::in(['face-to-face',  'remote'])],
            'activity'          => ['required', Rule::in(['preventive',  'corrective'])],
            'kms'               => ['numeric'],
            'tentative_date'    => ['required', 'date'],
            'product_user_ids'  => 'required|array',
            'performance'       => 'required'
        ]);

        if ($validator->fails()) {
            return CustomResponse::error('Error al validar', $validator->errors());
        }

        try{
            $result = DB::transaction(function () use($request){

            	$service = Services::create([
                    'client_id'         => $request->get('client_id'),
                    'technical_id'      => $request->get('technical_id'),
                    'tentative_date'    => $request->get('tentative_date'),
                    'type'              => $request->get('type'),
                    'kms'               => $request->get('kms'),
                    'activity'          => $request->get('activity'),
                    'branch_office_id'  => $request->current_user->branch_office_id,
                    'performance'       => $request->get('performance')
                 ]);

                foreach ($request->get('product_user_ids') as $product_id){
                    $product_service = ReportService::create([
                        'service_id' => $service->id,
                        'product_user_id' => $product_id,
                        'service_start' => Carbon::now()
                    ]);
                }

            	return $service;

            });

            return CustomResponse::success('Servicio creado correctamente', $result);

        }catch (\Exception $exception){

            return CustomResponse::error('El servicio no ha podido ser creado', $exception->getMessage());

        }

    }

    public function show(Request $request, $id){

        $service = Services::where([
            'id' => $id,
            'branch_office_id' => $request->current_user->branch_office_id
        ])->first();

        if (!$service instanceof Services){
            return CustomResponse::error("Servicio no encontrado");
        }

        $service->files = File::where(['model' => str_replace('\\', '/', get_class($service)), 'model_id' => $service->id])->get();

        return CustomResponse::success("Servicio encontrados correctamente", [ 'service' => $service] );

    }

    public function update(Request $request, $id){

        $service = ModelHelper::findEntity(Services::class, $id, ['branch_office_id' => $request->current_user->branch_office_id]);

        $validator = Validator::make($request->all(), [
            'client_id'         => ['required', new ValidRole('cliente')],
            'technical_id'      => ['required', new ValidRole('tecnico')],
            'tentative_date'    => ['date'],
            'type'              => ['required', Rule::in(['face-to-face',  'remote'])],
            'activity'          => ['required', Rule::in(['preventive',  'corrective'])],
            'kms'               => ['numeric'],
            'performance'       => ['required', 'numeric']
        ]);

        if ($validator->fails()) {
            return CustomResponse::error('Error al validar', $validator->errors());
        }

        try{

            $service = DB::transaction(function() use($request, $service){

                $service->update([
                    'client_id' => $request->get('client_id', $service->client_id),
                    'technical_id' => $request->get('technical_id', $service->technical_id),
                    'type' => $request->get('type', $service->type),
                    'kms' => $request->get('kms', $service->kms),
                    'tentative_date' => $request->get('tentative_date', $service->tentative_date),
                    'activity' => $request->get('activity', $service->activity),
                    'performance' => $request->get('performance', $service->performance)
                ]);

                return $service;
            });

            return CustomResponse::success('Servicio modificado correctamente', $service);

        }catch(\Exception $exception){
            return CustomResponse::error('No ha sido posible modificar el servicio', $exception->getMessage());
        }

    }


    public function destroy($id){

        try{

            $service = Services::find($id);
            $service->delete();

            return CustomResponse::success("Servicio desactivado correctamente");

        }catch(\Exception $exception){

            return CustomResponse::error('No ha sido posible crear el servicio');

        }

    }

    public function reportShow(Request $request, $service_id, $report_id){

        $report = ModelHelper::findEntity(ReportService::class, $report_id);

        return CustomResponse::success("Reporte obetenido correctamente", ['report' => $report]);

    }

    public function reportUpdate(Request $request, $service_id, $report_id){

        $report = ModelHelper::findEntity(ReportService::class, $report_id);

        try{
             DB::transaction(function () use ($report, $request){
                $report->update($request->all());
                $report->progress = ($request->get('status') == 'terminado') ? 100 : 50;

                if ($request->get('status') == 'terminado'){
                    $product_user = ProductUser::find($report->productUser->id);
                    $product_user->update = true;
                    $product_user->last_service = Carbon::now();
                    $product_user->save();
                }

                $report->save();
            });

            $client = User::find($report->productUser->user_id);
            if(ServiceHelper::checkServiceComplete($report->service_id) && UserHelper::checkEmail($client)){
                event(new ServiceComplete($service_id, $client));
            }

            return CustomResponse::success(
                "Reporte actualizado correctamente",
                ['report' => $report]
            );
        }catch(\Exception $exception){
            Bugsnag::notifyException($exception);
            return CustomResponse::error("No fue posible actualizar el reporte", $exception->getMessage());
        }


    }

    public function reSendEmail(Request $request, $service_id){
        $service = ModelHelper::findEntity(Services::class, $service_id);
        try{
            $client = User::find($service->client_id);
            event(new ServiceComplete($service_id, $client));
            return CustomResponse::success("Correo enviado correctamente");
        }catch(\Exception $exception){
            Bugsnag::notifyException($exception);
            return CustomResponse::error("No se puede enviar reporte", $exception->getMessage());
        }
        return CustomResponse::error("No se puede enviar reporte");
    }

    public function serviceSurvey(ServiceReviewRequest $request, $service_id){

        $email = Crypt::decrypt($request->get('token_review'));

        try{

            $service = ModelHelper::findEntity(Services::class, $service_id);

            $client = User::find($service->client_id);

            if($client->email == $email && $service->received_review == false){

                $comment = DB::transaction(function() use($request, $service){

                    $service->received_review = true;
                    $service->save();

                    return CommentReview::create([
                        'star'         => $request->get('star'),
                        'description'  => $request->get('description'),
                        'check_revision' => $request->get('check_revision', false),
                        'service_id'   => $service->id
                    ]);
                });

            return CustomResponse::success("Comentario enviado correctamente", $comment);

            }

            return CustomResponse::error("No es posible hacer comentarios a este servicio");


        }catch(\Exception $exception){
            Bugsnag::notifyException($exception);
            return CustomResponse::error('El Comentario no logró ser almacenado', $exception->getMessage() );
        }
    }

    public function reviewServices(Request $request, $service_id){

        $review = CommentReview::where('service_id', $service_id)->first();

        return CustomResponse::success("Datos obtenidos", ['review' => $review]);
    }

    public function reviewsUpdate(Request $request, $review_id){

        $entity = ModelHelper::findEntity(CommentReview::class, $review_id);
        $entity->update($request->all());

        return CustomResponse::success("Estatus de comentario actualizado correctamente", []);

    }
}

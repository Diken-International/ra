<?php

namespace App\Console\Commands;

use App\Models\ProductUser;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckNextService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:services';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar manualmente las maquinas / productos que requieren un servicio proximo';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $products_update =  ProductUser::where(['update' => true, 'status' => true])->select(
            "id",
            "period_service",
            "next_service",
            "last_service",
            "update"
        )->get();

        $count_update = 0;
        foreach ($products_update as $product){
            $product->next_service = Carbon::createFromFormat('Y-m-d', $product->last_service)->addDays($product->period_service);
            $product->update = false;
            $product->save();
            $count_update++;
        }

        DB::table('information')->insert([
            'name' => 'Task next services',
            'number_elements_update' => $count_update,
            'last_update' => Carbon::now()
        ]);
    }
}

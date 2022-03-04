<html>
    <head>
        <title>Reporte de servicio</title>
        <link rel="stylesheet" href="{{ url('css/tychions.css') }}">
        <style>
            h2{
              margin-top: 0px;
            }
            .space-text p span{
                padding: 0 15px;
            }
            .my_row{
                border-top: solid 1px gray;
            }
        </style>
    </head>
    <body class="system-sans-serif">
    <div class="center mw5 mw7 hidden ba mv4">
      <h1 class="f4 bg-near-black  mv0 pv2 ph3 bg-blue white tc">Reporte de servicio</h1>
      <div class="pa3 bt">
        <div class="dib w-20">
            <img style="width: 120px" src="{{ url('images/diken.png') }}" alt="">
        </div>
        <div class="dib w-70">
            <p class="f6 f5-ns lh-copy mv0 tj">
                El reporte detalla el servicio proporcionado por el tecnico <b>{{ $service->technical->name }} {{ $service->technical->last_name }}</b> en sus instalaciones.
            </p>
            <div class="tr space-text">
                <p><span>Fecha: {{ \Carbon\Carbon::now()->format('d/m/y') }}</span> | <span>Número de servicio: </span> {{ $service->id }}</p>
            </div>
        </div>
      </div>
      <div class="ph3 pv2 ">
          <div class="dib w-30">
              <p><b>Cliente:</b> <br>{{ $service->client->name }} {{ $service->client->last_name }}</p>
          </div><div class="dib w-30">
              <p><b>Tipo de mantenimiento:</b> <br>{{ ($service->type == 'face-to-face') ? 'Presencial' : 'En linea'  }}</p>
          </div><div class="dib w-30">
              <p><b>Tipo de servicio:</b> <br>{{ type_activity($service->activity)  }}</p>
          </div>

      </div>
      <div class="pa3">
          <div class="tc">
              <h3 class="blue">Servicios realizados</h3>
          </div>
          <?php
            $repair_cost = 0;
            $cost_total = 0
          ?>
          @foreach($service->reportServices->all() as $report)
          <div class="my_row">
              <div class="f6">
                  <div class="dib w-40">
                      <p><b>Progreso del servicio:</b> {{ $report->progress }}%</p>
                  </div><div class="dib w-30">
                      <p><b>Fecha de inicio:</b>  {{ $report->service_start }}</p>
                  </div><div class="dib w-30">
                      <p><b>Fecha de termino:</b>  {{ $report->service_end }}</p>
                  </div>
              </div>
              <div>
                  <div class="dib w-100">
                      <p class="b">Observaciones</p>
                      <p>{{ $report->description }}</p>
                      <div>
                          <div class="dib w-40">
                              <p><b>Dilución:</b> {{ $report->dilution }}</p>
                          </div><div class="dib w-30">
                              <p><b>Frecuencía:</b>  {{ $report->frequency }}</p>
                          </div><div class="dib w-30">
                              <p><b>Método: </b> {{ $report->method }}</p>
                          </div>
                      </div>
                  </div>
              </div>
              <div>
                  <div class="dib w-50">
                      <p class="b">Costos extras</p>
                      @foreach($report->costs as $cost)
                          <?php $cost_total += $cost->cost ?>
                          <div>
                              <div class="dib w-70">
                                  <p>{{ $cost->description }}</p>
                              </div><div class="dib w-30">
                                  <p>${{ $cost->cost }}</p>
                              </div>
                          </div>
                      @endforeach
                  </div><div class="dib w-50">
                      <p class="b">Refacciones</p>
                      @foreach($report->costs_repairs as $repair)
                          <?php
                          $repair_object = \App\Models\RepairsParts::where(['id' => $repair->repair_id])->first();
                          $repair_cost += $repair->cost;
                          ?>
                          <div>
                              <div class="dib w-70">
                                  <p>{{ $repair_object->code }} | {{ $repair_object->name }}</p>
                              </div><div class="dib w-30">
                                  <p>${{ $repair->cost }}</p>
                              </div>
                          </div>
                      @endforeach
                  </div>
              </div>
          </div>
          @endforeach
          <div>
              <div class="dib w-70">
                  <p class="b">Total Costos extras y refacciones</p>
              </div><div class="dib w-30">
                  <p>${{ $repair_cost + $cost_total }}</p>
              </div>
          </div>
      </div>
    </div>
    </body>
</html>

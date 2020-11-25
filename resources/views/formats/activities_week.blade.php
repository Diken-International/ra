<?php


    function typeActivity($activity){
        if ($activity === 'remote'){
            return "Remoto";
        }
        return "Presencial";
    }

    function hasClient($client_id, $client_name){
        if (empty($client_id)){
            return "Actividad personal";
        }
        return $client_name;
    }


?>

<html>
    <head>
        <title>Reporte de actividades semanal</title>
        <link rel="stylesheet"
              href="https://cdnjs.cloudflare.com/ajax/libs/tachyons/4.11.1/tachyons.min.css"
              integrity="sha512-d0v474klOFSF7qD9WDvyRxAvXaWSxCHDZdnBSZQjo8BpVr6vpjwAgqetpqkKP38DzlOzdVPaLVnzzW1Ba8wB9w=="
              crossorigin="anonymous" />
        <style>
            h2{
                margin-top: 0px;
            }
            .page_break { page-break-before: always; }
        </style>
    </head>
    <body class="system-sans-serif">
        <div class="pa4">
            <h2 class="blue">Reporte de actividades semanal</h2>
            <p>Asesor: <strong>{{ $technical_name }}</strong></p>
            <p>El plan contempla actividades del día <b>{{ $range['start'] }}</b> al <b>{{ $range['end'] }}</b></p>
        </div>
        <div class="ph4">
            <div class="overflow-auto">
                @foreach($activities_week as $key => $day)
                    <h3 class="blue">{{ $key }}</h3>
                    <table class="f6 w-100 mw8 center system-sans-serif" cellspacing="0">
                        <thead>
                        <tr>
                            <th class="fw6 bb b--black-20 tl pb3 pr3 bg-white">Fecha y hora</th>
                            <th class="fw6 bb b--black-20 tl pb3 pr3 bg-white">Trabajo</th>
                            <th class="fw6 bb b--black-20 tl pb3 pr3 bg-white">Cliente</th>
                            <th class="fw6 bb b--black-20 tl pb3 pr3 bg-white">Kms por recorrer</th>
                            <th class="fw6 bb b--black-20 tl pb3 pr3 bg-white">Rendimiento</th>
                            <th class="fw6 bb b--black-20 tl pb3 pr3 bg-white">Actividad</th>
                        </tr>
                        </thead>
                        <tbody class="lh-copy">


                        @foreach($day as $activity)
                            <tr class="bg-near-white">
                                <td class="pv3 pr3 bb b--black-20">{{ $activity->date_activity }}</td>
                                <td class="pv3 pr3 bb b--black-20">{{ typeActivity($activity->type_activity) }}</td>
                                <td class="pv3 pr3 bb b--black-20">{{ hasClient($activity->client_id, $activity->client_name ) }}</td>
                                <td class="pv3 pr3 bb b--black-20">{{ $activity->kms }}</td>
                                <td class="pv3 pr3 bb b--black-20">{{ $activity->performance }}</td>
                                <td class="pv3 pr3 bb b--black-20">{{ type_activity($activity->activity) }}</td>
                            </tr>
                            <tr>
                                <td class="pv3 pr3 b--light-purple" colspan="6">
                                    Descripción de actividad: <b>{{$activity->description}}</b>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                @endforeach

            </div>
        </div>
        <div class="page_break"></div>
        <div class="ph4">
            <div class="overflow-auto">
                <h3 class="blue">Litros de Gasolina</h3>
                <table class="f6 mw8 center system-sans-serif" cellspacing="0">
                    <thead>
                    <tr>
                        <th class="fw6 bb b--black-20 tl pb3 pr3 bg-white">Fecha</th>
                        <th class="fw6 bb b--black-20 tl pb3 pr3 bg-white">Kms por recorrer</th>
                        <th class="fw6 bb b--black-20 tl pb3 pr3 bg-white">Rendimiento</th>
                    </tr>
                    </thead>
                    <tbody class="lh-copy">
                    @foreach($activities_week as $key => $day)
                        @foreach($day as $activity)
                            <tr class="bg-near-white">
                                <td class="pv3 pr3 bb b--black-20">{{ $activity->date_activity }}</td>
                                <td class="pv3 pr3 bb b--black-20">{{ $activity->kms }}</td>
                                <td class="pv3 pr3 bb b--black-20">{{ $activity->performance }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="ph4">
            <h4>Total de gasolina: <b>{{ round($sum_kms / $sum_performance, 3, PHP_ROUND_HALF_UP) }} Litros</b></h4>
            <p>El valor en litros de gasolina se encuentra redondeado</p>
        </div>
    </body>
</html>

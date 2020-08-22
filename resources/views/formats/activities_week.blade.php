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

    function activityTrad($activity){
        $activityTrad = [
            'presentation-project' => 'Presentación de proyecto',
            'presentation-system' => 'Presentación de sistema',
            'develop-project' => 'Desarrollo de proyecto',
            'installation-of-system' => 'Instalación de sistema',
            'calibration-of-equipment' => 'Calibración de equipo',
            'start-system-ccs' => 'Arranque de sistema CCS',
            'delivery-system' => 'Entrega de sistema a cliente',
            'other' => 'Otra',
            'preventive' => 'Mantenimiento preventivo',
            'corrective' => 'Mantenimiento correctivo'
        ];
        return $activityTrad[$activity];
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
        </style>
    </head>
    <body class="system-sans-serif">
        <div class="pa4">
            <h2>Reporte de actividades semanal</h2>
            <p>El plan contempla actividades del día <b>{{ $range['start'] }}</b> al <b>{{ $range['end'] }}</b></p>

            {{ $activities_week }}
        </div>
        <div class="ph4">
            <div class="overflow-auto">
                @foreach($activities_week as $key => $day)
                    <h3>{{ $key }}</h3>
                    <table class="f6 w-100 mw8 center" cellspacing="0">
                        <thead>
                        <tr>
                            <th class="fw6 bb b--black-20 tl pb3 pr3 bg-white">Fecha y hora</th>
                            <th class="fw6 bb b--black-20 tl pb3 pr3 bg-white">Trabajo</th>
                            <th class="fw6 bb b--black-20 tl pb3 pr3 bg-white">Cliente</th>
                            <th class="fw6 bb b--black-20 tl pb3 pr3 bg-white">Kms por recorrer</th>
                            <th class="fw6 bb b--black-20 tl pb3 pr3 bg-white">Actividad</th>
                        </tr>
                        </thead>
                        <tbody class="lh-copy">


                        @foreach($day as $activity)
                            <tr>
                                <td class="pv3 pr3 bb b--black-20">{{ $activity->date_activity }}</td>
                                <td class="pv3 pr3 bb b--black-20">{{ typeActivity($activity->type_activity) }}</td>
                                <td class="pv3 pr3 bb b--black-20">{{ hasClient($activity->client_id, $activity->client_name ) }}</td>
                                <td class="pv3 pr3 bb b--black-20">{{ $activity->kms }}</td>
                                <td class="pv3 pr3 bb b--black-20">{{ activityTrad($activity->activity) }}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                @endforeach

            </div>
        </div>
    </body>
</html>

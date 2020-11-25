<?php

if (!function_exists('type_activity')){
    function type_activity($activity){
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
}

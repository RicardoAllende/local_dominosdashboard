<?php

function local_dominosdashboard_get_kpi_info(int $courseid, array $params = array()){
    $kpis = array();
    $configs = get_config('local_dominosdashboard');
    $configs = (array) $configs;
    foreach(local_dominosdashboard_get_KPIS('list') as $kpi){
        $key = $kpi->id;
        if(isset($configs['kpi_' . $key])){
        // if($setting = get_config('local_dominosdashboard', 'kpi_' . $key)){
            $setting = $configs['kpi_' . $key];
            $courses = explode(',', $setting);
            if(array_search($courseid, $courses) !== false){
                $kpi_info = new stdClass();
                $kpi_info->kpi_name = $kpi->name;
                $kpi_info->kpi_key = $kpi->kpi_key;
                $kpi_info->type = $kpi->type;
                $kpi_info->value    = local_dominosdashboard_get_kpi_results($key, $params);
                
                array_push($kpis, $kpi_info);
            }
        }
    }
    return $kpis;
}
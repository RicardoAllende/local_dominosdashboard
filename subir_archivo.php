<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Plugin administration pages are defined here.
 *
 * @package     local_dominosdashboard
 * @category    admin
 * @copyright   2019 Subitus <contacto@subitus.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/upload_kpis.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/csvlib.class.php');

$pluginName = 'local_dominosdashboard';
$PAGE->set_context(context_system::instance());
$PAGE->set_url($CFG->wwwroot . "/local/dominosdashboard/subir_archivo.php");
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', $pluginName));
$PAGE->set_heading(get_string('pluginname', $pluginName));
// admin_externalpage_setup('dominosdashboard');
$returnurl = new moodle_url('/local/dominosdashboard/dashboard.php');

$mform = new local_dominosdashboard_upload_kpis();
if ($formdata = $mform->get_data()) {
    // echo "<style>
    // .dominosloader {
    //     border: 16px solid #f3f3f3; /* Light grey */
    //     border-top: 16px solid #3498db; /* Blue */
    //     border-radius: 50%;
    //     width: 120px;
    //     height: 120px;
    //     animation: dominosspin 2s linear infinite;
    //   }
      
    //   @keyframes dominosspin {
    //     0% { transform: rotate(0deg); }
    //     100% { transform: rotate(360deg); }
    // }</style>";
    echo $OUTPUT->header();
    // echo "<image id='loading-csv-content' src='https://thomas.vanhoutte.be/miniblog/wp-content/uploads/spinningwheel.gif'>";
    // echo "<div  id='loading-csv-content' class='dominosloader'></div>";
    
    $currentYear = $formdata->year; //date('Y');
    $iid = csv_import_reader::get_new_iid($pluginName);
    $cir = new csv_import_reader($iid, $pluginName);

    $content = $mform->get_file_content('userfile');
    // _log("Contenido del archivo", $content);

    $readcount = $cir->load_csv_content($content, $formdata->encoding, $formdata->delimiter_name);
    // _log("readcount", $readcount);
    $csvloaderror = $cir->get_error();
    if (!is_null($csvloaderror)) {
        print_error('Existe un error en la estructura de su archivo', '', $returnurl, $csvloaderror);
    }
    $columns = $cir->get_columns();
    $columns = array_map(function($element){
        return trim($element);
    }, $columns);
    $kpi = $formdata->kpi;
    $cir->init();
    $currenttime = time();
    switch ($kpi) {
        case KPI_OPS: // 1
            /*
                Columnas esperadas:
                CC,NOMBRE,REGION ,DISTRITAL COACH,CALIFICACION,ESTATUS,# CRITICOS,DÍA,SEMANA,MES,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,, 
                CC->ccosto,NOMBRE->nom_ccosto,REGION->region ,DISTRITAL COACH -> distrital,CALIFICACION -> calificacion,ESTATUS -> estatus,# CRITICOS,DÍA->day,SEMANA->week,MES->month
                CC,NOMBRE,REGION,DISTRITAL COACH,CALIFICACION,ESTATUS,DÍA,SEMANA,MES
            */
            $requiredFields=explode(',',"CC,NOMBRE,REGION,DISTRITAL COACH,CALIFICACIÓN,ESTATUS,DÍA,SEMANA,MES");
            
            $count= 0;
            $hasRequiredColumns = true;
            $columns_ = local_dominosdashboard_relate_column_with_fields($columns, $requiredFields, $hasRequiredColumns);
            if(!$hasRequiredColumns){
                $missingColumns = implode(',', $columns_);
                print_error('Faltan los siguientes campos:' . $missingColumns, '', $returnurl, $missingColumns);
            }
            $cir->init();
            $linenum = 1; //column header is first line
            global $DB;
            while ($line = $cir->next()) {
                // _log("Línea", $line);
                if(empty($line[$columns_["ESTATUS"]])){
                    // _log("Estatus vacío en la línea", $line);
                    continue;
                }
                if( ! $DB->record_exists('dominos_kpis', array('day' => $line[$columns_['DÍA']], 'ccosto' => $line[$columns_['CC']],
                    'kpi' => KPI_OPS,
                    'region' => $line[$columns_['REGION']],
                    'distrital' => $line[$columns_['DISTRITAL COACH']],
                    ))
                ){ 
                    $record = new stdClass();
                    $record->kpi = KPI_OPS;
                    $record->nombre = "AUDITORÍA ICA";
                    $record->valor = $line[$columns_['ESTATUS']];
                    $record->typevalue = "text";
                    $record->day = $line[$columns_['DÍA']];
                    $record->week = $line[$columns_['SEMANA']];
                    $record->month = local_dominosdashboard_format_month_from_kpi($line[$columns_['MES']]);
                    $record->year = $currentYear;
                    $record->region = $line[$columns_['REGION']];
                    $record->ccosto = $line[$columns_['CC']];
                    $record->nom_ccosto = $line[$columns_['NOMBRE']];
                    $record->original_time = $record->day . ' ' . $record->year;
                    $record->distrital = $line[$columns_['DISTRITAL COACH']];
                    $record->timecreated = $currenttime;
                    $DB->insert_record('dominos_kpis', $record);
                }else{
                    // Llegando aquí el KPI ya está registrado
                }
            }
        break;

        case KPI_HISTORICO: // 2
            /*
            Columnas esperadas
            Región / Razón social,No_Tienda,Nombre_Tienda,Nombre del Distrital / Gerente de Operaciones,1_ENERO,2_FEBRERO,3_MARZO,4_ABRIL,5_MAYO,6_JUNIO,7_JULIO,Total general
            Región / Razón social,No_Tienda,Nombre_Tienda,Nombre del Distrital / Gerente de Operaciones,1_ENERO,2_FEBRERO,3_MARZO,4_ABRIL,5_MAYO,6_JUNIO,7_JULIO,Total general
            */
            // Región / Razón social,No_Tienda,Nombre_Tienda,Nombre del Distrital / Gerente de Operaciones,1_ENERO,2_FEBRERO,3_MARZO,4_ABRIL,5_MAYO,6_JUNIO,7_JULIO,8_AGOSTO,9_SEPTIEMBRE,10_OCTUBRE,11_NOVIEMBRE,12_DICIEMBRE,Total general
        
            $meses = "12_DICIEMBRE,11_NOVIEMBRE,10_OCTUBRE,9_SEPTIEMBRE,8_AGOSTO,7_JULIO,6_JUNIO,5_MAYO,4_ABRIL,3_MARZO,2_FEBRERO,1_ENERO";
            $requiredFields=explode(',',"Región / Razón social,No_Tienda,Nombre_Tienda,Nombre del Distrital / Gerente de Operaciones,1_ENERO");
            
            $count= 0;
            $hasRequiredColumns = true;
            $columns_ = local_dominosdashboard_relate_column_with_fields($columns, $requiredFields, $hasRequiredColumns);
            if(!$hasRequiredColumns){
                $missingColumns = implode(',', $columns_);
                print_error('Faltan los siguientes campos:' . $missingColumns, '', $returnurl, $missingColumns);
            }
            $cir->init();
            global $DB;
            while ($line = $cir->next()) {
                $lastMonthColumn = local_dominosdashboard_get_last_month_key($columns);
                if($lastMonthColumn === -1){
                    // _log("No se envía el mes", $line);
                    continue;
                }
                $monthColumn = local_dominosdashboard_get_last_month_name($columns);
                if($line[$columns_['Región / Razón social']] == 'Total general'){
                    continue;
                }
                if( ! $DB->record_exists('dominos_kpis', array('original_time' => $line[$lastMonthColumn], 'ccosto' => $line[$columns_['No_Tienda']],
                    'kpi' => KPI_HISTORICO,
                    'year' => $currentYear,
                    'region' => $line[$columns_['Región / Razón social']],
                    'distrital' => $line[$columns_['Nombre del Distrital / Gerente de Operaciones']],
                    ))
                ){ 
                    $record = new stdClass();
                    $record->kpi = KPI_HISTORICO;
                    $record->nombre = 'NÚMERO DE QUEJAS';
                    $record->valor = $line[$lastMonthColumn];
                    $record->typevalue = 'number';
                    $record->day = '';
                    $record->week = '';
                    $record->month = local_dominosdashboard_convert_month_name($monthColumn);
                    $record->year = $currentYear;
                    $record->region = $line[$columns_['Región / Razón social']];
                    $record->ccosto = $line[$columns_['No_Tienda']];
                    $record->nom_ccosto = $line[$columns_['Nombre_Tienda']];
                    $record->original_time = $monthColumn . ' ' . $currentYear;
                    $record->distrital = $line[$columns_['Nombre del Distrital / Gerente de Operaciones']];
                    $record->timecreated = $currenttime;
                    $DB->insert_record('dominos_kpis', $record);
                }else{
                    // Llegando aquí el KPI ya está registrado
                }
            }
        break;

        case KPI_SCORCARD: // 3
            /*
            Columnas mostradas: REGION,GTE DTTO,CC,NOM_CCOSTO,AÑO,MES,INDICADOR,VALOR,FECHA,TIPO,Valor 1,,
            */
            // $requiredFields=explode(',',"CC,NOM_CCOSTO,REGION,GTE DTTO,Valor 1,ESTATUS,DIA,SEMANA,MES");
            $requiredFields=explode(',',"CC,NOM_CCOSTO,REGION,GTE DTTO,Valor 1,AÑO,FECHA,MES,INDICADOR,TIPO");
            
            $count= 0;
            $hasRequiredColumns = true;
            $columns_ = local_dominosdashboard_relate_column_with_fields($columns, $requiredFields, $hasRequiredColumns);
            if(!$hasRequiredColumns){
                $missingColumns = implode(',', $columns_);
                print_error('Faltan los siguientes campos:' . $missingColumns, '', $returnurl, $missingColumns);
            }
            $cir->init();
            global $DB;
            while ($line = $cir->next()) {
                if(empty($line[$columns_["Valor 1"]])){
                    // _log("Estatus vacío Valor 1 en la línea", $line);
                    continue;
                }
                if(!($line[$columns_['INDICADOR']] == "ROTACION MENSUAL" || $line[$columns_['INDICADOR']] == "ROTACION ROLLING")){
                    continue;
                }
                if( ! $DB->record_exists('dominos_kpis', array('original_time' => $line[$columns_['FECHA']], 'ccosto' => $line[$columns_['CC']],
                    'kpi' => KPI_SCORCARD,
                    'region' => $line[$columns_['REGION']],
                    'distrital' => $line[$columns_['GTE DTTO']],
                    'nombre'     => $line[$columns_['INDICADOR']],
                    ))
                ){ 
                    $record = new stdClass();
                    $record->kpi = KPI_SCORCARD;
                    $record->nombre = $line[$columns_['INDICADOR']];
                    $record->valor = $line[$columns_['Valor 1']];
                    $record->typevalue = $line[$columns_['TIPO']];
                    $record->day = '';
                    $record->week = '';
                    $record->month = local_dominosdashboard_format_month_from_kpi($line[$columns_['MES']]);
                    $record->year = $line[$columns_['AÑO']];
                    $record->region = $line[$columns_['REGION']];
                    $record->ccosto = $line[$columns_['CC']];
                    $record->nom_ccosto = $line[$columns_['NOM_CCOSTO']];
                    $record->original_time = $line[$columns_['FECHA']];
                    $record->distrital = $line[$columns_['GTE DTTO']];
                    $record->timecreated = $currenttime;
                    $DB->insert_record('dominos_kpis', $record);
                }else{
                    // Llegando aquí el KPI ya está registrado
                }
            }

            break;

        default:
            
            break;
    }
    unset($content);
    // echo "<h2>Se terminó la carga del documento</h2>";
    // echo "<script>
    //         document.addEventListener('DOMContentLoaded', function() {
    //             loader = document.getElementById('loading-csv-content');
    //             if(loader != undefined){
    //                 loader.style.display = 'none';
    //             }
    //         });
    //     </script>";
    echo $OUTPUT->footer();

} else {
    echo $OUTPUT->header();

    echo $OUTPUT->heading_with_help(get_string('uploadkpis', 'local_dominosdashboard'), 'uploadkpis', 'local_dominosdashboard');

    $mform->display();
    echo $OUTPUT->footer();
    die;
}
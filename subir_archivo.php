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
    _log('$cir->get_columns()', $columns);
    $kpi = $formdata->kpi;
    switch ($kpi) {
        /*
        campos de la tabla
        <FIELD NAME="id"            TYPE="int"   LENGTH="10"  NOTNULL="true"   SEQUENCE="true" />
        <FIELD NAME="kpi"           TYPE="char"  LENGTH="10" NOTNULL="false"  SEQUENCE="false" />
        <FIELD NAME="nombre"        TYPE="char"  LENGTH="255" NOTNULL="false"  SEQUENCE="false" />
        <FIELD NAME="valor"         TYPE="char"  LENGTH="255" NOTNULL="false"  SEQUENCE="false" />
        <FIELD NAME="typevalue"     TYPE="char"  LENGTH="255" NOTNULL="false"  SEQUENCE="false" />
        <FIELD NAME="day"           TYPE="int"   LENGTH="10"  NOTNULL="false"  SEQUENCE="false" />
        <FIELD NAME="week"          TYPE="int"   LENGTH="10"  NOTNULL="false"  SEQUENCE="false" />
        <FIELD NAME="month"         TYPE="int"   LENGTH="10"  NOTNULL="false"  SEQUENCE="false" />
        <FIELD NAME="year"          TYPE="int"   LENGTH="10"  NOTNULL="false"  SEQUENCE="false" />
        <FIELD NAME="region"        TYPE="char"  LENGTH="255" NOTNULL="false"  SEQUENCE="false" />
        <FIELD NAME="ccosto"        TYPE="char"  LENGTH="255" NOTNULL="false"  SEQUENCE="false" />
        <FIELD NAME="nom_ccosto"    TYPE="char"  LENGTH="255" NOTNULL="false"  SEQUENCE="false" />
        <FIELD NAME="original_time" TYPE="char"  LENGTH="255" NOTNULL="false"  SEQUENCE="false" />
        <FIELD NAME="distrital"     TYPE="char"  LENGTH="255" NOTNULL="false"  SEQUENCE="false" />
        <FIELD NAME="timecreated"   TYPE="int"   LENGTH="10"  NOTNULL="false"  SEQUENCE="false" />
        */
        case KPI_OPS:
            /*
                $record = new stdClass();
                $record->kpi = KPI_OPS;
                $record->nombre = "ICA";
                $record->valor = $line[$columns_['CALIFICACION']];
                $record->typevalue = "text";
                $record->day = "";
                $record->week = "";
                $record->month = "";
                $record->year = "";
                $record->region = "";
                $record->ccosto = "";
                $record->nom_ccosto = "";
                $record->original_time = "";
                $record->distrital = "";
                $record->timecreated = "";
            */
            /*
                Columnas esperadas:
                CC,NOMBRE,REGION ,DISTRITAL COACH,CALIFICACION,ESTATUS,# CRITICOS,DIA,SEMANA,MES,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,, 
                CC->ccosto,NOMBRE->nom_ccosto,REGION->region ,DISTRITAL COACH -> distrital,CALIFICACION -> calificacion,ESTATUS -> estatus,# CRITICOS,DIA->day,SEMANA->week,MES->month
                CC,NOMBRE,REGION,DISTRITAL COACH,CALIFICACION,ESTATUS,DIA,SEMANA,MES
            */
            $requiredFields=explode(',',"CC,NOMBRE,REGION,DISTRITAL COACH,CALIFICACION,ESTATUS,DIA,SEMANA,MES");
            
            //Si estatus está vacía ignorar esa columna

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
                _log("Línea", $line);
                if(empty($line[$columns_["ESTATUS"]])){
                    _log("Estatus vacío en la línea", $line);
                    continue;
                }
                if( ! $DB->record_exists('dominos_kpis', array('day' => $line[$columns_['DIA']], 'ccosto' => $line[$columns_['CC']],
                    // 'nom_ccosto' => $line[$columns_['NOMBRE']],
                    'region' => $line[$columns_['REGION']],
                    'distrital' => $line[$columns_['DISTRITAL COACH']],
                    ))
                ){ 
                    $record = new stdClass();
                    $record->kpi = KPI_OPS;
                    $record->nombre = "ICA";
                    $record->valor = $line[$columns_['ESTATUS']];
                    $record->typevalue = "text";
                    $record->day = $line[$columns_['DIA']];
                    $record->week = $line[$columns_['SEMANA']];
                    $record->month = $line[$columns_['MES']];
                    $record->year = date('Y');
                    $record->region = $line[$columns_['REGION']];
                    $record->ccosto = $line[$columns_['CC']];
                    $record->nom_ccosto = $line[$columns_['NOMBRE']];
                    $record->original_time = "";
                    $record->distrital = $line[$columns_['DISTRITAL COACH']];
                    $record->timecreated = time();
                    $DB->insert_record('dominos_kpis', $record);
                }else{
                    // Llegando aquí el KPI ya está registrado
                }
                if($DB->record_exists('dominos_kpis', array())){

                }
            }
        break;

        case KPI_HISTORICO: // 
        /*
        Columnas esperadas
        Regi�n / Raz�n social,No_Tienda,Nombre_Tienda,Nombre del Distrital / Gerente de Operaciones,1_ENERO,2_FEBRERO,3_MARZO,4_ABRIL,5_MAYO,6_JUNIO,7_JULIO,Total general
        Region / Razon social,No_Tienda,Nombre_Tienda,Nombre del Distrital / Gerente de Operaciones,1_ENERO,2_FEBRERO,3_MARZO,4_ABRIL,5_MAYO,6_JUNIO,7_JULIO,Total general
        */
        // 1_ENERO,2_FEBRERO,3_MARZO,4_ABRIL,5_MAYO,6_JUNIO,7_JULIO,8_AGOSTO,9_SEPTIEMBRE,10_OCTUBRE,11_NOVIEMBRE,12_DICIEMBRE,Total general
        
            $requiredFields=explode(',',"CC,NOM_CCOSTO,REGION,GTE DTTO,Valor 1,ANIO,FECHA,MES,INDICADOR,TIPO");
            $requiredFields=explode(',',"Region / Razon social,No_Tienda,REGION,GTE DTTO,Valor 1,ANIO,FECHA,MES,INDICADOR,TIPO");
            
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
                    _log("Estatus vacío en la línea", $line);
                    continue;
                }
                if( ! $DB->record_exists('dominos_kpis', array('original_time' => $line[$columns_['FECHA']], 'ccosto' => $line[$columns_['CC']],
                    // 'nom_ccosto' => $line[$columns_['NOMBRE']],
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
                    // $record->day = $line[$columns_['DIA']];
                    // $record->week = $line[$columns_['SEMANA']];
                    $record->month = $line[$columns_['MES']];
                    $record->year = 'ANIO';
                    $record->region = $line[$columns_['REGION']];
                    $record->ccosto = $line[$columns_['CC']];
                    $record->nom_ccosto = $line[$columns_['NOM_CCOSTO']];
                    $record->original_time = $line[$columns_['FECHA']];
                    $record->distrital = $line[$columns_['GTE DTTO']];
                    $record->timecreated = time();
                    $DB->insert_record('dominos_kpis', $record);
                }else{
                    // Llegando aquí el KPI ya está registrado
                }
                if($DB->record_exists('dominos_kpis', array())){

                }
            }
        break;

        case KPI_SCORCARD: // scorecard
        /*
        Columnas mostradas: REGION,GTE DTTO,CC,NOM_CCOSTO,ANIO,MES,INDICADOR,VALOR,FECHA,TIPO,Valor 1,,
        */
            // $requiredFields=explode(',',"CC,NOM_CCOSTO,REGION,GTE DTTO,Valor 1,ESTATUS,DIA,SEMANA,MES");
            $requiredFields=explode(',',"CC,NOM_CCOSTO,REGION,GTE DTTO,Valor 1,ANIO,FECHA,MES,INDICADOR,TIPO");
            
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
                    _log("Estatus vacío en la línea", $line);
                    continue;
                }
                if( ! $DB->record_exists('dominos_kpis', array('original_time' => $line[$columns_['FECHA']], 'ccosto' => $line[$columns_['CC']],
                    // 'nom_ccosto' => $line[$columns_['NOMBRE']],
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
                    // $record->day = $line[$columns_['DIA']];
                    // $record->week = $line[$columns_['SEMANA']];
                    $record->month = $line[$columns_['MES']];
                    $record->year = 'ANIO';
                    $record->region = $line[$columns_['REGION']];
                    $record->ccosto = $line[$columns_['CC']];
                    $record->nom_ccosto = $line[$columns_['NOM_CCOSTO']];
                    $record->original_time = $line[$columns_['FECHA']];
                    $record->distrital = $line[$columns_['GTE DTTO']];
                    $record->timecreated = time();
                    $DB->insert_record('dominos_kpis', $record);
                }else{
                    // Llegando aquí el KPI ya está registrado
                }
                if($DB->record_exists('dominos_kpis', array())){

                }
            }

            break;

        default:
            
            break;
    }
    // init csv import helper
    // $cir->init();
    // $linenum = 1; //column header is first line

    // while ($line = $cir->next()) {
    //     _log("Línea", $line);
    // }
    die("Se envió un documento");
    unset($content);

    
    // admin\tool\uploaduser\index.php
    // admin\tool\uploaduser\locallib.php
    // test if columns ok
    // $filecolumns = uu_validate_user_upload_columns($cir, $STD_FIELDS, $PRF_FIELDS, $returnurl);
    // continue to form2

} else {
    echo $OUTPUT->header();

    echo $OUTPUT->heading_with_help(get_string('uploadusers', 'tool_uploaduser'), 'uploadusers', 'tool_uploaduser');

    $mform->display();
    echo $OUTPUT->footer();
    die;
}
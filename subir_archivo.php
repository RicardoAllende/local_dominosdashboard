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
$returnurl = new moodle_url('/local/dominosdashboard/subir_archivo.php');

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
    _log('$cir->get_columns()', $columns);
    $kpi = $formdata->kpi;
    switch ($kpi) {
        /*
        campos de la tabla
        <FIELD NAME="id"            TYPE="int"   LENGTH="10"  NOTNULL="true"   SEQUENCE="true" />
        <FIELD NAME="kpi"           TYPE="char"  LENGTH="255" NOTNULL="false"  SEQUENCE="false" />
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
        Columnas esperadas:
        CC,NOMBRE,REGION ,DISTRITAL COACH,CALIFICACION,ESTATUS,# CRITICOS,DIA,SEMANA,MES,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,, 
        CC->ccosto,NOMBRE->nom_ccosto,REGION->region ,DISTRITAL COACH -> distrital,CALIFICACION -> calificacion,ESTATUS -> estatus,# CRITICOS,DIA->day,SEMANA->week,MES->month
        CC,NOMBRE,REGION,DISTRITAL COACH,CALIFICACION,ESTATUS,DIA,SEMANA,MES
        */
        $requiredFields="CC,NOMBRE,REGION,DISTRITAL COACH,CALIFICACION,ESTATUS,DIA,SEMANA,MES";
        /*
        Si estatus está vacía ignorar esa columna
        */
        while ($line = $cir->next()) {
            _log("Línea", $line);
            foreach ($line as $keynum => $value){
                
            }
        }
        // foreach($columns as $column){
        //     _log("Dentro de la columna ", $column, ' tipo de dato: ', gettype($column));
        // }
        break;

        case KPI_HISTORICO: // 
        /*
        Columnas esperadas
        Regi�n / Raz�n social,No_Tienda,Nombre_Tienda,Nombre del Distrital / Gerente de Operaciones,1_ENERO,2_FEBRERO,3_MARZO,4_ABRIL,5_MAYO,6_JUNIO,7_JULIO,Total general
        */
        
        foreach($columns as $column){
            _log("Dentro de la columna ", $column, ' tipo de dato: ', gettype($column));
        }
        break;

        case KPI_SCORCARD: // scorecard
        /*
        Columnas mostradas: REGION,GTE DTTO,CC,NOM_CCOSTO,ANIO,MES,INDICADOR,VALOR,FECHA,TIPO,Valor 1,,
        */
        foreach($columns as $column){
            _log("Dentro de la columna ", $column, ' tipo de dato: ', gettype($column));
        }   
            break;

        default:
            
            break;
    }
    // init csv import helper
    $cir->init();
    $linenum = 1; //column header is first line

    while ($line = $cir->next()) {
        _log("Línea", $line);
    }
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
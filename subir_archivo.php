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
require_once(__DIR__ . '/lib.php');
local_dominosdashboard_user_has_access();
require_once(__DIR__ . '/upload_kpis.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/csvlib.class.php');

$pluginName = 'local_dominosdashboard';
$context_system = context_system::instance();
$PAGE->set_context($context_system);
$PAGE->set_url($CFG->wwwroot . "/local/dominosdashboard/subir_archivo.php");
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', $pluginName));
$PAGE->set_heading(get_string('pluginname', $pluginName));
// admin_externalpage_setup('dominosdashboard');
$returnurl = new moodle_url('/local/dominosdashboard/dashboard.php');

$mform = new local_dominosdashboard_upload_kpis();
if ($formdata = $mform->get_data()) {
    echo $OUTPUT->header();
    $tiempo_inicial = microtime(true);
    
    $currentYear = $formdata->year; //date('Y');
    $month = $formdata->month;
    $kpi_date = local_dominosdashboard_get_time_from_month_and_year($month, $currentYear);
    $updateIfExists = $formdata->update_existent;
    _log('editar si existe');
    die('Enviado');
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
    $requiredFields=explode(',',"profile_field_ccosto,CECO,CALIFICACIÓN,ESTATUS,TOTAL QUEJAS (NO.),ROTACION MENSUAL %,ROTACION ROLLING %");

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
        $ccosto = $columns_['profile_field_ccosto'];
        $calificacion = $columns_['CALIFICACIÓN'];
        $estatus = $columns_['ESTATUS'];
        $quejas = $columns_['profile_field_ccosto'];
        $rotacion_mensual = $columns_['TOTAL QUEJAS (NO.)'];
        $rotacion_rolling = $columns_['ROTACION MENSUAL %'];
        $ceco = $columns_['ROTACION ROLLING %'];

        $record = $DB->get_record('dominos_kpis', array('ccosto' => $ccosto, 'kpi_date' => $kpi_date));
        if( empty($record) ){
            $record = new stdClass();
            $record->ccosto = $ccosto;
            $record->ceco = $ceco;
            $record->calificacion = $calificacion;
            $record->estatus = $estatus;
            $record->quejas = $quejas;
            $record->rotacion_mensual = $rotacion_mensual;
            $record->rotacion_rolling = $rotacion_rolling;
            $record->kpi_date = $kpi_date;
            $record->month = $month;
            $record->year = $currentYear;
            $record->timecreated = $currenttime;

            $DB->insert_record('dominos_kpis', $record);
        }else{// El kpi existe
            if($updateIfExists){ // Editando el kpi en caso de seleccionar la opción
                $record->ccosto = $ccosto;
                $record->ceco = $ceco;
                $record->calificacion = $calificacion;
                $record->estatus = $estatus;
                $record->quejas = $quejas;
                $record->rotacion_mensual = $rotacion_mensual;
                $record->rotacion_rolling = $rotacion_rolling;
                $record->kpi_date = $kpi_date;
                $record->month = $month;
                $record->year = $currentYear;
                $record->timecreated = $currenttime;
                $DB->update_record('dominos_kpis', $record);
            }
        }
    }

    unset($content);
    $tiempo_final = microtime(true);
    $tiempo = $tiempo_final - $tiempo_inicial;
    if(LOCALDOMINOSDASHBOARD_DEBUG){
        echo $OUTPUT->heading("El tiempo de proceso del archivo fue de: " . $tiempo);
    }
    echo $OUTPUT->heading("Su archivo ha sido procesado");
    echo $OUTPUT->heading("Si desea subir más archivos, recargue la página");
    echo "<div class='row' style='text-align: center; padding: 5%;'>";
    echo "<div class='col-sm-6'>";
    // $route = $CFG->wwwroot . '/local/dominosdashboard/subir_archivo.php';
    echo "<button class='btn btn-success text-center' style='text-align: center !important;' onclick='window.location.href = window.location.href'>Recargar la página</button>";
    echo "</div>";
    echo "<div class='col-sm-6'>";
    $route = $CFG->wwwroot . '/local/dominosdashboard/dashboard.php';
    echo "<a class='btn btn-success text-center' style='text-align: center !important;' href='{$route}'>Ver tablero Domino's</a>";
    echo "</div>";
    echo $OUTPUT->footer();

} else {
    echo $OUTPUT->header();

    echo $OUTPUT->heading_with_help(get_string('uploadkpis', 'local_dominosdashboard'), 'uploadkpis', 'local_dominosdashboard');

    $mform->display();
    echo $OUTPUT->footer();
    die;
}
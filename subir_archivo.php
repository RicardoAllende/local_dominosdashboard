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
$currenturl = $CFG->wwwroot . "/local/dominosdashboard/subir_archivo.php";
$PAGE->set_url($currenturl);
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', $pluginName));
$PAGE->set_heading(get_string('pluginname', $pluginName));
// admin_externalpage_setup('dominosdashboard');

$mform = new local_dominosdashboard_upload_kpis();
global $CFG;
$link = $CFG->wwwroot . '/local/dominosdashboard/administrar_KPIS.php';
$managekpis = $CFG->wwwroot . '/local/dominosdashboard/administrar_KPIS.php';
$settingsurl = $CFG->wwwroot . '/admin/settings.php?section=local_dominosdashboard';
$returnurl = $managekpis;

echo $OUTPUT->header();
echo "
<div class='row' style='padding-bottom: 2%;'>
    <div class='col-sm-4' style='text-align: right;'>
    </div>
    <div class='col-sm-4' style='text-align: center;'>
        <a class='btn btn-primary btn-lg' href='{$managekpis}'>Administrar KPI's</a>
    </div>
    <div class='col-sm-4' style='text-align: left;'>
        <a class='btn btn-primary btn-lg' href='{$settingsurl}'>Configuraciones del plugin</a>
    </div>
</div>
";
// echo "<br>";

$fields = local_dominosdashboard_get_kpi_list('menu');
// $fields = implode(',', local_dominosdashboard_get_kpi_list('menu'));
echo "<div class='row' style='padding-left: 2%;'><div class='col-sm-6'><h4 style='font-weight: 300;'>Las columnas disponibles son:</h4>";
foreach ($fields as $fieldid => $field) {
    echo "<p>{$field}</p>";
}
echo "  </div>
        <div class='col-sm-6'>
            <h2>Recuerde establecer  la relación entre los cursos y su KPI en las configuraciones del plugin</h2>
            <div style='padding-top: 2%; text-align: center;'>
                <a class='btn btn-success' href='{$currenturl}'>Recargar la página</a>
            </div>
        </div>
    </div><br>";
if ($formdata = $mform->get_data()) {
    $tiempo_inicial = microtime(true);
    
    $currentYear = $formdata->year; //date('Y');
    $month = $formdata->month;
    $kpi_date = local_dominosdashboard_get_time_from_month_and_year($month, $currentYear);
    $updateIfExists = $formdata->updateIfExists;
    $iid = csv_import_reader::get_new_iid($pluginName);
    $cir = new csv_import_reader($iid, $pluginName);

    $content = $mform->get_file_content('userfile');

    $readcount = $cir->load_csv_content($content, $formdata->encoding, $formdata->delimiter_name);
    $csvloaderror = $cir->get_error();
    if (!is_null($csvloaderror)) {
        print_error('Existe un error en la estructura de su archivo', '', $returnurl, $csvloaderror);
    }
    $columns = $cir->get_columns();
    $columns = array_map(function($element){
        return trim($element);
    }, $columns);
    $currenttime = time();

    $count= 0;
    $hasRequiredColumns = true;
    $columns_ = local_dominosdashboard_read_kpis_from_columns($columns, $hasRequiredColumns);
    if(!$hasRequiredColumns){
        print_error($columns_, '', $returnurl, $columns_);
    }
    $requiredColumns = local_dominosdashboard_required_kpi_columns;
    $requiredColumnsKeys = array_keys($requiredColumns);
    global $DB;
    $cir->init();
    while ($line = $cir->next()) {

        $ccosto = $columns_->ccosto;
        
        foreach($columns_->kpis as $kpi){
            $conditions = array('ccosto' => $ccosto, 'kpi_date' => $kpi_date, 'kpi_key' => $kpi->kpi_key);
            foreach($requiredColumnsKeys as $rck){
                // $record->$rck = $line[$columns_->$rck];
                $conditions[$rck] = $line[$columns_->$rck];
            }
            _log(compact('conditions'));
            $record = $DB->get_record('dominos_kpis', $conditions);
            if( empty($record) ){
                $record = new stdClass();
                foreach($requiredColumnsKeys as $rck){
                    $record->$rck = $line[$columns_->$rck];
                }
                // $record->ccosto = $line[$columns_->ccosto];
                $record->kpi_key = $kpi->kpi_key;
                $record->value = $line[$kpi->position];
                $record->kpi_date = $kpi_date;
                $record->month = $month;
                $record->year = $currentYear;
                $record->timecreated = $currenttime;
                $DB->insert_record('dominos_kpis', $record);
            }else{
                if($updateIfExists){
                    foreach($requiredColumnsKeys as $rck){
                        $record->$rck = $line[$columns_->$rck];
                    }
                    // $record->ccosto = $line[$columns_->ccosto];
                    $record->kpi_key = $kpi->kpi_key;
                    $record->value = $line[$kpi->position];
                    $record->kpi_date = $kpi_date;
                    $record->month = $month;
                    $record->year = $currentYear;
                    $record->timecreated = $currenttime;
                    $DB->update_record('dominos_kpis', $record);
                }
            }
        }
    }

    unset($content);
    $tiempo_final = microtime(true);
    $tiempo = $tiempo_final - $tiempo_inicial;
    // if(LOCALDOMINOSDASHBOARD_DEBUG){
    //     echo $OUTPUT->heading("El tiempo de proceso del archivo fue de: " . $tiempo);
    // }
    echo $OUTPUT->heading("Su archivo ha sido procesado. Si desea subirlo nuevamente recargue la página");
    $mform->display();

} else {

    echo $OUTPUT->heading_with_help(get_string('uploadkpis', 'local_dominosdashboard'), 'uploadkpis', 'local_dominosdashboard');

    $mform->display();
}
echo $OUTPUT->footer();
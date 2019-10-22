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
 * Plugin strings are defined here.
 *
 * @package     local_dominosdashboard
 * @category    dashboard
 * @copyright   2019 Subitus <contacto@subitus.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
// require_capability('local/dominosdashboard:view', context_system::instance()); // Requiere permisos de capa que en primera instancia son otorgados al administrador del sistema
require_once(__DIR__ . '/lib.php');
// require_login();
global $DB;
$PAGE->set_url($CFG->wwwroot . "/local/dominosdashboard/pruebas.php");
$courseid = optional_param('id', 0, PARAM_INT);
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'local_dominosdashboard'));
require_once(__DIR__ . '/../../lib/enrollib.php');
echo $OUTPUT->header();
$tiempo_inicial = microtime(true); //true es para que sea calculado en segundos

// _print(local_dominosdashboard_get_courses_overview(LOCALDOMINOSDASHBOARD_PROGRAMAS_ENTRENAMIENTO));
// _print(local_dominosdashboard_get_info_from_cache($courseid, $params = $_POST));
_print(local_dominosdashboard_make_courses_cache());
// $currenttime = time();
// $course_information = local_dominosdashboard_get_course_information(8);
// // _print($course_information);
// $record = new stdClass();
// $record->courseid = 1;
// $record->title = $course_information->title;
// $record->enrolled_users = $course_information->enrolled_users;
// $record->approved_users = $course_information->approved_users;
// $record->percentage = $course_information->percentage;
// $record->value = $course_information->value;
// // $record->startdate = $course_information->fecha_inicial;
// // $record->enddate = $course_information->fecha_final;
// // $record->regiones = $conditions->regiones;
// $record->distritos = null;
// $record->entrenadores = null;
// $record->tiendas = null;
// $record->puestos = null;
// $record->ccosto = null;
// $record->timemodified = $currenttime;
// _print($record);
// $DB->insert_record('dominos_d_cache', $record);


$tiempo_final = microtime(true);
$tiempo = $tiempo_final - $tiempo_inicial; //este resultado estará en segundos
echo "<br>El tiempo de ejecución del archivo ha sido de " . $tiempo . " segundos";
echo $OUTPUT->footer();
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

// $columnas = array(
//     0 => trim('CC'),
//     1 => trim('NOMBRE'),
//     2 => trim('REGION '),
//     3 => trim('DISTRITAL COACH'),
//     4 => trim('CALIFICACION'),
//     5 => trim('ESTATUS'),
//     6 => trim('# CRITICOS'),
//     7 => trim('DIA'),
//     8 => trim('SEMANA'),
//     9 => trim('MES'),
// );

// _print(local_dominosdashboard_relate_column_with_fields($columnas, explode(',', "CC,CALIFICACION,ESTATUS,DIA,SEMANA,NOMBRE,REGION,DISTRITAL COACH,MES")));

// foreach(local_dominosdashboard_get_courses() as $course){
//     // _print("Actividades del curso ", $course->fullname, local_dominosdashboard_get_activities($course->id));
//     _print('Actividades del curso ' . $course->fullname, local_dominosdashboard_get_activities_completion($course->id, "1,2,3,4,5,6"));
// }
// $courseid = 8;
$coursecontext = context_course::instance($courseid);
$users = get_enrolled_users($coursecontext, '', 0, 'distinct u.id', '');
_print($users);
_print('Número de ' . count($users));
$users = local_dominosdashboard_get_enrolled_users_ids($courseid);
_print($users);
_print('Número de ' . count($users));
// _print(local_dominosdashboard_get_user_catalogues($params = array()));
// local_dominosdashboard_make_all_historic_reports();

// foreach(local_dominosdashboard_get_KPIS() as $kpi_key => $kpi){
//     echo "<h1>Tipo de KPI {$kpi}<h1>";
//     foreach(local_dominosdashboard_get_kpi_indicators() as $indicator){
//         $catalogue = local_dominosdashboard_get_kpi_catalogue($indicator, $kpi_key);
//         $elements = count($catalogue);
//         echo "<h4>Catálogo <<{$indicator}>> tiene {$elements} elementos<h4>";
//     }
// }

// foreach(local_dominosdashboard_get_courses() as $course){
//     echo $OUTPUT->heading("Curso: " . $course->fullname);
//     _print(local_dominosdashboard_get_historic_dates($course->id));
// }

echo $OUTPUT->footer();
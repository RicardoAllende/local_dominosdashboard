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
$context_system = context_system::instance();
require_capability('local/dominosdashboard:view', $context_system);
require_once(__DIR__ . '/lib.php');
require_once("$CFG->libdir/gradelib.php");
require_once("$CFG->dirroot/grade/querylib.php");
require_login();
global $DB;
$PAGE->set_url($CFG->wwwroot . "/local/dominosdashboard/reportes_historicos.php");
$PAGE->set_context($context_system);
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'local_dominosdashboard'));

echo $OUTPUT->header();
echo "<script> var indicadores = '" . DOMINOSDASHBOARD_INDICATORS . "'</script>";

$tabOptions = [
    LOCALDOMINOSDASHBOARD_PROGRAMAS_ENTRENAMIENTO => 'Programas de entrenamiento',
    LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS => 'Campañas',
    LOCALDOMINOSDASHBOARD_COURSE_KPI_COMPARISON => 'Comparación de kpis',
];
$courses    = local_dominosdashboard_get_courses();
// $reports = local_dominosdashboard_get_historic_reports();
foreach($courses as $course){
?>

<table class="table table-bordered">
    <tr>
        <td>Curso</td>
        <td>Usuarios inscritos</td>
        <td>Usuarios aprobados</td>
        <td>Filtro</td>
        <td>Texto</td>
        <td>Fecha de creación</td>
    </tr>
    <?php 
    foreach(local_dominosdashboard_get_historic_reports($course->id) as $report){
        echo "<tr>
                <td>{$report->fullname}</td>
                <td>{$report->enrolled_users}</td>
                <td>{$report->approved_users}</td>
                <td>{$report->filterid}</td>
                <td>{$report->filtertext}</td>
                <td>{$report->fecha}</td>
            </tr>";
    }
    ?>
</table>

<?php
}
echo $OUTPUT->footer();
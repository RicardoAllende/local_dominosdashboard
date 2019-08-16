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
require_capability('local/dominosdashboard:view', context_system::instance());
require_once(__DIR__ . '/lib.php');
require_once("$CFG->libdir/gradelib.php");
require_once("$CFG->dirroot/grade/querylib.php");
require_login();
global $DB;
$PAGE->set_url($CFG->wwwroot . "/local/dominosdashboard/detalle_curso.php");
$courseid = optional_param('courseid', 27, PARAM_INT);
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'local_dominosdashboard'));

echo $OUTPUT->header();
// local_dominosdashboard_get_course_all_catalogues($courseid);
$params = [
    'puestos'       => 'Cajero de sucursal',
    'regiones'      => 'Sur',
    'tiendas'       => 'Perisur',
    'entrenadores'  => 'Julián '
];
_print($params);
$course_information = local_dominosdashboard_get_course_information(27, 'Completado Con criterio', true, $params);
_print($course_information);
echo $OUTPUT->footer();
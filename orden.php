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
require_login();
require_once(__DIR__ . '/lib.php');
local_dominosdashboard_user_has_access();

global $DB;
$PAGE->set_url($CFG->wwwroot . "/local/dominosdashboard/orden.php");
$PAGE->set_context($context_system);
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'local_dominosdashboard'));
echo $OUTPUT->header();

// list($default_fields, $custom_fields) = local_dominosdashboard_get_report_fields();
// $allfields = array_merge($default_fields, $custom_fields);
// _print($allfields);
$all_default_profile_fields = local_dominosdashboard_get_default_profile_fields();
$custom_fields = local_dominosdashboard_get_custom_profile_fields();
$allfields = array_merge($all_default_profile_fields, $custom_fields);
echo "<table class='table'>";
foreach($allfields as $key => $name){
    echo "
    <tr>
        <td>{$name} </td>
        <td><button onclick='moverElemento(\"{$key}\", \"up\")' class='btn btn-info'>Subir</button></td>
        <td><button onclick='moverElemento(\"{$key}\", \"down\")' class='btn btn-info'>Bajar</button></td>
    </tr>";
}
echo "</table>";
?>
<script src="js/jquery.min.js"></script>
<script>
    function moverElemento(clave, movimiento){
        
    }
</script>
<?php
echo $OUTPUT->footer();

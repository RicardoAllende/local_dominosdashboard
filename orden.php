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

$update_key = optional_param('update_key', '', PARAM_TEXT);
$update_action = optional_param('update_action', '', PARAM_TEXT);

if($update_key != '' && $update_action != ''){
    local_dominosdashboard_set_new_order($update_key, $update_action);
}

$allfields = local_dominosdashboard_get_report_fields_in_order();
$num_fields = count($allfields);
if($num_fields == 0){
    print_error('No se ha configurado ningún campo personalizado');
}
$keys = array_keys($allfields);
$firstkey = $keys[0];
if($num_fields > 1){
    $lastkey  = $keys[$num_fields - 1];
}else{
    $lastkey = '';
}
$reporte = $CFG->wwwroot . '/local/dominosdashboard/reporte_personalizado.php';
$settingsurl = $CFG->wwwroot . '/admin/settings.php?section=local_dominosdashboard';
?>
<div class="row" style="padding-bottom: 2%;">
    <div class="col-sm-6" style="text-align: center;">
        <!-- <h4>Si el reporte no tiene la estructura necesaria, por favor ordene los campos del reporte en el siguiente enlace: </h4> -->
        <a class="btn btn-primary btn-lg" href="<?php echo $reporte; ?>">Ver reporte personalizado</a>
    </div>
    <div class="col-sm-6" style="text-align: center;">
        <h4>Si desea modificar los campos que aparecerán, o los cursos incluídos por favor edítelo en Configuraciones del plugin->reportes personalizados </h4>
        <a class="btn btn-primary btn-lg" href="<?php echo $settingsurl; ?>">Configuraciones del plugin</a>
    </div>
</div>
<?php
echo "<table class='table table-hover text-center'  rules='rows' style='width: 80%; text-align: center; margin: auto;'>";
echo "
    <thead class='thead-dark' style='background: black; color: white; font-weight: bold;'>
    <tr>
        <td>Nombre del filtro</td>
        <td>Subir posición</td>
        <td>Bajar posición</td>
    </tr>
    </thead><tbody>
    ";
foreach($allfields as $key => $name){
    echo "<tr>";
    echo "<td>{$name} </td>";
    if($firstkey !== $key){
        echo "<td><button onclick='moverElemento(\"{$key}\", \"up\")' class='btn btn-info'>Subir</button></td>";
    }else{
        echo "<td></td>";
    }
    if($lastkey !== $key){
        echo "<td><button onclick='moverElemento(\"{$key}\", \"down\")' class='btn btn-info'>Bajar</button></td>";
    }else{
        echo "<td></td>";
    }
    echo "</tr>";
}
echo "</tbody></table>";
?>
<script src="js/jquery.min.js"></script>
<script>
    function moverElemento(clave, movimiento){
        urlActual = location.protocol + '//' + location.host + location.pathname;
        nueva_url = urlActual = `?update_key=${clave}&update_action=${movimiento}`;
        window.location.href = nueva_url;
    }
</script>
<?php
echo $OUTPUT->footer();

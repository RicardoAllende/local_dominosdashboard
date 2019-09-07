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
$PAGE->set_url($CFG->wwwroot . "/local/dominosdashboard/_Dashboard.php");
$PAGE->set_context($context_system);
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'local_dominosdashboard'));

echo $OUTPUT->header();

$courses = local_dominosdashboard_get_courses();
?>
<div class="row">
    <form id="filter_form" method="post" action="services.php" class='col-sm-4'>
        <?php
        foreach(local_dominosdashboard_get_kpi_indicators() as $indicator){
            echo "<h3>Indicador: {$indicator}</h3>";
            foreach(local_dominosdashboard_get_kpi_catalogue($indicator) as $itemkey =>$item){
                if($indicator == 'tiendas'){
                    echo "<label><input type=\"checkbox\" name=\"{$indicator}[]\" class=\"indicator_option indicator_{$indicator}\" data-indicator=\"{$indicator}\" value=\"{$itemkey}\">{$item}</label><br>";
                }else{
                    echo "<label><input type=\"checkbox\" name=\"{$indicator}[]\" class=\"indicator_option indicator_{$indicator}\" data-indicator=\"{$indicator}\" value=\"{$item}\">{$item}</label><br>";
                }
            }
            echo "<span type=\"checkbox\" class=\"btn btn-info uncheck_indicators\" data-indicator=\"indicator_{$indicator}\" value=\"1\">Desmarcar todas</span>";
            echo "<span type=\"checkbox\" class=\"btn btn-success check_indicators\" data-indicator=\"indicator_{$indicator}\" value=\"1\">Marcar todas</span><br>";
        }
        echo "<br><select class='form-control course-selector' name='courseid'>";
        foreach($courses as $course){
            echo "<option value='{$course->id}'>{$course->id} -> {$course->fullname}</option>";
        }
        echo "</select>";
        ?>
        <input type="hidden" name="request_type" value="course_completion"><br><br>
        <span class="btn btn-info" onclick="obtenerInformacion()">Volver a simular obtención de gráficas</span>
    </form>
    <div class="col-sm-8" id="local_dominosdashboard_content"></div>
    <!-- <div class="col-sm-12" style="padding-top: 50px;" id="local_dominosdashboard_request"></div> -->
</div>
<script>
    var indicator;
    var item;
    var serialized_form = "";
    var demark = "";
    document.addEventListener("DOMContentLoaded", function() {
        try{
            require(['jquery'], function($) {
                $('.indicator_option').click(function(){
                    indicator = $(this).attr('data-indicator');
                    value = $(this).val();
                    // console.log(indicator);
                    // console.log(value);
                    obtenerInformacion();
                });
                // function 
                $('.course-selector').change(function(){
                    obtenerInformacion();
                });
                $('.uncheck_indicators').click(function(){
                    demark = $(this).attr('data-indicator');
                    $('.' + demark).prop('checked', false);
                    obtenerInformacion();
                });
                $('.check_indicators').click(function(){
                    demark = $(this).attr('data-indicator');
                    $('.' + demark).prop('checked', true);
                    obtenerInformacion();
                });
                obtenerInformacion();
            });
        }catch(error){
            console.log(error);
        }
    });
    var dateBegining;
    var dateEnding;
    function obtenerInformacion(){
        console.log("Obteniendo gráficas");
        informacion = $('#filter_form').serializeArray();
        // $('#local_dominosdashboard_request').html("<br><br>La petición enviada es: <br>" + $('#filter_form').serialize());
        dateBegining = Date.now();
        // $('#local_dominosdashboard_content').html('Cargando la información');
        $.ajax({
            type: "POST",
            url: "services.php",
            data: informacion,
            dataType: "json"
        })
        .done(function(data) {
            dateEnding = Date.now();
            console.log(`Tiempo de respuesta de API ${dateEnding - dateBegining} ms`);
            console.log("Petición correcta");
            console.log(data);
            // $('#local_dominosdashboard_content').html('<pre>' + JSON.stringify(data, undefined, 2) + '</pre>');
        })
        .fail(function(error, error2) {
            console.log(error);
            console.log(error2);
        });
        // });
    }
</script>
<?php
// echo local_dominosdashboard_format_response($course_elearning);
// echo "<br><br><br>";
// echo local_dominosdashboard_format_response($classroom);
// echo "<br><br><br>";
// echo local_dominosdashboard_format_response($comparison);
// echo "<br><br><br>";

// $result = array();
// $result['status'] = $status;
// $result['courses'] = $count;
// $result['count'] = $data;
// return json_encode($result);

// _print('local_dominosdashboard_get_courses_overview(1)', $course_elearning);
// _print('local_dominosdashboard_get_courses_overview(2)', $classroom);
// _print('local_dominosdashboard_get_courses_overview(3)', $comparison);
// Contenido del dashboard
echo $OUTPUT->footer();
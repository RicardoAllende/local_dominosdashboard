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
$PAGE->set_url($CFG->wwwroot . "/local/dominosdashboard/historicos.php");
$PAGE->set_context($context_system);
$PAGE->set_pagelayout('admin');
$PAGE->set_title('Históricos ' . get_string('pluginname', 'local_dominosdashboard'));

echo $OUTPUT->header();

$courses = local_dominosdashboard_get_courses();

$indicators = local_dominosdashboard_get_indicators();
?>
<div class="row">
    <form id="filter_form" method="post" action="services.php" class='col-sm-4'>
        <span class="btn btn-success" onclick="quitarFiltros()">Quitar todos los filtros</span><br>
        <?php
        echo "<br><select class='form-control course-selector' name='courseid'>";
        foreach($courses as $course){
            echo "<option value='{$course->id}'>{$course->id} -> {$course->fullname}</option>";
        }
        echo "</select>";
        foreach($indicators as $indicator){
            echo "<h3>Indicador: {$indicator} </h3>";
            echo "<div id='indicator_section_{$indicator}'></div>";
        }
        ?>
        <span class="btn btn-info" onclick="obtenerGraficas()">Volver a simular obtención de gráficas</span>
    </form>
    <div class="col-sm-8" id="local_dominosdashboard_content"></div>
    <div class="col-sm-12" style="padding-top: 50px;" id="local_dominosdashboard_request"></div>
</div>
<?php echo local_dominosdashboard_get_ideales_as_js_script(); ?>
<script>
    var indicator;
    var item;
    var serialized_form = "";
    var demark = "";
    document.addEventListener("DOMContentLoaded", function() {
        try{
            require(['jquery'], function($) {
                $('.course-selector').change(function(){obtenerGraficas()});
                obtenerGraficas();
                obtenerFiltros();
            });
        }catch(error){
            console.log(error);
        }
    });
    var dateBegining;
    var dateEnding;
    function quitarFiltros(){
        peticionFiltros({
            request_type: 'user_catalogues'
        });
    }
    function obtenerGraficas(indicator){
        console.log("Obteniendo gráficas");
        informacion = $('#filter_form').serializeArray();
        informacion.push({name: 'request_type', value: 'course_completion'});
        $('#local_dominosdashboard_request').html("<br><br>La petición enviada es: <br>" + $('#filter_form').serialize());
        dateBegining = Date.now();
        $('#local_dominosdashboard_content').html('Cargando la información');
        $.ajax({
            type: "POST",
            url: "services.php",
            data: informacion,
            dataType: "json"
        })
        .done(function(data) {
            dateEnding = Date.now();
            console.log(`Tiempo de respuesta de API al obtener json para gráficas ${dateEnding - dateBegining} ms`);
            // console.log("Petición correcta");
            // console.log(data);
            $('#local_dominosdashboard_content').html(JSON.stringify(data).replace(/{/g, "<br/>{"));
        })
        .fail(function(error, error2) {
            console.log(error);
            console.log(error2);
        });
        if(indicator !== undefined){
            obtenerFiltros(indicator);
        }
    }
    function peticionFiltros(info){
        $.ajax({
            type: "POST",
            url: "services.php",
            data: info,
            dataType: "json"
        })
        .done(function(data) {
            dateEnding = Date.now();
            console.log(`Tiempo de respuesta al obtener filtros de API ${dateEnding - dateBegining} ms`);
            console.log(data);
            keys = Object.keys(data.data);
            for (var index = 0; index < keys.length; index++) {
                clave = keys[index]
                var catalogo = data.data[clave];
                console.log(clave, catalogo.length);
                $('#indicator_section_' + clave).html('');
                for(var j = 0; j < catalogo.length; j++){
                    var elementoDeCatalogo = catalogo[j];
                    if(elementoDeCatalogo == ''){
                        $('#indicator_section_' + clave).append(`<label><input type=\"checkbox\" name=\"${clave}[]\" 
                        class=\"indicator_option indicator_${clave}\" onclick="obtenerGraficas('${clave}')" data-indicator=\"${clave}\" value=\"${elementoDeCatalogo}\">(vacío)</label><br>`);
                    }else{
                        $('#indicator_section_' + clave).append(`<label><input type=\"checkbox\" name=\"${clave}[]\" 
                        class=\"indicator_option indicator_${clave}\" onclick="obtenerGraficas('${clave}')" data-indicator=\"${clave}\" value=\"${elementoDeCatalogo}\">${elementoDeCatalogo}</label><br>`);
                    }
                }
            }
        })
        .fail(function(error, error2) {
            console.log(error);
            console.log(error2);
        });
    }
    function obtenerFiltros(indicator){
        console.log("Obteniendo filtros");
        informacion = $('#filter_form').serializeArray();
        dateBegining = Date.now();
        informacion.push({name: 'request_type', value: 'user_catalogues'});
        if(indicator != undefined){
            informacion.push({name: 'selected_filter', value: indicator});
        }
        peticionFiltros(informacion);
    }
</script>
<?php
// Contenido del dashboard
echo $OUTPUT->footer();
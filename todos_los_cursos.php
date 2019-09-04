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
$PAGE->set_url($CFG->wwwroot . "/local/dominosdashboard/todos_los_cursos.php");
$PAGE->set_context($context_system);
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'local_dominosdashboard'));

echo $OUTPUT->header();
echo "<script> var indicadores = '" . DOMINOSDASHBOARD_INDICATORS . "'</script>";

$tabOptions = local_dominosdashboard_get_course_tabs();

$indicators = local_dominosdashboard_get_indicators();
?>
<div class="row">
    <form id="filter_form" method="post" action="services.php" class='col-sm-3'>
        <span class="btn btn-success" onclick="quitarFiltros()">Quitar todos los filtros</span><br><br>
        <span class="btn btn-info" onclick="obtenerGraficas()">Volver a simular obtención de gráficas</span><br><br>
        <?php
        echo "<br><select class='form-control' id='tab-selector' name='type'>";
        foreach($tabOptions as $key => $option){
            echo "<option value='{$key}'>{$key}. {$option}</option>";
        }
        echo "</select><br>";
        ?>
        <div id='contenedor_filtros'></div>
    </form>
    <div class="col-sm-9" id="contenido_cursos">
        <div>
            <div class="row" id="contenedor_cursos">
            </div>
        </div>
    </div>
    <div class="col-sm-12" id="local_dominosdashboard_content"></div>
    <div class="col-sm-12" style="padding-top: 50px;" id="local_dominosdashboard_request"></div>
</div>

<?php echo local_dominosdashboard_get_ideales_as_js_script(); echo local_dominosdashboard_get_course_tabs_as_js_script(); ?>
<link href="libs/c3.css" rel="stylesheet">
<script src="//d3js.org/d3.v3.min.js" charset="utf-8"></script>
<script src="libs/c3.js"></script>
<script src="dominosdashboard_scripts.js"></script>
<script src="scripts/topdf.js"></script>
<script src="scripts/saveSvgAsPng.js"></script>
<script>
    var indicator;
    var item;
    var serialized_form = "";
    var demark = "";
    var tituloPestana = "";
    document.addEventListener("DOMContentLoaded", function() {
        require(['jquery'], function ($) {
            $('.course-selector').change(function(){obtenerGraficas()});
            tituloPestana = $('#tab-selector').children('option:selected').html();
            $('#tab-selector').change(function(){ tituloPestana = $(this).children('option:selected').html(); obtenerGraficas(); });
            obtenerGraficas();
            obtenerFiltros();
        });
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
        informacion.push({name: 'request_type', value: 'course_list'});
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
            respuesta = JSON.parse(JSON.stringify(data));
            respuesta = respuesta.data;
            dateEnding = Date.now();
            $('#local_dominosdashboard_content').html('<pre>' + JSON.stringify(data, undefined, 2) + '</pre>');
            console.log(`Tiempo de respuesta de API al obtener json para gráficas ${dateEnding - dateBegining} ms`);
            generarGraficasTodosLosCursos('#contenedor_cursos', respuesta, tituloPestana);
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
            keys = Object.keys(data.data);
            div_selector = '#contenedor_filtros';
            crearElementos = esVacio($(div_selector).html());
            for (var index = 0; index < keys.length; index++) {
                clave = keys[index]
                var catalogo = data.data[clave];
                heading_id = "indicatorheading" + clave;
                collapse_id = "collapse_" + clave;
                subfiltro_id = 'subfilter_id_' + clave;
                console.log(clave, catalogo.length);
                if(crearElementos){
                    $(div_selector).append(`
                        <div class="card">
                            <div class="card-header cuerpo-filtro" id="${heading_id}">
                                <h5 class="mb-0">
                                    <input type="checkbox"><button class="btn btn-link collapsed texto-filtro"
                                        data-toggle="collapse" style="color: white;" data-target="#${collapse_id}" aria-expanded="false"
                                        aria-controls="${collapse_id}">
                                        ${clave}
                                    </button>
                                </h5>
                            </div>
                            <div id="${collapse_id}" class="collapse" aria-labelledby="${heading_id}" data-parent="#contenedor_filtros">
                                <div class="card-body subgrupo-filtro" id='${subfiltro_id}'></div>
                            </div>
                        </div>`);                
                // }else{
                }
                subfiltro_id = "#" + subfiltro_id;
                $(subfiltro_id).html('');
                for(var j = 0; j < catalogo.length; j++){
                    var elementoDeCatalogo = catalogo[j];
                    $(subfiltro_id).append(`
                                <label class="subfiltro"><input type="checkbox" name="${clave}[]"
                                class="indicator_option indicator_${clave}\" onclick="obtenerGraficas('${clave}')" 
                                data-indicator=\"${clave}\" value=\"${elementoDeCatalogo}\"
                                >
                                 ${esVacio(elementoDeCatalogo) ? "(Vacío)" : elementoDeCatalogo}</label><br>
                    `);
                }
            }
            dateEnding = Date.now();
            console.log(`Tiempo de respuesta al obtener filtros de API ${dateEnding - dateBegining} ms`);
        })
        .fail(function(error, error2) {
            console.log(error);
            console.log(error2);
        });
    }
    
</script>
<link href="estilos.css" rel="stylesheet">
<?php

echo $OUTPUT->footer();
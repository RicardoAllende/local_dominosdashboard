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
            echo "<option value='{$key}'>{$key} -> {$option}</option>";
        }
        echo "</select><br>";
        echo "<div id='contenedor_filtros'></div>";
        // foreach($indicators as $indicator){
        //     echo "<h3>Indicador: {$indicator} </h3>";
        //     echo "<div id='indicator_section_{$indicator}'></div>";    
        // }
        ?>
        <!-- <input type="hidden" name="request_type" value="course_list"><br><br> -->
    </form>
    <div class="col-sm-9" id="contenido_cursos">
        <div class="">
            <h1 class="text-center ldm_tab_name"></h1>
        </div>
        <div>
            <div class="row" id="contenedor_cursos">
                <div class="col-sm-12 col-xl-12">
                    
                </div>
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
<script>
    var indicator;
    var item;
    var serialized_form = "";
    var demark = "";
    document.addEventListener("DOMContentLoaded", function() {
        require(['jquery'], function ($) {
            $('.course-selector').change(function(){obtenerGraficas()});
            $('#tab-selector').change(function(){ obtenerGraficas(); });
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
        // console.log('Valor de la pestaña', ldm_course_tabs[$('#tab-selector').val()]);
        $('.ldm_tab_name').html(ldm_course_tabs[$('#tab-selector').val()]);
        $.ajax({
            type: "POST",
            url: "services.php",
            data: informacion,
            dataType: "json"
        })
        .done(function(data) {
            listado_cursos = JSON.parse(JSON.stringify(data));
            listado_cursos = listado_cursos.data;
            generarGraficasTodosLosCursos('#contenedor_cursos', listado_cursos);
            // console.log('Listado de cursos', listado_cursos);
            dateEnding = Date.now();
            console.log(`Tiempo de respuesta de API al obtener json para gráficas ${dateEnding - dateBegining} ms`);
            // console.log("Petición correcta");
            // console.log(data);
            $('#local_dominosdashboard_content').html('<pre>' + JSON.stringify(data, undefined, 2) + '</pre>');
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
            console.log(data);
            keys = Object.keys(data.data);
            // div_selector = '#indicator_section_' + clave;
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
                // console.log($(subfiltro_id));
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
                // if(crearElementos){
                //     $(div_selector).append(`
                //                 `);
                // }
                // continue;
                // for(var j = 0; j < catalogo.length; j++){
                //     var elementoDeCatalogo = catalogo[j];
                //     if(elementoDeCatalogo == ''){
                //         $('#indicator_section_' + clave).append(`<label><input type=\"checkbox\" name=\"${clave}[]\" 
                //         class=\"indicator_option indicator_${clave}\" onclick="obtenerGraficas('${clave}')" data-indicator=\"${clave}\" value=\"${elementoDeCatalogo}\">(vacío)</label><br>`);
                //     }else{
                //         $('#indicator_section_' + clave).append(`<label><input type=\"checkbox\" name=\"${clave}[]\" 
                //         class=\"indicator_option indicator_${clave}\" onclick="obtenerGraficas('${clave}')" data-indicator=\"${clave}\" value=\"${elementoDeCatalogo}\">${elementoDeCatalogo}</label><br>`);
                //     }
                // }
            }
            dateEnding = Date.now();
            console.log(`Tiempo de respuesta al obtener filtros de API ${dateEnding - dateBegining} ms`);
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

    function generarGraficasTodosLosCursos(_bindto, cursos) {
        // console.log('La información es: ', cursos);
        if(Array.isArray(cursos)){
            var aprobados = Array();
            var nombres = Array();
            var _ideal_cobertura = Array();
            aprobados.push("Porcentaje de aprobación del curso");
            _ideal_cobertura.push('Ideal de cobertura');
            $(_bindto).html('');
            for (var i = 0; i < cursos.length; i++) {
                _ideal_cobertura.push(ideal_cobertura);
                var curso = cursos[i];
                aprobados.push(curso.percentage);
                crearTarjetaParaGrafica(_bindto, curso);
            }
            crearGraficaComparativaVariosCursos(_bindto, [aprobados, _ideal_cobertura], cursos);
            return;
            
        }
    }

    // function generarGraficaPuntos()

    function crearTarjetaParaGrafica(div, curso){
        id_para_Grafica = "chart_" + curso.id;
        $(div).append(`<div class="col-sm-12 col-xl-6">
                    <div class="card bg-gray border-0 m-2" id="">

                        <div class="card-group">
                            <div class="card border-0 m-2">
                                <div class="card-body text-center">
                                    <p class="card-text text-primary">Aprobados</p>
                                    <p class="card-text text-primary">${curso.approved_users} (${curso.percentage} %)</p>
                                </div>
                            </div>
                            <div class="card border-0 m-2">
                                <div class="card-body text-center">
                                    <p class="card-text text-warning">No Aprobados</p>
                                    <p class="card-text text-warning">${curso.not_approved_users}</p>
                                </div>
                            </div>
                            <div class="card border-0 m-2">
                                <div class="card-body text-center">
                                    <p class="card-text text-success">Total de usuarios inscritos</p>
                                    <p class="card-text text-success">${curso.enrolled_users}</p>
                                </div>
                            </div>

                        </div>
                        <div class="chart_ bg-white m-2" id="${id_para_Grafica}"></div>
                        <div class="align-items-end">
                            <div class="fincard text-center">
                                <a href="detalle_curso?id=${curso.id}">${curso.title}</a>
                            </div>
                        </div>
                    </div>
                </div>`);
        crearGraficaDeBarras('#' + id_para_Grafica, curso);
    }

    function crearGraficaComparativaVariosCursos(_bindto, info_grafica, cursos){
        div_id = "chart__" + $('#tab-selector').val();
        $(_bindto).prepend(`
                    <div class="card bg-faded border-0 m-2" id="">
                        <div class="bg-white m-2" id="${div_id}"></div>
                        <div class="align-items-end">
                            <div class="fincard text-center">
                                <a href="#">Comparativa</a>
                            </div>
                        </div>
                    </div>`);
        var chartz = c3.generate({
            data: {
                columns: info_grafica,
                type: ''
            },
            bindto: '#' + div_id,
            tooltip: {
                format: {
                    title: function (d) { return cursos[d].title; },
                    value: function (value, ratio, id) {
                        return value + " %";
                    }
                }
            }
        });
    }

    function crearGraficaDeBarras(_bindto, curso){
        var nombre_columnas = ["Inscritos", "Aprobados", "No iniciaron el curso"];
        var chartc = c3.generate({
                data: {
                    columns: [
                        ['Inscritos', curso.enrolled_users],
                        ['Aprobados', curso.approved_users],
                        ['No iniciaron el curso', curso.not_viewed]
                    ],
                    type: 'bar'
                },
                bindto: _bindto,
                tooltip: {
                    format: {
                        title: function (d) {
                            if(nombre_columnas[d] !== undefined){
                                return nombre_columnas[d];
                            }else{
                                return "_";
                            }
                        },
                    }
                }
            });
    }


</script>
<link href="estilos.css" rel="stylesheet">
<?php

echo $OUTPUT->footer();
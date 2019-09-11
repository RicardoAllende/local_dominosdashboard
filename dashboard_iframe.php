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
require_capability('local/dominosdashboard:view', $context_system);
require_once(__DIR__ . '/lib.php');
global $DB;
$PAGE->set_url($CFG->wwwroot . "/local/dominosdashboard/inner.php");
$PAGE->set_context($context_system);
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'local_dominosdashboard'));


$tabOptions = local_dominosdashboard_get_course_tabs();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css" integrity="sha384-2hfp1SzUoho7/TsGGGDaFdsuuDL0LX2hnUp6VkX3CUQ2K4K+xjboZdsXyp4oUHZj" crossorigin="anonymous">
    <link href="estilos.css" rel="stylesheet">
</head>
<body>
    
    <div class="row" style="max-width: 100%;">
        <form id="filter_form" method="post" action="services.php" class='col-sm-3'>
            <span class="btn btn-success" onclick="quitarFiltros()">Quitar todos los filtros</span><br><br>
            <div id="contenedor_fechas">
                <label for="fecha_inicial">Desde <input type="date" class="form-control" name="fecha_inicial" id="fecha_inicial"></label> 
                <label for="fecha_final">Hasta <input type="date" class="form-control" name="fecha_final" id="fecha_final"></label>
            </div>
            <div id='contenedor_filtros'></div>
        </form>
        <div class="col-sm-9" id="contenido_cursos">
            <div id="navbarSupportedContent">
                <ul class="nav justify-content-center nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active dtag" id="home-tab" data-toggle="tab" href="#home" role="tab"
                            aria-controls="home" onclick="cambiarpestana(1)" aria-selected="true">Programas de entrenamiento</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                            aria-controls="profile" onclick="cambiarpestana(2)" aria-selected="false">Lanzamientos y campañas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                            aria-controls="contact" onclick="cambiarpestana(3)" aria-selected="false">Cruce de indicadores</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content row" id="myTabContent">
                <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="" id="ldm_tab_1"></div>
                    <!-- <div class="" id="contenedor_cursos"></div> -->
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="" id="ldm_tab_2"></div>
                </div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                    <div class="" id="ldm_tab_3"></div>
                </div>
            </div>
        </div>
        <div id="loader"></div>
        <div class="col-sm-12" id="local_dominosdashboard_content"></div>
        <!-- <div class="col-sm-12" style="padding-top: 50px;" id="local_dominosdashboard_request"></div> -->
    </div>
    
    <?php echo local_dominosdashboard_get_ideales_as_js_script(); echo local_dominosdashboard_get_course_tabs_as_js_script(); ?>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script> -->
    <script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/js/bootstrap.min.js" integrity="sha384-VjEeINv9OSwtWFLAtmc4JCtEJXXBub00gtSnszmspDLCtC0I4z4nqz7rEFbIZLLU" crossorigin="anonymous"></script>
    <link href="libs/c3.css" rel="stylesheet">
    <script src="//d3js.org/d3.v3.min.js" charset="utf-8"></script>
    <script src="libs/c3.js"></script>
    <script src="dominosdashboard_scripts.js"></script>
    <script>
        var isCourseLoading = false;
        var isFilterLoading = false;
        var trabajoPendiente = false;
        var currentTab = 1;
        var indicator;
        var item;
        var serialized_form = "";
        var demark = "";
        var tituloPestana = "";
        var tabsCursos = [false, false, false];
        function cambiarpestana(id){
            if(id != currentTab){
                hidePage("ldm_tab_" + id);
                currentTab = id;
                tituloPestana = pestanas[id];
                setTimeout(function() {
                    obtenerInformacion();
                }, 500);
            }
        }
        pestanas = [
            '',
            'Programas de entrenamiento',
            'Lanzamientos y campañas',
            'Cruce de indicadores'
        ]
        document.addEventListener("DOMContentLoaded", function() {
                $('.course-selector').change(function(){obtenerInformacion()});
                tituloPestana = pestanas[1];
                // tituloPestana = $('#tab-selector').children('option:selected').html();
                // $('#tab-selector').change(function(){ tituloPestana = $(this).children('option:selected').html(); obtenerInformacion(); });
                obtenerInformacion();
                obtenerFiltros();
        });
        var dateBegining;
        var dateEnding;
        function quitarFiltros(){
            peticionFiltros({
                request_type: 'user_catalogues'
            });
        }
        function rehacerPeticion(){
            trabajoPendiente = true;
            setTimeout(function() {
                
            }, 2000);
        }
        // function reObtenerInformacion(){

        // }
        function obtenerInformacion(indicator){
            if(isCourseLoading){
                console.log('Cargando contenido de cursos, no debe procesar más peticiones por el momento');
                return;
            }
            isCourseLoading = !isCourseLoading;
            console.log("Obteniendo gráficas");
            informacion = $('#filter_form').serializeArray();
            informacion.push({name: 'request_type', value: 'course_list'});
            informacion.push({name: 'type', value: currentTab});
            dateBegining = Date.now();
            // $('#local_dominosdashboard_content').html('Cargando la información');
            $.ajax({
                type: "POST",
                url: "services.php",
                data: informacion,
                dataType: "json"
            })
            .done(function(data) {
                isCourseLoading = false;
                console.log('Data obtenida', data);
                respuesta = JSON.parse(JSON.stringify(data));
                respuesta = respuesta.data;
                console.log('Imprimiendo la respuesta', respuesta);
                dateEnding = Date.now();
                // $('#local_dominosdashboard_content').html('<pre>' + JSON.stringify(data, undefined, 2) + '</pre>');
                console.log(`Tiempo de respuesta de API al obtener json para gráficas ${dateEnding - dateBegining} ms`);
                render_div = "#ldm_tab_" + currentTab;
                var cosa = generarGraficasTodosLosCursos(render_div, respuesta, tituloPestana);
                setTimeout(function(){
                    console.log(cosa);
                    if(cosa == true){
                        showPage("ldm_tab_" + currentTab);
                    }
                },1000)
            })
            .fail(function(error, error2) {
                isCourseLoading = false;
                console.log(error);
                console.log(error2);
            });
            if(indicator !== undefined){
                obtenerFiltros(indicator);
            }
        }
        
    </script>
</body>
</html>
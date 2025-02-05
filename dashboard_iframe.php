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
$PAGE->set_url($CFG->wwwroot . "/local/dominosdashboard/inner.php");
$PAGE->set_context($context_system);
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'local_dominosdashboard'));


$tabOptions = local_dominosdashboard_get_course_tabs();

$firstKpiDate = local_dominosdashboard_get_first_kpi_date();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css" integrity="sha384-2hfp1SzUoho7/TsGGGDaFdsuuDL0LX2hnUp6VkX3CUQ2K4K+xjboZdsXyp4oUHZj" crossorigin="anonymous">
    <link rel="stylesheet" href="css/jquery.loadingModal.css">
    <link href="estilos.css" rel="stylesheet">
</head>
<body>
    
    <div class="row" style="max-width: 100%; min-height: 300px;">
        <form id="filter_form" name="filter_form" method="post" style="display:none" action="imprimir.php" class='col-sm-3'>
            <!-- <span class="btn btn-success" onclick="quitarFiltros()">Quitar todos los filtros</span><br><br> -->
            <p class="btn btn-primary" id="exportar_a_excel_boton" onclick="exportar_a_excel();">Exportar cursos a excel</p>
            <div id="contenedor_fechas">
                <label for="fecha_inicial">Desde <input type="date" onchange="obtenerInformacion(),loaderFiltro()" class="form-control" name="fecha_inicial" id="fecha_inicial"></label> 
                <label for="fecha_final">Hasta <input type="date" onchange="obtenerInformacion(),loaderFiltro()" class="form-control" name="fecha_final" id="fecha_final"></label>
            </div>
            <div id="contenedor_fechas_kpis">
                <label for="fecha_inicial_kpi">KPI desde <input type="date" onchange="obtenerInformacion(),loaderFiltro()" class="form-control" name="fecha_inicial_kpi" id="fecha_inicial_kpi" value="<?php echo $firstKpiDate; ?>"></label> 
                <label for="fecha_final_kpi">KPI hasta <input type="date" onchange="obtenerInformacion(),loaderFiltro()" class="form-control" name="fecha_final_kpi" id="fecha_final_kpi" value="<?php echo date('Y-m-d'); ?>"></label>
            </div>
            <input type="hidden" name="report_type" id="report_type" value="course_list">
            <div class="col-sm-11" id='contenedor_filtros' style="text-align: center;"></div>
        </form>
        <div class="col-sm-9" id="contenido_cursos">
            <div id="navbarSupportedContent">
                <ul class="nav justify-content-center nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active dtag" id="home-tab" data-toggle="tab" href="#home" role="tab"
                            aria-controls="home" onclick="cambiarpestana(1)" aria-selected="true">Entrenamiento nacional</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                            aria-controls="profile" onclick="cambiarpestana(2)" aria-selected="false">Detalles entrenamiento</a>
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
                    <div class="col-sm-12" id="seccion_a">                                           
                        <div id="graficas_seccion_a"></div>                        
                    </div>

                    <div class="col-sm-12" id="seccion_b">                       
                        <div id="graficas_seccion_b"></div>
                    </div>

                    <div class="col-sm-12" id="seccion_c">
                        <!-- <div style="text-align: center;">
                        <h3 class="titulog">Ruta Dominos</h3>
                        </div> -->
                        <div id="graficas_seccion_c"></div>
                    </div>

                    <div class="col-sm-12" id="seccion_d">                                          
                        <div id="graficas_seccion_d"></div>                        
                    </div>

                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="" id="ldm_tab_2"></div>
                    <div class="col-sm-12" id="seccion_detalles_entrenamiento">                        
                        <div id="graficas_seccion_detalles_entrenamiento"></div>
                        <div class="col-sm-12" id="ldm_comparativas"></div>
                    </div>
                </div>

                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                    <div class="" id="ldm_tab_3"></div>
                    <div class="col-sm-12" id="kpi_comparative">                        
                        <div id="contenedor_kpi"></div>
                    </div>
                </div>
            </div>
            
        </div>
        
        <div id="loader"></div>
        <!-- <div class="col-sm-12" id="local_dominosdashboard_content"></div> -->
        
        
        <!-- <div class="col-sm-12" style="padding-top: 50px;" id="local_dominosdashboard_request"></div> -->
    </div>
    
    <?php echo local_dominosdashboard_get_course_tabs_as_js_script(); ?>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script> -->
    <script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/js/bootstrap.min.js" integrity="sha384-VjEeINv9OSwtWFLAtmc4JCtEJXXBub00gtSnszmspDLCtC0I4z4nqz7rEFbIZLLU" crossorigin="anonymous"></script>
    <link href="libs/c3.css" rel="stylesheet">
    <script src="//d3js.org/d3.v3.min.js" charset="utf-8"></script>
    <script src="libs/c3.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="js/jquery.loadingModal.js"></script>
    
    <script>
        var muestraComparativas = false;
        var mostrarEnlaces = true;
        var isCourseLoading = false;
        var isFilterLoading = false;
        var trabajoPendiente = false;
        var currentTab = 1;
        var indicator;
        var item;
        var tituloPestana = "";
        var tabsCursos = [false, false, false];
        verificarVistaFiltros(currentTab);
        function verificarVistaFiltros(tab){
            ocultarFiltros = false;
            if(typeof tab === 'number'){ // dashboard_iframe
                if(tab === 1){ // Entrenamiento nacional
                    ocultarFiltros = true;
                // }else{
                }
            // }else{ // detalle_curso_iframe
            }
            if(ocultarFiltros){
                $('#filter_form').hide();
                $('#contenido_cursos, #contenido_dashboard').removeClass('row col-sm-9 col-sm-12 sin_filtro');
                $('#contenido_cursos, #contenido_dashboard').addClass('row col-sm-12 sin_filtro');
                // return;
            }else{
                $('#filter_form').show();
                $('#contenido_cursos, #contenido_dashboard').removeClass('row col-sm-9 col-sm-12 sin_filtro');
                $('#contenido_cursos, #contenido_dashboard').addClass('row col-sm-9');
            }
            $("#cardpuestos").hide();
            $("#contenedor_fechas_kpis").hide();

            if(tab == 2){
                $("#exportar_a_excel_boton").show();
                $("#cardpuestos").show();
            }
            if(tab == 3){
                $("#contenedor_fechas_kpis").show();
                $("#exportar_a_excel_boton").hide(); // Ocultar exportado de cursos en la última pestaña
                $("#cardpuestos").hide();
            }
        }
        //imprimirComparativaFiltrosDeCurso('#comparativa_region',informacion_del_curso.data.filter_comparative);
        function cambiarpestana(id){
            if(id != currentTab){
                hidePage("ldm_tab_" + id);
                currentTab = id;
                verificarVistaFiltros(currentTab);
                tituloPestana = pestanas[id];
                setTimeout(function() {
                    obtenerInformacion();
                }, 500);
            }
            
        }
        pestanas = [
            '',
            'Entrenamiento nacional',
            'Detalles entrenamiento',
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
            obtenerInformacion();
        }
        // function rehacerPeticion(){
        //     trabajoPendiente = true;
        //     setTimeout(function() {                
        //     }, 2000);
        // }
        // function reObtenerInformacion(){

        // }
        function obtenerInformacion(indicator){
            
            // Limpiando todas las secciones
            $('#graficas_seccion_a').empty();
            $('#graficas_seccion_b').empty();
            $('#graficas_seccion_c').empty();
            $('#graficas_seccion_d').empty();
            $('#graficas_seccion_detalles_entrenamiento').empty();
            $('#contenedor_kpi').empty();

            if(currentTab == 1){
                informacion = Array();
            }else{
                informacion = $('#filter_form').serializeArray();
            }
            informacion.push({name: 'request_type', value: 'course_list'});
            informacion.push({name: 'type', value: currentTab});
            if(currentTab === 3){
                if(esVacio(indicator)){
                    informacion.push({ name: 'selected_filter', value: 'regiones' });
                }else{
                    informacion.push({ name: 'selected_filter', value: indicator });
                }
            }
            dateBegining_courses = Date.now();
            $('#ldm_comparativas').html(''); // Se eliminan las gráficas comparativas anteriormente creadas
            // $('#local_dominosdashboard_content').html('Cargando la información');
            $.ajax({
                type: "POST",
                url: "services.php",
                data: informacion,
                dataType: "json"
            })
            .done(function(data) {
                isCourseLoading = false;
                respuestaArr = JSON.parse(JSON.stringify(data));
                respuesta = respuestaArr.data;
                console.log(`Resultado de la petición course_list pestaña ${currentTab}: ` , respuesta);
                dateEnding_courses = Date.now();
                // $('#local_dominosdashboard_content').html('<pre>' + JSON.stringify(data, undefined, 2) + '</pre>');
                console.log(`Tiempo de respuesta de API al obtener json para listado de cursos ${dateEnding_courses - dateBegining_courses} ms`);
                if(currentTab == 1){
                    $('#graficas_seccion_a').empty();
                    $('#graficas_seccion_b').empty();
                    $('#graficas_seccion_c').empty();
                    $('#graficas_seccion_d').empty();
                    seccion_a_imprimirGraficaComparativaCursos('#graficas_seccion_a',respuesta);
                    seccion_b_imprimirGraficaComparativaCursos('#graficas_seccion_b',respuesta);
                    seccion_c_imprimirGraficaComparativaCursos('#graficas_seccion_c',respuesta);
                    seccion_d_imprimirGraficaComparativaCursos('#graficas_seccion_d',respuesta);
                }
                if(currentTab == 2){
                    $('#graficas_seccion_detalles_entrenamiento').empty();
                    detalles_entrenamiento('#graficas_seccion_detalles_entrenamiento',respuesta);
                }
                if(currentTab == 3){
                    $('#contenedor_kpi').empty();
                    kpi_comparative('#contenedor_kpi', respuesta.result); // Cambiado nombre a kpi_comparative por ambigüedad (funciona para cualquier filtro)
                }
                ocultarLoader(); 
                
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
    <script src="dominosdashboard_scripts.js"></script>
    <script>        
        //imprimirComparativaFiltrosDeCurso('#graficas_seccion_c', informacion_del_curso.data.filter_comparative);            
        //seccion_a_imprimirGraficaComparativaCursos('#graficas_seccion_a');
        //seccion_b_imprimirGraficaComparativaCursos('#graficas_seccion_b');
        
        //seccion_d_imprimirGraficaComparativaCursos('#graficas_seccion_d');
       // kpi_comparative('#contenedor_kpi');
    </script>
    
</body>
</html>
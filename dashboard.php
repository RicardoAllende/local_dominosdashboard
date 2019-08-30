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
$PAGE->set_url($CFG->wwwroot . "/local/dominosdashboard/dashboard.php");
$PAGE->set_context($context_system);
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'local_dominosdashboard'));

echo $OUTPUT->header();
// echo "<script> var indicadores = '" . DOMINOSDASHBOARD_INDICATORS . "'</script>";

$courses = local_dominosdashboard_get_courses();

$indicators = local_dominosdashboard_get_indicators();
?>
<div class="row">
    <form id="filter_form" method="post" action="services.php" class='col-sm-4'>
        <span class="btn btn-success" onclick="quitarFiltros()">Quitar todos los filtros</span><br>
        <span class="btn btn-info" onclick="obtenerGraficas()">Volver a simular obtención de gráficas</span><br><br>
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
    </form>
    <div class="col-sm-8" id="local_dominosdashboard_content"></div>    
    <div class="col-sm-12" style="padding-top: 50px;" id="local_dominosdashboard_request"></div>
    <div class="col-sm-12" id="data_ob"></div>
    <div class="col-sm-12" id="data_card"></div>
    <div class="col-sm-12" id="data_card2"></div>
    
    <div class="titulog col-sm-12 dominosdashboard-ranking">
        <h1 style="text-align: center;">Ranking de actividades</h1>
    </div>
    <div class="col-sm-6 dominosdashboard-ranking">
        <table frame="void" rules="rows" style="width:100%">
            <tr class="rankingt">
                <th>#</th>
                <th>Actividades</th>
                <th>Aprobados</th>
            </tr>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Actividad 1</td>
                    <td>
                        <div class="progress">
                            <div class="progress-bar" style="width:90%">90</div>
                        </div>
                    </td>
                </tr>
            </tbody>
            <!-- <tr>
                <td>2</td>
                <td>Actividad 2</td>
                <td>
                    <div class="progress">
                        <div class="progress-bar" style="width:70%">70</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>3</td>
                <td>Actividad 3</td>
                <td>
                    <div class="progress">
                        <div class="progress-bar" style="width:65%">65</div>
                    </div>
                </td>
            </tr> -->
        </table>
    </div>

    <div class="col-sm-6 dominosdashboard-ranking">
        <table frame="void" rules="rows" style="width:100%">
            <tr class="rankingt">
                <th>#</th>
                <th>Actividades</th>
                <th>No Aprobados</th>
            </tr>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Actividad 1</td>
                    <td>
                        <div class="progress">
                            <div class="progress-bar bg-warning" style="width:30%">30</div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
<?php echo local_dominosdashboard_get_ideales_as_js_script(); ?>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<link href="libs/c3.css" rel="stylesheet">
<script src="//d3js.org/d3.v3.min.js" charset="utf-8"></script>
<script src="libs/c3.js"></script>
<link href="estilos.css" rel="stylesheet">

<script>
    var indicator;
    var item;
    var serialized_form = "";
    var demark = "";
    document.addEventListener("DOMContentLoaded", function() {
        try{
            // $('.dominosdashboard-ranking').hide();
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
    var _kpi;
    var _kpis;
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
            console.log("Petición correcta");
            $('#local_dominosdashboard_content').html('<pre>' + JSON.stringify(data, undefined, 2) + '</pre>');
            // return;
            informacion_del_curso = JSON.parse(JSON.stringify(data));
            _kpis= informacion_del_curso.data.kpi;
            for (var index = 0; index < informacion_del_curso.data.kpi.length; index++) {
                _kpi = informacion_del_curso.data.kpi[index];
                /*
                 DEFINE("KPI_OPS", 1);
                 DEFINE("KPI_HISTORICO", 2); // quejas
                 DEFINE("KPI_SCORCARD", 3); // % rotación
                */
                switch (_kpi.kpi) {
                    case 1: // OPS 
                        imprimirCards(_kpi);
                        addChartc(_kpi);
                        imprimirCards2(_kpi);
                        addChartc2(_kpi);
                        break;
                    case 2: //Reporte de Casos Histórico por tiendas
                        break;
                    case 3:
                        
                        break;
                    default:
                        break;
                }
                console.log(_kpi);
            }


            dateEnding = Date.now();
            console.log(`Tiempo de respuesta de API al obtener json para gráficas ${dateEnding - dateBegining} ms`);
            // console.log("Petición correcta");
            // console.log(data);
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
        info = $('#filter_form').serializeArray();
        dateBegining = Date.now();
        info.push({name: 'request_type', value: 'user_catalogues'});
        if(indicator != undefined){
            info.push({name: 'selected_filter', value: indicator});
        }
        peticionFiltros(info);
    }
    
    
    //--------------------------------------------------------------------------------------------------------------------------KPIS
    //---------------------------OPS MÉXICO W
    function imprimirCards(kpi){
        //console.log("entra imprimir");
        // myJSON = JSON.parse(JSON.stringify(data));
        // console.log(myJSON);        
        document.getElementById("data_card").innerHTML = "<div class='col-sm-12 col-xl-6'>"+
                                "<div class='card bg-gray border-0 m-2'>"+


                                       "<div class='card-group'>"+
                                          "<div class='card border-0 m-2'>"+
                                            "<div class='card-body'>"+
                                              "<p class='card-text text-primary text-center'>Aprobados</p>"+
                                              "<p class='card-text text-primary text-center' id='apro'></p>"+
                                            "</div>"+
                                          "</div>"+
                                          "<div class='card border-0 m-2'>"+
                                            "<div class='card-body text-center'>"+
                                              "<p class='card-text text-warning text-center'>No Aprobados</p>"+
                                              "<p class='card-text text-warning text-center' id='no_apro'></p>"+
                                            "</div>"+
                                          "</div>"+
                                          "<div class='card border-0 m-2'>"+
                                            "<div class='card-body text-center'>"+
                                              "<p class='card-text text-success text-center'>Destacado</p>"+
                                              "<p class='card-text text-success text-center' id='des'></p>"+
                                            "</div>"+
                                          "</div>"+

                                        "</div>"+
                                    "<div class='bg-white m-2' id='chart'></div>"+
                                   
                                    "<div class='align-items-end'>"+
                                        
                                        "<div class='fincard text-center'>"+
                                            "<a href='Grafica.html' id='titulo_grafica'></a>"+
                                        "</div>"+
                                    "</div>"+
                                "</div>"+
                            "</div>";
        $('#apro').html(kpi.value["Aprobado"]);//Aprobados
        $('#no_apro').html(kpi.value["No aprobado"]);//No Aprobados
        $('#des').html(_kpis[0].value["Destacado"]);//No Aprobados
        
        
        //---------------------------SUMA PARA SACAR EL TOTAL DE USUARIOS    
        /*var a = kpi.value["Aprobado"];
        var b = kpi.value["No aprobado"];    
        var c = parseInt(a) + parseInt(b);
        document.getElementById("tusuario").value = c;*/
        //$('#chart').html(myJSON.status);//Chart
        //$('#tusuario').html(myJSON.data["enrolled_users"]);//Total de usuarios
        $('#titulo_grafica').html(kpi.kpi_name);//Titulo grafica
    }     
    
    function addChartc(kpi){
        // myJSON = JSON.parse(JSON.stringify(data));
        //console.log(myJSON);
        var chartc = c3.generate({
            data: {
                columns: [
                    ['Aprobado', kpi.value["Aprobado"]],
                    ['No Aprobado', kpi.value["No aprobado"]],
                    ['Destacado', kpi.value["Destacado"]]
                ],
                type: ''
            },
            bindto: "#chart",
            tooltip: {
            format: {
                title: function (d) { return 'Curso '; },
                value: function (value, ratio, id) {
                    var format = id === 'data1' ? d3.format(',') : d3.format('');
                    return format(value);
                }
            }
        }
        });
    }
    
//-----------------------------------Reporte de Casos Histórico por tiendas
function imprimirCards2(kpi){
        //console.log("entra imprimir");
        // myJSON = JSON.parse(JSON.stringify(data));
        // console.log(myJSON);        
        document.getElementById("data_card2").innerHTML = "<div class='col-sm-12 col-xl-6'>"+
                                "<div class='card bg-gray border-0 m-2'>"+


                                       "<div class='card-group'>"+
                                          "<div class='card border-0 m-2'>"+
                                            "<div class='card-body'>"+
                                              "<p class='card-text text-primary text-center'>Aprobados</p>"+
                                              "<p class='card-text text-primary text-center' id='apro2'></p>"+
                                            "</div>"+
                                          "</div>"+
                                          "<div class='card border-0 m-2'>"+
                                            "<div class='card-body text-center'>"+
                                              "<p class='card-text text-warning text-center'>No Aprobados</p>"+
                                              "<p class='card-text text-warning text-center' id='no_apro2'></p>"+
                                            "</div>"+
                                          "</div>"+
                                          "<div class='card border-0 m-2'>"+
                                            "<div class='card-body text-center'>"+
                                              "<p class='card-text text-success text-center'>Total de usuarios</p>"+
                                              "<p class='card-text text-success text-center' id='tusuario2'></p>"+
                                            "</div>"+
                                          "</div>"+

                                        "</div>"+
                                    "<div class='bg-white m-2' id='chart2'></div>"+
                                   
                                    "<div class='align-items-end'>"+
                                        
                                        "<div class='fincard text-center'>"+
                                            "<a href='Grafica.html' id='titulo_grafica2'></a>"+
                                        "</div>"+
                                    "</div>"+
                                "</div>"+
                            "</div>";
        $('#apro2').html(kpi.value["Aprobado"]);//Aprobados
        $('#no_apro2').html(kpi.value["No aprobado"]);//No Aprobados
        
        //---------------------------SUMA PARA SACAR EL TOTAL DE USUARIOS    
        var a = kpi.value["Aprobado"];
        var b = kpi.value["No aprobado"];    
        var c = parseInt(a) + parseInt(b);
        document.getElementById("tusuario2").value = c;
        //$('#chart').html(myJSON.status);//Chart
        //$('#tusuario').html(myJSON.data["enrolled_users"]);//Total de usuarios
        $('#titulo_grafica2').html(_kpis[1].kpi_name);//Titulo grafica
    }     
    
    function addChartc2(kpi){
        // myJSON = JSON.parse(JSON.stringify(data));
        //console.log(myJSON);
        var chartc = c3.generate({
            data: {
                columns: [
                    ['Aprobado', kpi.value["Aprobado"]],
                    ['No Aprobado', kpi.value["No aprobado"]],
                    ['Destacado', kpi.value["Destacado"]],
                ],
                type: 'bar',
            },
            bindto: "#chart2",
            tooltip: {
                format: {
                    title: function (d) { return 'Curso '; },
                    value: function (value, ratio, id) {
                        var format = id === 'data1' ? d3.format(',') : d3.format('');
                        return format(value);
                    }

                }
            }
        });
    }

function imprimirRanking(data){

}
    
</script>



<?php
// Contenido del dashboard
echo $OUTPUT->footer();
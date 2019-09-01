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
    <div class="col-sm-12" id="data_card3"></div>
    

    <div class="titulog col-sm-12 dominosdashboard-ranking" id="dominosdashboard-ranking-title">
        <h1 style="text-align: center;">Ranking de actividades</h1>
    </div>
    <div class="col-sm-6 dominosdashboard-ranking" id="dominosdashboard-ranking-top">
        <table frame="void" rules="rows" style="width:100%">
            <tr class="rankingt">
                <th>#</th>
                <th>Actividades</th>
                <th>Aprobados</th>
            </tr>
            <tbody id="tbody-ranking-top"></tbody>
        </table>
    </div>

    <div class="col-sm-6 dominosdashboard-ranking" id="dominosdashboard-ranking-bottom">
        <table frame="void" rules="rows" style="width:100%">
            <tr class="rankingt">
                <th>#</th>
                <th>Actividades</th>
                <th>No Aprobados</th>
            </tr>
            <tbody id="tbody-ranking-bottom"></tbody>
        </table>
    </div>

</div>
<?php echo local_dominosdashboard_get_ideales_as_js_script(); ?>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<link href="libs/c3.css" rel="stylesheet">
<script src="//d3js.org/d3.v3.min.js" charset="utf-8"></script>
<script src="libs/c3.js"></script>
<link href="estilos.css" rel="stylesheet">
<script src="dominosdashboard_scripts.js"></script>

<script>
    var indicator;
    var item;
    var serialized_form = "";
    var demark = "";
    document.addEventListener("DOMContentLoaded", function () {
        try {
            $('.dominosdashboard-ranking').hide();
            require(['jquery'], function ($) {
                $('.course-selector').change(function () { obtenerGraficas() });
                obtenerGraficas();
                obtenerFiltros();
            });
        } catch (error) {
            console.log(error);
        }
    });
    var dateBegining;
    var dateEnding;
    function quitarFiltros() {
        peticionFiltros({
            request_type: 'user_catalogues'
        });
    }
    var _kpi;
    var _kpis;
    function obtenerGraficas(indicator) {
        // console.log("Obteniendo gráficas");
        informacion = $('#filter_form').serializeArray();
        informacion.push({ name: 'request_type', value: 'course_completion' });
        $('#local_dominosdashboard_request').html("<br><br>La petición enviada es: <br>" + $('#filter_form').serialize());
        dateBegining = Date.now();
        $('#local_dominosdashboard_content').html('Cargando la información');
        $.ajax({
            type: "POST",
            url: "services.php",
            data: informacion,
            dataType: "json"
        })
            .done(function (data) {
                // console.log("Petición correcta");
                $('#local_dominosdashboard_content').html('<pre>' + JSON.stringify(data, undefined, 2) + '</pre>');
                // return;
                informacion_del_curso = JSON.parse(JSON.stringify(data));
                _kpis = informacion_del_curso.data.kpi;
                for (var index = 0; index < informacion_del_curso.data.kpi.length; index++) {
                    _kpi = informacion_del_curso.data.kpi[index];
                    console.log('Id del kpi', _kpi.kpi);
                    console.log('Valor del kpi ' + _kpi.kpi_name, _kpi.value);
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
                            imprimirCards3(_kpi);
                            addChartc3(_kpi);
                            break;
                        case 3:

                            break;
                        default:
                            break;
                    }
                    // console.log(_kpi);
                }


                imprimirRanking(informacion_del_curso.data);
                dateEnding = Date.now();
                console.log(`Tiempo de respuesta de API al obtener json para gráficas ${dateEnding - dateBegining} ms`);
                // console.log("Petición correcta");
                // console.log(data);
            })
            .fail(function (error, error2) {
                console.log(error);
                console.log(error2);
            });
        if (indicator !== undefined) {
            obtenerFiltros(indicator);
        }
    }
    function peticionFiltros(info) {
        $.ajax({
            type: "POST",
            url: "services.php",
            data: info,
            dataType: "json"
        })
            .done(function (data) {
                dateEnding = Date.now();
                console.log(`Tiempo de respuesta al obtener filtros de API ${dateEnding - dateBegining} ms`);
                console.log(data);
                keys = Object.keys(data.data);
                for (var index = 0; index < keys.length; index++) {
                    clave = keys[index]
                    var catalogo = data.data[clave];
                    // console.log(clave, catalogo.length);
                    $('#indicator_section_' + clave).html('');
                    for (var j = 0; j < catalogo.length; j++) {
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
            .fail(function (error, error2) {
                console.log(error);
                console.log(error2);
            });
    }

    function obtenerFiltros(indicator) {
        // console.log("Obteniendo filtros");
        info = $('#filter_form').serializeArray();
        dateBegining = Date.now();
        info.push({ name: 'request_type', value: 'user_catalogues' });
        if (indicator != undefined) {
            info.push({ name: 'selected_filter', value: indicator });
        }
        peticionFiltros(info);
    }


    //----------------------------------------------------KPIS---------------------------------------------------------------------------------
    //---------------------------OPS MÉXICO W
    function imprimirCards(kpi) {
        //console.log("entra imprimir");
        // myJSON = JSON.parse(JSON.stringify(data));
        // console.log(myJSON);        
                document.getElementById("data_card").innerHTML = "<div class='col-sm-12 col-xl-6'>" +
                                "<div class='card bg-gray border-0 m-2'>" +


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
        $('#des').html(kpi.value["Destacado"]);//Destacados

        
        //$('#chart').html(myJSON.status);//Chart
        //$('#tusuario').html(myJSON.data["enrolled_users"]);//Total de usuarios
        $('#titulo_grafica').html(kpi.kpi_name);//Titulo grafica
    }

    function addChartc(kpi) {
        // myJSON = JSON.parse(JSON.stringify(data));
        //console.log(myJSON);
        if(esVacio(kpi.value)){
            return false;
        }
        var chartc = c3.generate({
            data: {
                columns: [
                    ['Aprobado', kpi.value["Aprobado"]],
                    ['No Aprobado', kpi.value["No aprobado"]],
                    ['Destacado', kpi.value["Destacado"]]
                ],
                type: 'bar'
            },
            bindto: "#chart",
            tooltip: {
                format: {
                    title: function (d) { return 'KPI '; },
                    value: function (value, ratio, id) {
                        var format = id === 'data1' ? d3.format(',') : d3.format('');
                        return format(value);
                    }
                }
            }
        });
    }

    //-----------------------------------
    function imprimirCards2(kpi) {
        //console.log("entra imprimir2");
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
                                              "<p class='card-text text-warning text-center' id='tusuario2'></p>"+
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
        console.log(c);
        //document.getElementById("tusuario2").value = "Johnny Bravo";
        
        //$('#chart').html(myJSON.status);//Chart
        //$('#tusuario').html(myJSON.data["enrolled_users"]);//Total de usuarios
        $('#titulo_grafica2').html(kpi.kpi_name);//Titulo grafica
    }

    function addChartc2(kpi) {
        // myJSON = JSON.parse(JSON.stringify(data));
        //console.log(myJSON);
        if(esVacio(kpi.value)){
            return false;
        }
        var chartc = c3.generate({
            data: {
                columns: [
                    ['Aprobado', kpi.value["Aprobado"]],
                    ['No Aprobado', kpi.value["No aprobado"]],
                    ['Destacado', kpi.value["Destacado"]],
                ],
                type: 'pie',
            },
            bindto: "#chart2",
            tooltip: {
                format: {
                    title: function (d) { return 'KPI '; },
                    value: function (value, ratio, id) {
                        var format = id === 'data1' ? d3.format(',') : d3.format('');
                        return format(value);
                    }

                }
            }
        });
    }
    
    //----------------------------------------------------------Reporte de Casos Histórico por tiendas
    function imprimirCards3(kpi) {
        //console.log("entra imprimir2");
        // myJSON = JSON.parse(JSON.stringify(data));
        // console.log(myJSON);        
        document.getElementById("data_card3").innerHTML = "<div class='col-sm-12 col-xl-6'>"+
                                "<div class='card bg-gray border-0 m-2'>"+


                                       "<div class='card-group'>"+
                                          "<div class='card border-0 m-2'>"+
                                            "<div class='card-body'>"+
                                              "<p class='card-text text-primary text-center'>Usuarios  inscritos</p>"+
                                              "<p class='card-text text-primary text-center' id='apro3'></p>"+
                                            "</div>"+
                                          "</div>"+
                                          "<div class='card border-0 m-2'>"+
                                            "<div class='card-body text-center'>"+
                                              "<p class='card-text text-warning text-center'>Aprobados</p>"+
                                              "<p class='card-text text-warning text-center' id='no_apro3'></p>"+
                                            "</div>"+
                                          "</div>"+
                                          "<div class='card border-0 m-2'>"+
                                            "<div class='card-body text-center'>"+
                                              "<p class='card-text text-success text-center'>Total de usuarios</p>"+
                                              "<p class='card-text text-warning text-center' id='tusuario3'></p>"+
                                            "</div>"+
                                          "</div>"+

                                        "</div>"+
                                    "<div class='bg-white m-2' id='chart3'></div>"+
                                   
                                    "<div class='align-items-end'>"+
                                        
                                    "<div class='fincard text-center'>"+
                                            "<a href='Grafica.html' id='titulo_grafica3'></a>"+
                                        "</div>"+
                                    "</div>"+
                                "</div>"+
            "</div>";
        $('#apro3').html(informacion_del_curso.data.enrolled_users);//Inscritos
        $('#no_apro3').html(informacion_del_curso.data.approved_users);//Aprobados
        $('#tusuario3').html(informacion_del_curso.data.not_viewed);//Aprobados

       
        
        //$('#chart').html(myJSON.status);//Chart
        //$('#tusuario').html(myJSON.data["enrolled_users"]);//Total de usuarios
        $('#titulo_grafica3').html(kpi.kpi_name);//Titulo grafica
    }
    
    function addChartc3(kpi) {
        // myJSON = JSON.parse(JSON.stringify(data));
        //console.log(myJSON);
        var chartc = c3.generate({
            data: {
                columns: [
                    ['Aprobado', informacion_del_curso.data.enrolled_users],
                    ['No Aprobado', informacion_del_curso.data.approved_users],
                    ['Destacado', informacion_del_curso.data.not_viewed],
                ],
                type: 'bar',
            },
            bindto: "#chart3",
            tooltip: {
                format: {
                    title: function (d) { return 'KPI '; },
                    value: function (value, ratio, id) {
                        var format = id === 'data1' ? d3.format(',') : d3.format('');
                        return format(value);
                    }

                }
            }
        });
    }
    
//---------------------------------------------------------------------------------------------------------------------------------------------

    ranking_top         = "#dominosdashboard-ranking-top";
    ranking_bottom      = "#dominosdashboard-ranking-bottom";
    ranking_top_body    = "#tbody-ranking-top";
    ranking_bottom_body = "#tbody-ranking-bottom";
    ranking_clase       = ".dominosdashboard-ranking";
    ranking_titulo      = "#dominosdashboard-ranking-title";

    function imprimirRanking(info) {
        if(Array.isArray(info.activities)){
            activities = info.activities;
            num_activities = info.activities.length;
            enrolled_users = parseInt(info.enrolled_users);
            if(enrolled_users < 1){
                return false;
            }
            
            if(num_activities >= 6){ // Se muestran 2 rankings
                $(ranking_titulo).show();
                $(ranking_top).show();
                $(ranking_bottom).show();
                $(ranking_top_body).html('');
                var contenido = "";
                for(var i = 0; i < 3; i++){
                    var elemento = activities[i];
                    // console.log(elemento);
                    percentage = Math.floor(elemento.completed / enrolled_users * 100);
                    // console.log(percentage);
                    contenido += `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${elemento.title}</td>
                        <td>
                            <div class="progress">
                                <div class="progress-bar" style="width: ${percentage}%; background: #29B6F6; color: white; border-radius: 5px; text-align: center;">${percentage}%</div>
                            </div>
                        </td>
                    </tr>`;
                }
                $(ranking_top_body).html(contenido);
                $(ranking_bottom_body).html('');
                var contenido = "";
                // #FF6F00
                for(var i = 1; i <= 3; i++){
                    var elemento = activities[num_activities - i];
                    // console.log(percentage);
                    notCompleted = enrolled_users - elemento.completed;
                    percentage = Math.floor(notCompleted / enrolled_users * 100);
                    contenido += `
                    <tr>
                        <td>${i}</td>
                        <td>${elemento.title}</td>
                        <td>
                            <div class="progress">
                                <div class="progress-bar" style="width: ${percentage}%; background: #FF6F00; color: white;border-radius: 5px; text-align: center;">${percentage}%</div>
                            </div>
                        </td>
                    </tr>`;
                }
                $(ranking_bottom_body).html(contenido);
                return;
            }else if(num_activities > 0){ // Sólo se muestra un ranking
                $(ranking_titulo).show();
                $(ranking_top).show();
                $(ranking_top).removeClass("col-sm-6");
                $(ranking_top).addClass("col-sm-12");
                $(ranking_bottom).hide();
                var contenido = "";
                for(var i = 0; i < 3; i++){
                    if(activities[i] == undefined){
                        continue;
                    }
                    var elemento = activities[i];
                    percentage = Math.floor(elemento.completed / enrolled_users * 100);
                    // console.log(percentage);
                    contenido += `
                    <tr>
                        <td>${i}</td>
                        <td>${elemento.title}</td>
                        <td>
                            <div class="progress">
                                <div class="progress-bar" style="width: ${percentage}%; background: #29B6F6; color: white; border-radius: 5px; text-align: center;">${percentage}%</div>
                            </div>
                        </td>
                    </tr>`;
                }
                $(ranking_top_body).html(contenido);
                return;
            }
            return;
        }
    }

</script>



<?php
// Contenido del dashboard
echo $OUTPUT->footer();
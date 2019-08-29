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
    <div class="col-sm-12" id="data_ob"></div>
    <div class="col-sm-12" id="data_card"></div>
    
    <div class="titulog col-sm-12">
        <h1 style="text-align: center;">Ranking de actividades</h1>
    </div>
    <div class="col-sm-6">
        <table frame="void" rules="rows" style="width:100%">
            <tr class="rankingt">
                <th>#</th>
                <th>Actividades</th>
                <th>Aprobados</th>
            </tr>
            <tr>
                <td>1</td>
                <td>Actividad 1</td>
                <td>
                    <div class="progress">
                        <div class="progress-bar" style="width:90%">90</div>
                    </div>
                </td>
            </tr>
            <tr>
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
            </tr>
        </table>
    </div>

    <div class="col-sm-6">
        <table frame="void" rules="rows" style="width:100%">
            <tr class="rankingt">
                <th>#</th>
                <th>Actividades</th>
                <th>No Aprobados</th>
            </tr>
            <tr>
                <td>1</td>
                <td>Actividad 1</td>
                <td>
                    <div class="progress">
                        <div class="progress-bar bg-warning" style="width:30%">30</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>Actividad 2</td>
                <td>
                    <div class="progress">
                        <div class="progress-bar bg-warning" style="width:20%">20</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>3</td>
                <td>Actividad 3</td>
                <td>
                    <div class="progress">
                        <div class="progress-bar bg-warning" style="width:5%">5</div>
                    </div>
                </td>
            </tr>
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
            console.log("Petición correcta");
            imprimirInfo(data);
            imprimirCards(data);
            addChartc(data);

            dateEnding = Date.now();
            console.log(`Tiempo de respuesta de API al obtener json para gráficas ${dateEnding - dateBegining} ms`);
            // console.log("Petición correcta");
            // console.log(data);
            // $('#local_dominosdashboard_content').html('<pre>' + JSON.stringify(data, undefined, 2) + '</pre>');
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
    
    

    function imprimirInfo(data){
        console.log("entra imprimir");
        myJSON = JSON.parse(JSON.stringify(data));
        console.log(myJSON);
        /*var content = "";
        for ( var element in myJSON.data ) {
            if(myJSON.data.hasOwnProperty[element]){
                var ele = myJSON.data[element]
                for(var item in ele){
                    content += item  +'<br>';
                }
               }
            //content += element[title]  +'<br>';
            
            
        }
        $('#data_ob').html(content)*/
        
        //$('#data_ob').html('<p>'+myJSON.data.key+','+myJSON.data["approved_users"]+'</p>');
        //$('#data_ob').html(myJSON.data["approved_users"]);
        //$('#data_ob').html(JSON.stringify(data));
        
    }
    
    function imprimirCards(data){
        //console.log("entra imprimir");
        myJSON = JSON.parse(JSON.stringify(data));
        console.log(myJSON);        
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
                                              "<p class='card-text text-warning text-center'>213</p>"+
                                            "</div>"+
                                          "</div>"+
                                          "<div class='card border-0 m-2'>"+
                                            "<div class='card-body text-center'>"+
                                              "<p class='card-text text-success text-center'>Total de usuarios</p>"+
                                              "<p class='card-text text-success text-center' id='tusuario'></p>"+
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
                            "</div>"
    //$('#apro').html(myJSON.data["approved_users"]);//Aprobados
    //$('#chart').html(myJSON.status);//Chart
    $('#tusuario').html(myJSON.data["enrolled_users"]);//Total de usuarios
    $('#titulo_grafica').html(myJSON.data["title"]);//Titulo grafica
    } 

    function imprimirRanking(data){

    }
    
    function addChartc(data){
    myJSON = JSON.parse(JSON.stringify(data));
    //console.log(myJSON);
    var chartc = c3.generate({
        data: {
            columns: [
                ['Aprobado', myJSON.data["enrolled_users"]],
                ['No Aprobado', 130],
                ['Destacado', 140]
            ],
            type: 'bar'
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
    
</script>



<?php
// Contenido del dashboard
echo $OUTPUT->footer();
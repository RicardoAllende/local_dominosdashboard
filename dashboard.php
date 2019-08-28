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
// require_capability('local/dominosdashboard:view', context_system::instance());
require_once(__DIR__ . '/lib.php');
require_once("$CFG->libdir/gradelib.php");
require_once("$CFG->dirroot/grade/querylib.php");
require_login();
global $DB;
$PAGE->set_url($CFG->wwwroot . "/local/dominosdashboard/dashboard.php");
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'local_dominosdashboard'));

echo $OUTPUT->header();
echo "<script> var indicadores = '" . DOMINOSDASHBOARD_INDICATORS . "'</script>";

$courses = local_dominosdashboard_get_courses();

// _print($course_elearning = local_dominosdashboard_get_courses_overview(LOCALDOMINOSDASHBOARD_PROGRAMAS_ENTRENAMIENTO));
// _print($classroom = local_dominosdashboard_get_courses_overview(LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS));
// _print($comparison = local_dominosdashboard_get_courses_overview(LOCALDOMINOSDASHBOARD_COURSE_KPI_COMPARISON));

$indicators = local_dominosdashboard_get_kpi_indicators();
?>
<h2>En este momento los valores de los filtros están siendo tomados desde los kpis</h2>
<div class="row">
    <form id="filter_form" method="post" action="services.php" class='col-sm-4'>
        <?php
        echo "<br><select class='form-control course-selector' name='courseid'>";
        foreach($courses as $course){
            echo "<option value='{$course->id}'>{$course->id} -> {$course->fullname}</option>";
        }
        echo "</select>";
        foreach($indicators as $indicator){
            echo "<h3>Indicador: {$indicator}</h3>";
            foreach(local_dominosdashboard_get_kpi_catalogue($indicator) as $item){
                echo "<label><input type=\"checkbox\" name=\"{$indicator}[]\" class=\"indicator_option indicator_{$indicator}\" data-indicator=\"{$indicator}\" value=\"{$item}\">{$item}</label><br>";
            }
            echo "<span type=\"checkbox\" class=\"btn btn-info uncheck_indicators\" data-indicator=\"indicator_{$indicator}\" value=\"1\">Desmarcar todas</span>";
            echo "<span type=\"checkbox\" class=\"btn btn-success check_indicators\" data-indicator=\"indicator_{$indicator}\" value=\"1\">Marcar todas</span><br>";
        }
        ?>
        <input type="hidden" name="request_type" value="course_completion"><br><br>
        <span class="btn btn-info" onclick="obtenerGraficas()">Volver a simular obtención de gráficas</span>
    </form>
    <div class="col-sm-8" id="local_dominosdashboard_content"></div>    
    <div class="col-sm-12" style="padding-top: 50px;" id="local_dominosdashboard_request"></div>
    <div class="col-sm-12" id="data_ob"></div>
    <div class="col-sm-12" id="data_card"></div>
    
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
                $('.indicator_option').click(function(){
                    indicator = $(this).attr('data-indicator');
                    value = $(this).val();
                    obtenerGraficas();
                });
                $('.course-selector').change(function(){
                    obtenerGraficas();
                });
                $('.uncheck_indicators').click(function(){
                    demark = $(this).attr('data-indicator');
                    $('.' + demark).prop('checked', false);
                    obtenerGraficas();
                });
                $('.check_indicators').click(function(){
                    demark = $(this).attr('data-indicator');
                    $('.' + demark).prop('checked', true);
                    obtenerGraficas();
                });
                obtenerGraficas();
                
            });
        }catch(error){
            console.log(error);
        }
    });
    function obtenerGraficas(){
        console.log("Obteniendo gráficas");
        informacion = $('#filter_form').serializeArray();
        //$('#local_dominosdashboard_request').html("<br><br>La petición enviada es: <br>" + $('#filter_form').serialize())
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
            //console.log(data);
            //$('#local_dominosdashboard_content').html(JSON.stringify(data).replace(/{/g, "<br/>{"));
        }) 
        .fail(function(error, error2) {
            console.log(error);
            console.log(error2);
        });
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
    $('#apro').html(myJSON.data["approved_users"]);//Aprobados
    //$('#chart').html(myJSON.status);//Chart
    $('#tusuario').html(myJSON.data["enrolled_users"]);//Total de usuarios
    $('#titulo_grafica').html(myJSON.data["title"]);//Titulo grafica
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
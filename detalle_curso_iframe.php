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
require_login();
$context_system = context_system::instance();
require_capability('local/dominosdashboard:view', $context_system);
require_once(__DIR__ . '/lib.php');
$courseid = optional_param('id', 0, PARAM_INT);
global $DB;
$course = $DB->get_record($table = 'course', $conditions_array = array('id' => $courseid), 'id, shortname, fullname', MUST_EXIST);
$PAGE->set_url($CFG->wwwroot . "/local/dominosdashboard/detalle_curso.php");
$PAGE->set_context($context_system);
// $PAGE->set_pagelayout('admin');

// $PAGE->set_title(get_string('course_details_title', 'local_dominosdashboard') . $course->fullname);

// echo $OUTPUT->header();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css" integrity="sha384-2hfp1SzUoho7/TsGGGDaFdsuuDL0LX2hnUp6VkX3CUQ2K4K+xjboZdsXyp4oUHZj" crossorigin="anonymous">
</head>
<body>    
    <div class="row" style="max-width: 100%;">
        <form id="filter_form" method="post" action="services.php" class='col-sm-3'>
            <a class="btn btn-success" href="dashboard_iframe.php">Volver al dashboard</a><br><br>
            <span class="btn btn-success" onclick="quitarFiltros()">Quitar todos los filtros</span><br><br>
            <input type="hidden" name="courseid" value="<?php echo $course->id; ?>";>
            <div id="contenedor_fechas">
                <label for="fecha_inicial">Desde <input type="date" class="form-control" name="fecha_inicial" id="fecha_inicial"></label> 
                <label for="fecha_final">Hasta <input type="date" class="form-control" name="fecha_final" id="fecha_final"></label>
            </div>
            <div id='contenedor_filtros'></div>
        </form>
        <div class="row col-sm-9" id="contenido_dashboard">
            <div class="col-sm-12 col-xl-12 row" id="course_title"></div>
            <div class="col-sm-12 col-xl-12" id="course_overview"></div>
            <div class="col-sm-12 col-xl-12" id="indicators_title"></div>
            <div class="col-sm-6" id="card_ops"></div>
            <div class="col-sm-6" id="card_numero_de_quejas"></div>
            <div class="col-sm-6" id="card_scorcard"></div>

            <div class="col-sm-12" id="ldm_comparativas"></div>
            <div class="col-sm-12 row" id="ranking_dm"></div>
        </div>
    </div>
    <?php echo local_dominosdashboard_get_ideales_as_js_script(); ?>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/js/bootstrap.min.js" integrity="sha384-VjEeINv9OSwtWFLAtmc4JCtEJXXBub00gtSnszmspDLCtC0I4z4nqz7rEFbIZLLU" crossorigin="anonymous"></script>
    <link href="libs/c3.css" rel="stylesheet">
    <script src="//d3js.org/d3.v3.min.js" charset="utf-8"></script>
    <script src="libs/c3.js"></script>
    <link href="estilos.css" rel="stylesheet">
    <script src="dominosdashboard_scripts.js"></script>

    <script>
        var isCourseLoading = false;
        var isFilterLoading = false;
        var trabajoPendiente = false;
        document.addEventListener("DOMContentLoaded", function () {
            $('.dominosdashboard-ranking').hide();
            $('.course-selector').change(function () { obtenerInformacion() });
            obtenerInformacion();
            obtenerFiltros();
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
        function obtenerInformacion(indicator) {
            if(isCourseLoading){
                trabajoPendiente = false;
                console.log('Cargando contenido de cursos, no debe procesar más peticiones por el momento');
                return;
            }
            isCourseLoading = !isCourseLoading;
            informacion = $('#filter_form').serializeArray();
            informacion.push({ name: 'request_type', value: 'course_completion' });
            // $('#local_dominosdashboard_request').html("<br><br>La petición enviada es: <br>" + $('#filter_form').serialize());
            dateBegining = Date.now();
            // $('#local_dominosdashboard_content').html('Cargando la información');
            $.ajax({
                type: "POST",
                url: "services.php",
                data: informacion,
                dataType: "json"
            })
                .done(function (data) {
                    isCourseLoading = false;
                    // $('#local_dominosdashboard_content').html('<pre>' + JSON.stringify(data, undefined, 2) + '</pre>');
                    informacion_del_curso = JSON.parse(JSON.stringify(data));
                    _kpis = informacion_del_curso.data.kpi;
                    $('#indicators_title').html('');
                    if(informacion_del_curso.data.kpi.length > 0){ insertarTituloSeparador('#indicators_title', 'Cruce de indicadores'); }
                    for (var index = 0; index < informacion_del_curso.data.kpi.length; index++) {
                        _kpi = informacion_del_curso.data.kpi[index];
                        // console.log('Id del kpi', _kpi.kpi);
                        // console.log('Valor del kpi ' + _kpi.kpi_name, _kpi.value);
                        switch (_kpi.kpi) {
                            case 1: // ICA (normalmente regresa Destacado/Aprobado/No aprobado), OPS
                                imprimir_kpi_ops_ica_curso(_kpi, (100 - informacion_del_curso.data.percentage));
                                break;
                            case 2: // Número de quejas, Reporte de Casos Histórico por tiendas
                                imprimir_kpi_reporte_casos_historico_curso(_kpi, (100 - informacion_del_curso.data.percentage));
                                break;
                            case 3: // Porcentaje de rotación, scorcard
                                imprimir_kpi_scorcard_rotacion_curso(_kpi, (100 - informacion_del_curso.data.percentage));
                                break;
                            default:
                                break;
                        }
                    }
                    $('#course_title,#course_overview').html('');
                    insertarTituloSeparador('#course_title', 'Curso ' + informacion_del_curso.data.title);
                    crearTarjetaParaGrafica('#course_overview', informacion_del_curso.data, 'col-sm-12 col-xl-12');
                    imprimirGraficaComparativaDentroDeCurso('#ldm_comparativas', informacion_del_curso.data);

                    imprimirRanking('#ranking_dm', informacion_del_curso.data);
                    dateEnding = Date.now();
                    console.log(`Tiempo de respuesta de API al obtener json para gráficas ${dateEnding - dateBegining} ms`);
                })
                .fail(function (error, error2) {
                    isCourseLoading = false;
                    console.log(error);
                    console.log(error2);
                });
            if (indicator !== undefined) {
                obtenerFiltros(indicator);
            }
        }

        function imprimir_kpi_ops_ica_curso(kpi) {
            if (esVacio(kpi.value)) {
                insertarGraficaSinInfo("#card_ops");
            }else{
                var a = obtenerDefaultEnNull(kpi.value["Aprobado"]);
                var b = obtenerDefaultEnNull(kpi.value["No aprobado"]);
                var c = parseInt(a) + parseInt(b);

                document.getElementById("card_ops").innerHTML = "<div class='col-sm-12 espacio'>"+
                                    "<div class='card bg-gray border-0 m-2'>"+


                                        "<div class='card-group'>"+
                                            "<div class='card border-0 m-2'>"+
                                                "<div class='card-body'>"+
                                                "<p class='card-text text-primary text-center txti'>Aprobados</p>"+
                                                "<p class='card-text text-primary text-center txtnum' id='apro2'></p>"+
                                                "</div>"+
                                            "</div>"+
                                            "<div class='card border-0 m-2'>"+
                                                "<div class='card-body text-center'>"+
                                                "<p class='card-text text-warning text-center txti'>No Aprobados</p>"+
                                                "<p class='card-text text-warning text-center txtnum' id='no_apro2'></p>"+
                                                "</div>"+
                                            "</div>"+
                                            "<div class='card border-0 m-2'>"+
                                                "<div class='card-body text-center'>"+
                                                "<p class='card-text text-success text-center txti'>Total de usuarios</p>"+
                                                "<p class='card-text text-warning text-center txtnum' id='tusuario2'>"+c+"</p>"+
                                                "</div>"+
                                            "</div>"+

                                            "</div>"+
                                        "<div class='bg-faded m-2' id='chart2'></div>"+
                                    
                                        "<div class='align-items-end'>"+
                                            
                                        "<div class='fincard text-center'>"+
                                                "<a href='#' id='titulo_grafica2'></a>"+
                                            "</div>"+
                                        "</div>"+
                                    "</div>"+
                    "</div>";
                $('#apro2').html(obtenerDefaultEnNull(kpi.value["Aprobado"]));//Aprobados
                $('#no_apro2').html(obtenerDefaultEnNull(kpi.value["No aprobado"]));//No Aprobados

                $('#titulo_grafica2').html(kpi.kpi_name);//Titulo grafica
                
                var chartc = c3.generate({
                    data: {
                        columns: [
                            ['Aprobado', obtenerDefaultEnNull(kpi.value["Aprobado"])],
                            ['No Aprobado', obtenerDefaultEnNull(kpi.value["No aprobado"])],
                            ['Destacado', obtenerDefaultEnNull(kpi.value["Destacado"])],
                        ],
                        type: 'pie',
                    },
                    bindto: "#chart2",
                    tooltip: {
                        format: {
                            title: function (d) { return 'Calificacion '; },
                            value: function (value, ratio, id) {
                                var format = id === 'data1' ? d3.format(',') : d3.format('');
                                return format(value);
                            }

                        }
                    }
                });
            }
        }

        function imprimir_kpi_reporte_casos_historico_curso(kpi, not_approved) {
            if(!esVacio(kpi)){
                var a = obtenerDefaultEnNull(kpi.value["Aprobado"]);
                var b = obtenerDefaultEnNull(kpi.value["No aprobado"]);
                var c = parseInt(a) + parseInt(b);
                var d = parseInt(b) * 100;
                var e = parseInt(d) / parseInt(c);

                document.getElementById("card_numero_de_quejas").innerHTML = "<div class='col-sm-12 espacio'>"+
                                        "<div class='card bg-gray border-0 m-2'>"+


                                            // "<div class='card-group'>"+
                                            //     "<div class='card border-0 m-2'>"+
                                            //         "<div class='card-body'>"+
                                            //         "<p class='card-text text-primary text-center txti'>Aprobados</p>"+
                                            //         "<p class='card-text text-primary text-center txtnum' id='apro3'></p>"+
                                            //         "</div>"+
                                            //     "</div>"+
                                            //     "<div class='card border-0 m-2'>"+
                                            //         "<div class='card-body text-center'>"+
                                            //         "<p class='card-text text-warning text-center txti'>No Aprobados</p>"+
                                            //         "<p class='card-text text-warning text-center txtnum'></p>"+
                                            //         "</div>"+
                                            //     "</div>"+
                                            //     "<div class='card border-0 m-2'>"+
                                            //         "<div class='card-body text-center'>"+
                                            //         "<p class='card-text text-success text-center txti'>No visto</p>"+
                                            //         "<p class='card-text text-warning text-center txtnum' id='tusuario3'></p>"+
                                            //         "</div>"+
                                            //     "</div>"+

                                            // "</div>"+
                                            "<div class='bg-faded m-2' id='chart3'></div>"+
                                        
                                            "<div class='align-items-end'>"+
                                                
                                            "<div class='fincard text-center'>"+
                                                    "<a href='#' id='titulo_grafica3'></a>"+
                                                "</div>"+
                                            "</div>"+
                                        "</div>"+
                    "</div>";
                $('#apro3').html(obtenerDefaultEnNull(kpi.value["Aprobado"]));//Aprobados
                $('#no_apro3').html(obtenerDefaultEnNull(kpi.value["No aprobado"]));// No Aprobados

                $('#titulo_grafica3').html(kpi.kpi_name);//Titulo grafica

                // var a = obtenerDefaultEnNull(kpi.value["Aprobado"]);
                // var b = obtenerDefaultEnNull(kpi.value["No aprobado"]);
                // var c = parseInt(a) + parseInt(b);
                // var d = parseInt(b) * 100;
                // var e = parseInt(d) / parseInt(c);
                // var f = e.toFixed(2);
                var chartc = c3.generate({
                    data: {
                        columns: [
                            ['No Aprobado', not_approved],
                            ['Promedio de no. de quejas', _kpi.value]
                        ],
                        type: 'bar',
                    },
                    bindto: "#chart3",
                    tooltip: {
                        format: {
                            title: function (d) { return 'Quejas '; },
                            value: function (value, ratio, id) {
                                var format = id === 'data1' ? d3.format(',') : d3.format('');
                                return format(value);
                            }

                        }
                    }
                });
            }else{
                insertarGraficaSinInfo("#card_numero_de_quejas")
            }
        }

        function imprimir_kpi_scorcard_rotacion_curso(kpi, not_approved) {
            if(!esVacio(kpi.value)){  
                document.getElementById("card_scorcard").innerHTML = "<div class='col-sm-12 espacio'>"+
                                        "<div class='card bg-gray border-0 m-2'>"+
                                            // "<div class='card-group'>"+
                                            //     "<div class='card border-0 m-2'>"+
                                            //         "<div class='card-body'>"+
                                            //         "<p class='card-text text-primary text-center txti'>Aprobados</p>"+
                                            //         "<p class='card-text text-primary text-center txtnum' id='apro4'></p>"+
                                            //         "</div>"+
                                            //     "</div>"+
                                            //     "<div class='card border-0 m-2'>"+
                                            //         "<div class='card-body text-center'>"+
                                            //         "<p class='card-text text-warning text-center txti'>No Aprobados</p>"+
                                            //         "<p class='card-text text-warning text-center txtnum' id='no_apro4'></p>"+
                                            //         "</div>"+
                                            //     "</div>"+
                                            //     "<div class='card border-0 m-2'>"+
                                            //         "<div class='card-body text-center'>"+
                                            //         "<p class='card-text text-success text-center txti'>No visto</p>"+
                                            //         "<p class='card-text text-warning text-center txtnum' id='tusuario4'></p>"+
                                            //         "</div>"+
                                            //     "</div>"+

                                            // "</div>"+
                                            "<div class='bg-faded m-2' id='chart4'></div>"+
                                        
                                            "<div class='align-items-end'>"+
                                                
                                            "<div class='fincard text-center'>"+
                                                    "<a href='#' id='titulo_grafica4'></a>"+
                                                "</div>"+
                                            "</div>"+
                                        "</div>"+
                    "</div>";
                // $('#apro4').html(kpi.value["Aprobado"]);//Aprobados
                // $('#no_apro4').html(kpi.value["No aprobado"]);//No Aprobados

                $('#titulo_grafica4').html(kpi.kpi_name);//Titulo grafica
                // var a = kpi.value["Aprobado"];
                // var b = kpi.value["No aprobado"];
                // var c = parseInt(a) + parseInt(b);
                // var d = parseInt(b) * 100;
                // var e = parseInt(d) / parseInt(c);
                // var f = e.toFixed(2);
                var chartc = c3.generate({
                    data: {
                        columns: [
                            ['No Aprobado', not_approved],
                            ['Promedio de rotación', _kpi.value]
                        ],
                        type: 'bar',
                    },
                    bindto: "#chart4",
                    tooltip: {
                        format: {
                            title: function (d) { return 'Rotación '; },
                            value: function (value, ratio, id) {
                                var format = id === 'data1' ? d3.format(',') : d3.format('');
                                return format(value);
                            }

                        }
                    }
                });
            }else{
                insertarGraficaSinInfo("#card_scorcard")
            }
        }
    </script>
</body>
</html>



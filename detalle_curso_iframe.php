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
require_once(__DIR__ . '/lib.php');
local_dominosdashboard_user_has_access();
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
    <title>Detalle del curso <?php echo $course->fullname; ?></title>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css" integrity="sha384-2hfp1SzUoho7/TsGGGDaFdsuuDL0LX2hnUp6VkX3CUQ2K4K+xjboZdsXyp4oUHZj" crossorigin="anonymous">
    <link rel="stylesheet" href="css/jquery.loadingModal.css">
    <link href="estilos.css" rel="stylesheet">
</head>
<body onload="loaderGeneral()">    
    <div class="row" style="max-width: 100%; min-height: 300px;">
        <form id="filter_form" name="filter_form" method="post" action="imprimir.php" class='col-sm-3'>
            <a class="btn btn-success" href="dashboard_iframe.php">Volver al dashboard</a><br><br>
            <!-- <span class="btn btn-success" onclick="quitarFiltros()">Quitar todos los filtros</span><br><br> -->
            <input type="hidden" name="selected_ccoms" id="selected_ccoms" value="*">
            <input type="hidden" name="courseid" value="<?php echo $course->id; ?>";>
            <div id="contenedor_fechas">
                <label for="fecha_inicial">Desde <input type="date" onchange="obtenerInformacion(),loaderFecha()" class="form-control" name="fecha_inicial" id="fecha_inicial"></label> 
                <label for="fecha_final">Hasta <input type="date" onchange="obtenerInformacion(),loaderFecha()" class="form-control" name="fecha_final" id="fecha_final"></label>
            </div>
            <input type="hidden" name="report_type" id="report_type" value="course_completion">
            <div class="col-sm-11" id='contenedor_filtros' style="text-align: center;"></div>
        </form>
        <div id="loader"></div>
        <div class="row col-sm-9" id="contenido_dashboard">
            <div class="col-sm-12 col-xl-12" id="course_title"></div>
            <div class="col-sm-12 col-xl-12" id="course_overview"></div>
            <div class="col-sm-12 col-xl-12" id="indicators_title"></div>            
            <div class="col-sm-12" id="todos_los_kpis"></div>
            
            

            <div class="col-sm-12" id="ranking_dm"></div>
            <div class="col-sm-12" id="ldm_comparativas"></div>
        </div>
    </div>
    <?php echo local_dominosdashboard_get_ideales_as_js_script(); ?>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/js/bootstrap.min.js" integrity="sha384-VjEeINv9OSwtWFLAtmc4JCtEJXXBub00gtSnszmspDLCtC0I4z4nqz7rEFbIZLLU" crossorigin="anonymous"></script>
    <link href="libs/c3.css" rel="stylesheet">
    <script src="//d3js.org/d3.v3.min.js" charset="utf-8"></script>
    <script src="libs/c3.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="js/jquery.loadingModal.js"></script>
    <script src="dominosdashboard_scripts.js"></script>

    <script>
        var muestraComparativas = true;
        var isCourseLoading = false;
        var isFilterLoading = false;
        var trabajoPendiente = false;
        var comparativa;
        imprimirComparativaFiltrosDeCurso('#ldm_comparativas');
        var comparativas = 0;
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
            obtenerInformacion();
        }
        var _kpi;
        var _kpis;
        function obtenerInformacion(indicator) {
            // if(isCourseLoading){
            //     trabajoPendiente = false;
            //     console.log('Cargando contenido de cursos, no debe procesar más peticiones por el momento');
            //     return;
            // }
            // isCourseLoading = !isCourseLoading;
            ajustar_ccoms();
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
                        
                        console.log("index: "+ index);
                        if(esVacio(_kpi.value)){
                            insertarGraficaSinInfo("#todos_los_kpis");
                            continue;
                        }
                        switch (_kpi.type) {
                            case 'Texto': // ICA (normalmente regresa Destacado/Aprobado/No aprobado), (ejemplo)
                            var valor = [];
                            var contador = 0;
                            $.each(_kpi.value, function( ind, value ) {                          
                            valor.push([ind, parseInt(value)]);
                            
                            contador++;                                       
                            });
                            kpi_texto(valor, informacion_del_curso.data.percentage ,index);
                            break;
                            case 'Número entero': // Número entero de quejas (ejemplo)
                            var a = ((informacion_del_curso.data.approved_users * 100)/informacion_del_curso.data.enrolled_users);
                            var porcentaje_aprobados_entero = a.toFixed(2);
                            //console.log('AQUI VIENE LA A');
                            //console.log(porcentaje_aprobados_entero);
                            var valor_numero = [[_kpi.kpi_key , parseInt(_kpi.value)],['Porcentaje de aprobados',porcentaje_aprobados_entero]];                            
                            kpi_numero(valor_numero, informacion_del_curso.data.percentage,index);
                            break;    
                            case 'Porcentaje': // Número entero de quejas, Reporte de Casos Histórico por tiendas
                            var b = ((informacion_del_curso.data.approved_users * 100)/informacion_del_curso.data.enrolled_users);
                            var porcentaje_aprobados_porcentaje = b.toFixed(2);
                            //console.log('AQUI VIENE LA B');
                            //console.log(porcentaje_aprobados_porcentaje);
                            var valor_porcentaje = [[_kpi.kpi_key , parseFloat(_kpi.value)],['Porcentaje de aprobados',porcentaje_aprobados_porcentaje]];                            
                            kpi_numero(valor_porcentaje, informacion_del_curso.data.percentage,index); 
                            break;
                            //  3: // Porcentaje de rotación, scorcard
                            //     imprimir_kpi_scorcard_rotacion_curso(_kpi, informacion_del_curso.data.percentage);
                            //     break;
                            default:
                                break;
                        }
                    }
                    $('#course_title,#course_overview').html('');
                    insertarTituloSeparador('#course_title', 'Curso ' + informacion_del_curso.data.title);
                    crearTarjetaParaGrafica('#course_overview', informacion_del_curso.data, 'col-sm-12 col-xl-12');
                    //Aqui va una funcion nueva 
                    imprimirRanking('#ranking_dm', informacion_del_curso.data);
                    dateEnding = Date.now();
                    console.log(`Tiempo de respuesta de API al obtener json para gráficas ${dateEnding - dateBegining} ms`);
                    showPage("contenido_dashboard");                    
                    showPage_fecha("contenido_dashboard");
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
        var ccoms_seleccionados;
        function ajustar_ccoms(){
            ccoms_seleccionados = "";
            $('.indicator_ccosto').each(function (index, element){
                if($(this).is(':checked')){
                    if(ccoms_seleccionados == ''){
                        ccoms_seleccionados = $(this).val();
                    }else{
                        ccoms_seleccionados = ccoms_seleccionados + ',' + $(this).val();
                    }
                }
            });
            if(ccoms_seleccionados == ''){
                $('#selected_ccoms').val('*');
            }else{
                $('#selected_ccoms').val(ccoms_seleccionados);
            }
        }
        
        // Función cuando el tipo de kpi es texto
        function kpi_texto(valores, porcentaje_curso, indexfor){
            
            div_selector = '#todos_los_kpis';
            
                $(div_selector).append(`
                <div class='col-sm-6 espacio'>
                                    <div class='card bg-gray border-0 m-2'>
                                        <div class='align-items-end'>
                                            <div class='fincard text-center'>
                                                <a href='#' id='titulo_grafica2'>${_kpi.kpi_name}</a>
                                            </div>
                                        </div>                                       
                                        <div class='bg-faded m-2' id='chart_texto_${indexfor}'></div>                                     
                                    </div>
                    </div>
                `);
                    var chartc = c3.generate({
                    data: {                        
                        columns: valores,
                        type: 'bar',
                        colors: {
                            'Aprobado': '#008000',
                            'No aprobado': '#ff0000', 
                            'Destacado': '#ff7f0e',
                         //   '% de aprobación de un curso': '#d6c4b5'                                            
                        }
                    },
                    bindto: "#chart_texto_"+indexfor,
                    tooltip: {
                        format: {
                            title: function (d) { return ''; },
                            value: function (value, ratio, id) {
                                var format = id === 'data1' ? d3.format(',') : d3.format('');
                                return format(value);
                           }
                        }
                    }
                });
            
        }
        // Función cuando el tipo de kpi es entero o porcentaje
        function kpi_numero(valores, porcentaje_curso, indexfor){           
            div_selector = '#todos_los_kpis';            
            
                $(div_selector).append(`
                <div class='col-sm-6 espacio'>
                                    <div class='card bg-gray border-0 m-2'>
                                        <div class='align-items-end'>
                                            <div class='fincard text-center'>
                                                <a href='#' id='titulo_grafica2'>${_kpi.kpi_name}</a>
                                            </div>
                                        </div>                                       
                                        <div class='bg-faded m-2' id='chart_numero_${indexfor}'></div>                                     
                                    </div>
                    </div>
                `);
                    var chartc = c3.generate({
                    data: {                        
                        columns: valores,
                        type: 'bar'
                        // colors: {
                        //     'Aprobado': '#008000',
                        //     'No Aprobado': '#ff0000', 
                        //     'Destacado': '#ff7f0e',
                        //     '% de aprobación de un curso': '#d6c4b5'                                            
                        // }
                    },
                    bindto: "#chart_numero_"+indexfor,
                    tooltip: {
                        format: {
                            title: function (d) { return ''; },
                            value: function (value, ratio, id) {
                                var format = id === 'data1' ? d3.format(',') : d3.format('');
                                return format(value);
                            }
                        }
                    }
                });
            
         
        }
        
        function imprimir_kpi_ops_ica_curso(kpi) {
            if (esVacio(kpi.value)) {
                insertarGraficaSinInfo("#card_ops");
            }else{
                //var a = obtenerDefaultEnNull(kpi.value["Aprobado"]);
                //var b = obtenerDefaultEnNull(kpi.value["No aprobado"]);
                //var c = parseInt(a) + parseInt(b);
                document.getElementById("card_ops").innerHTML = "<div class='col-sm-12 espacio'>"+
                                    "<div class='card bg-gray border-0 m-2'>"+
                                        "<div class='align-items-end'>"+
                                            "<div class='fincard text-center'>"+
                                                "<a href='#' id='titulo_grafica2'></a>"+
                                            "</div>"+
                                        "</div>"+
                                        // "<div class='card esp'>"+
                                        //     "<div class='row espr'>"+
                                        //         "<div class='border-0 col-sm-4'>"+
                                        //             "<div class='card-body'>"+
                                        //                 "<p class='card-text text-primary text-center txti'>Aprobados</p>"+
                                        //                 "<p class='card-text text-primary text-center txtnum' id='apro2'></p>"+
                                        //             "</div>"+
                                        //         "</div>"+
                                        //         "<div class='border-0 col-sm-4'>"+
                                        //             "<div class='card-body text-center'>"+
                                        //                 "<p class='card-text text-warning text-center txti'>No Aprobados</p>"+
                                        //                 "<p class='card-text text-warning text-center txtnum' id='no_apro2'></p>"+
                                        //             "</div>"+
                                        //         "</div>"+
                                        //         "<div class='border-0 col-sm-4'>"+
                                        //             "<div class='card-body text-center'>"+
                                        //                 "<p class='card-text text-success text-center txti'>Total de usuarios</p>"+
                                        //                 "<p class='card-text text-warning text-center txtnum' id='tusuario2'></p>"+
                                        //             "</div>"+
                                        //         "</div>"+
                                        //     "</div>"+
                                        // "</div>"+
                                        "<div class='bg-faded m-2' id='chart2'></div>"+                                     
                                    "</div>"+
                    "</div>";
                    
                //$('#apro2').html(obtenerDefaultEnNull(kpi.value["Aprobado"]));//Aprobados
                //$('#no_apro2').html(obtenerDefaultEnNull(kpi.value["No aprobado"]));//No Aprobados
                $('#titulo_grafica2').html(kpi.kpi_name);//Titulo grafica
                
                var chartc = c3.generate({
                    data: {
                        columns: [
                            ['Aprobado', obtenerDefaultEnNull(kpi.value["Aprobado"])],
                            ['No Aprobado', obtenerDefaultEnNull(kpi.value["No aprobado"])],
                            ['Destacado', obtenerDefaultEnNull(kpi.value["Destacado"])],
                            ['% de aprobación de un curso', obtenerDefaultEnNull(informacion_del_curso.data.percentage)],
                        ],
                        type: 'bar',
                        colors: {
                            'Aprobado': '#008000',
                            'No Aprobado': '#ff0000', 
                            'Destacado': '#ff7f0e',
                            '% de aprobación de un curso': '#d6c4b5'                                            
                        }
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
            if(!esVacio(kpi.value)){
                var a = obtenerDefaultEnNull(kpi.value["Aprobado"]);
                var b = obtenerDefaultEnNull(kpi.value["No aprobado"]);
                var c = parseInt(a) + parseInt(b);
                var d = parseInt(b) * 100;
                var e = parseInt(d) / parseInt(c);
                
                document.getElementById("card_numero_de_quejas").innerHTML = "<div class='col-sm-12 espacio'>"+
                                        "<div class='card bg-gray border-0 m-2'>"+
                                            "<div class='align-items-end'>"+
                                                "<div class='fincard text-center'>"+
                                                    "<a href='#' id='titulo_grafica3'></a>"+
                                                "</div>"+
                                            "</div>"+
                                            // "<div class='card esp'>"+
                                            //     "<div class='row espr'>"+                                            
                                            //         "<div class='border-0 col-sm-4'>"+
                                            //             "<div class='card-body'>"+
                                            //                 "<p class='card-text text-primary text-center txti'>Aprobados</p>"+
                                            //                 "<p class='card-text text-primary text-center txtnum' id='apro3'></p>"+
                                            //             "</div>"+
                                            //         "</div>"+
                                            //         "<div class='border-0 col-sm-4'>"+
                                            //             "<div class='card-body text-center'>"+
                                            //                 "<p class='card-text text-warning text-center txti'>No Aprobados</p>"+
                                            //                 "<p class='card-text text-warning text-center txtnum'></p>"+
                                            //             "</div>"+
                                            //         "</div>"+
                                            //         "<div class='border-0 col-sm-4'>"+
                                            //             "<div class='card-body text-center'>"+
                                            //                 "<p class='card-text text-success text-center txti'>No visto</p>"+
                                            //                 "<p class='card-text text-warning text-center txtnum' id='tusuario3'></p>"+
                                            //             "</div>"+
                                            //         "</div>"+
                                            //     "</div>"+
                                            // "</div>"+
                                            "<div class='bg-faded m-2' id='chart3'></div>"+                                          
                                        "</div>"+
                    "</div>";
                //$('#apro3').html(obtenerDefaultEnNull(kpi.value["Aprobado"]));//Aprobados
                //$('#no_apro3').html(obtenerDefaultEnNull(kpi.value["No aprobado"]));// No Aprobados
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
                            ['No Aprobado',(100-informacion_del_curso.data.percentage).toFixed(2)],
                            ['Promedio de no. de quejas', _kpi.value]
                        ],
                        type: 'bar',
                        colors: {
                            'No Aprobado': '#ff0000', 
                            'Promedio de no. de quejas': '#97a5af'                                            
                        }
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
                if(esVacio(_kpi.value["rotacion_mensual"]) && esVacio(_kpi.value["rotacion_rolling"])){
                    insertarGraficaSinInfo("#card_scorcard");
                    return;
                }
                document.getElementById("card_scorcard").innerHTML = "<div class='col-sm-12 espacio'>"+
                                        "<div class='card bg-gray border-0 m-2'>"+
                                            "<div class='align-items-end'>"+
                                                "<div class='fincard text-center'>"+
                                                    "<a href='#' id='titulo_grafica4'></a>"+
                                                "</div>"+
                                            "</div>"+
                                            // "<div class='card esp'>"+
                                            //     "<div class='row espr'>"+
                                            //         "<div class='border-0 col-sm-4'>"+
                                            //             "<div class='card-body'>"+
                                            //                 "<p class='card-text text-primary text-center txti'>Aprobados</p>"+
                                            //                 "<p class='card-text text-primary text-center txtnum' id='apro4'></p>"+
                                            //             "</div>"+
                                            //         "</div>"+
                                            //         "<div class='border-0 col-sm-4'>"+
                                            //             "<div class='card-body text-center'>"+
                                            //                 "<p class='card-text text-warning text-center txti'>No Aprobados</p>"+
                                            //                 "<p class='card-text text-warning text-center txtnum' id='no_apro4'></p>"+
                                            //             "</div>"+
                                            //         "</div>"+
                                            //         "<div class='border-0 col-sm-4'>"+
                                            //             "<div class='card-body text-center'>"+
                                            //                 "<p class='card-text text-success text-center txti'>No visto</p>"+
                                            //                 "<p class='card-text text-warning text-center txtnum' id='tusuario4'></p>"+
                                            //             "</div>"+
                                            //         "</div>"+
                                            //     "</div>"+
                                            // "</div>"+
                                            "<div class='bg-faded m-2' id='chart4'></div>"+                                        
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
                            ['No Aprobado', (100-informacion_del_curso.data.percentage).toFixed(2)],
                            ['Promedio de rotación', kpi.value],
                            ['Ideal de rotación', ideal_rotacion],
                            ['Rotación mensual', kpi.value["rotacion_mensual"]],
                            ['Rotación rolling', kpi.value["rotacion_rolling"]]
                        ],
                        type: 'bar',
                        colors: {
                            'No Aprobado': '#ff0000',
                            'Promedio de rotación': '#6f5252',
                            'Ideal de rotación': '#f7e488',
                            'Rotación mensual': '#49bf89',
                            'Rotación rolling': '#3c87bb'                                        
                        }
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
        var comparativaMaxima = 20;
        var clase;
        function compararFiltros(filtro_seleccionado){
            mostrarLoader();
            informacion = $('#filter_form').serializeArray();
            informacion.push({ name: 'request_type', value: 'course_comparative' });
            informacion.push({ name: 'selected_filter', value: filtro_seleccionado });
            clase = ".indicator_" + filtro_seleccionado;
            elementosAComparar = $(clase).filter(':checked').length;
            if(elementosAComparar == 0){
                elementosAComparar = $(clase).length;
            }
            if(elementosAComparar > comparativaMaxima){
                if(!confirm('Usted está comparando más de ' + comparativaMaxima + ' elementos, ¿desea modificar su selección?')){
                    return false;
                }
            }
            loaderComparar();
            dateBeginingComparacion = Date.now();
            $.ajax({
                type: "POST",
                url: "services.php",
                data: informacion,
                dataType: "json"
            })
                .done(function (response) {
                    comparativa = JSON.parse(JSON.stringify(response));
                    console.log('Información para crear comparativa: ', comparativa);
                    imprimirComparativaFiltrosDeCurso('#ldm_comparativas', comparativa.data);
                    dateEnding = Date.now();
                    console.log(`Tiempo de respuesta de API al obtener json para comparativas ${dateEnding - dateBeginingComparacion} ms`);
                    ocultarLoader();
                })
                .fail(function (error, error2) {
                    // isCourseLoading = false;
                    console.log(error);
                    console.log(error2);
                    ocultarLoader();
                });
        }
        /**
         * @param _bindto string selector con sintaxis jquery donde se imprimirán las gráficas
         */
        function imprimirComparativaFiltrosDeCurso(_bindto, informacion){
            $(_bindto).html('');
            if(!esVacio(informacion.comparative)){
                comparative = informacion.comparative;
                columns = Array();
                comparativas++;
                id_para_Grafica = 'ldm_comparativa_' + comparativas + '_' + informacion.key;
                insertarTituloSeparador(_bindto, 'Comparativa ' + informacion.filter);
                $(_bindto).append(`<div class='col-sm-12'><div id="${id_para_Grafica}"></div></div><br>`);
                // $(_bindto).append(`<div><h4 style="text-transform: uppercase;">Comparativa ${informacion.filter}</h4><div id="${id_para_Grafica}"></div></div>`);
                id_para_Grafica = '#' + id_para_Grafica;
                for(var j = 0; j < comparative.length; j++){
                    datos_a_comparar = comparative[j];
                    columns.push([datos_a_comparar.name, datos_a_comparar.percentage]);
                }
                data = { columns: columns, type: 'bar'};
                var chart = c3.generate({
                    data: data,
                    axis: {
                        rotated: true
                    },
                    tooltip: {
                        format: {
                            title: function (d) { return 'Porcentaje de aprobación'; },
                        }               
                    },   
                    bindto: id_para_Grafica,
                });
            }else{
                console.log('Error de imprimirComparativaFiltrosDeCurso', informacion);
                $(_bindto).html('');
            }
        }
    </script>
</body>
</html>

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
$PAGE->set_url($CFG->wwwroot . "/local/dominosdashboard/prueba_historico.php");
$PAGE->set_context($context_system);
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'local_dominosdashboard'));

echo $OUTPUT->header();

$courses = local_dominosdashboard_get_courses();

?>
<div class="row">
    <form id="filter_form" method="post" action="services.php" class='col-sm-3'>
        <span class="btn btn-success" onclick="quitarFiltros()">Quitar todos los filtros</span><br><br>
        <span class="btn btn-info" onclick="obtenerInformacion()">Volver a simular obtención de gráficas</span><br><br>
        <?php
        echo "<br><select class='form-control course-selector' name='courseid'>";
        foreach($courses as $course){
            echo "<option value='{$course->id}'>{$course->id} -> {$course->fullname}</option>";
        }
        echo "</select><br>";
        ?>
        <div id='contenedor_filtros'></div>
    </form>
    <div class="row col-sm-9 row">
        <div class="col-sm-12">
            <div id="data_card2"></div>
        </div>
        <div class="col-sm-12" id="print_request" ></div>
    </div>
    




</div>
<?php echo local_dominosdashboard_get_ideales_as_js_script(); ?>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<link href="libs/c3.css" rel="stylesheet">
<script src="//d3js.org/d3.v3.min.js" charset="utf-8"></script>
<script src="libs/c3.js"></script>
<link href="estilos.css" rel="stylesheet">
<link rel="stylesheet" href="estilos.css" type="text/css"/>
<link href="estilos.css" rel="stylesheet" type="text/css" media="print" />
<script src="dominosdashboard_scripts.js"></script>

<script>
    var serialized_form = "";
    document.addEventListener("DOMContentLoaded", function () {
        try {
            $('.dominosdashboard-ranking').hide();
            require(['jquery'], function ($) {
                agregarGraficaHistorico('#data_card2');
                $('.course-selector').change(function () { obtenerInformacion(); });
                obtenerInformacion();
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
    function obtenerInformacion(indicator) {
        informacion = $('#filter_form').serializeArray();
        informacion.push({ name: 'request_type', value: 'course_historics' });
        console.log('Consulta al servicio retorna', informacion);
        $('#local_dominosdashboard_request').html("<br><br>La petición enviada es: <br>" + $('#filter_form').serialize());
        dateBegining = Date.now();
        $('#print_request').html('Cargando la información');
        $.ajax({
            type: "POST",
            url: "services.php",
            data: informacion,
            dataType: "json"
        })
            .done(function (data) {
                $('#print_request').html('<pre>' + JSON.stringify(data, undefined, 2) + '</pre>');
                dateEnding = Date.now();
                console.log(`Tiempo de respuesta de API al obtener json para gráficas ${dateEnding - dateBegining} ms`);
            })
            .fail(function (error, error2) {
                console.log(error);
                console.log(error2);
            });
        if (indicator !== undefined) {
            obtenerFiltros(indicator);
        }
    }

    function agregarGraficaHistorico(_bindto) {
        return c3.generate({
            data: {
                columns: [
                    ['Aprobados', 50, 50, 46, 20, 14, 40, 38, 50, 66, 78],
                    ['No hecho', 30, 30, 26, 62, 74, 20, 68, 10, 36, 68],
                    ['No aprobado', 23, 23, 20, 30, 19, 30, 80, 20, 42, 71],
                ],
                type: '',
            },
            bindto: _bindto,
            tooltip: {
                format: {
                    title: function (d) { return 'KPI '; },
                }
            }
        });
    }


    function imprimir() {
        window.print();
    }


</script>


<?php
// Contenido del dashboard
echo $OUTPUT->footer();
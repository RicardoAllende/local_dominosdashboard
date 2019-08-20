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
$PAGE->set_url($CFG->wwwroot . "/local/dominosdashboard/_Dashboard.php");
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'local_dominosdashboard'));

echo $OUTPUT->header();
echo "<script> var indicadores = '" . DOMINOSDASHBOARD_INDICATORS . "'</script>";

$courses = local_dominosdashboard_get_courses();

// _print($course_elearning = local_dominosdashboard_get_courses_overview(LOCALDOMINOSDASHBOARD_PROGRAMAS_ENTRENAMIENTO));
// _print($classroom = local_dominosdashboard_get_courses_overview(LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS));
// _print($comparison = local_dominosdashboard_get_courses_overview(LOCALDOMINOSDASHBOARD_COURSE_KPI_COMPARISON));

$indicators = local_dominosdashboard_get_indicators();
?>

<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/c3/0.7.4/c3.css" rel="stylesheet">
<link href="css/simple-sidebar.css" rel="stylesheet">
<link href="libs/c3.css" rel="stylesheet" type="text/css">





<!-- <!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Domino´s</title>
  
   Load c3.css 
    
    
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>

   
    
    
    
    


  
  
  

</head>

<body>

  <div class="d-flex" id="wrapper"> -->
  <script src="https://code.jquery.com/jquery-3.4.1.slim.js"></script>
  <script src="//d3js.org/d3.v3.min.js" charset="utf-8"></script>

    <!-- Sidebar -->
    <div class="border-right" id="sidebar-wrapper">
    <div class="sidebar-heading">Filtros </div>
    <div class="list-group list-group-flush">
        <div id="accordion" align="right">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"
                            aria-expanded="true" aria-controls="collapseOne">
                            Nacional <input type="checkbox">
                        </button>
                    </h5>
                </div>
                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                    <!--<div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                  </div>-->
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo"
                            aria-expanded="false" aria-controls="collapseTwo">
                            Regional <input type="checkbox">
                        </button>
                    </h5>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card-body">
                        <p>Centro Norte <input type="checkbox"></p>
                        <p>Centro Sur <input type="checkbox"></p>
                        <p>Puebla-Veracruz <input type="checkbox"></p>
                        <p>Occidente <input type="checkbox"></p>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingThree">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree"
                            aria-expanded="false" aria-controls="collapseThree">
                            Distrital <input type="checkbox">
                        </button>
                    </h5>
                </div>
                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                    <!--<div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                  </div>-->
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingFour">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour"
                            aria-expanded="false" aria-controls="collapseFour">
                            Entrenador <input type="checkbox">
                        </button>
                    </h5>
                </div>
                <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                    <!--<div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                  </div>-->
                </div>
            </div>

            <div class="card">
                <div class="card-header" id="headingFive">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFive"
                            aria-expanded="false" aria-controls="collapseThree">
                            Tienda <input type="checkbox">
                        </button>
                    </h5>
                </div>
                <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
                    <!--<div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                  </div>-->
                </div>
            </div>

            <div class="card">
                <div class="card-header" id="headingSix">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSix"
                            aria-expanded="false" aria-controls="collapseSix">
                            Resultado acumulado <input type="checkbox">
                        </button>
                    </h5>
                </div>
                <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
                    <!--<div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                  </div>-->
                </div>
            </div>

        </div>
    </div>
</div>
<!-- /#sidebar-wrapper -->

<!-- Page Content -->
<div id="page-content-wrapper">

    <nav class="navbar navbar-expand-lg navbar-light border-bottom" id="nav">
        <button class="btn btn-primary" id="menu-toggle">Filtros</button>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="nav justify-content-center nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">Cruce de indicadores</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                        aria-controls="profile" aria-selected="false">Programas de entrenamiento</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                        aria-controls="contact" aria-selected="false">Lanzamientos y campañas</a>
                </li>
            </ul>
        </div>

    </nav>

    <div class="tab-content" id="myTabContent">

        <!--Cruce de indicadores-->

        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="titulo">
                <h1 style="text-align: center;">Cruce de indicadores</h1>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-xl-4" id="cards">
                        <div class="card mt-3" id="cardsg" style="width: 18rem; height: 18rem;">
                            <div id="chart"></div>
                            <!--<img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">-->
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title  style="background-color: chocolate; width: 100% !important; bottom: 0 !important;"</h5>-->
                                <div class="fincard" style="text-align: center;">
                                    <a href="Grafica.html">Programa 0-90 vs % Rotación</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-xl-4" id="cards">
                        <div class="card mt-3" id="cardsg" style="width: 18rem; height: 18rem;">
                            <div id="chart2"></div>
                            <!--<img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">-->
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" style="text-align: center;">
                                    <a href="Grafica2.html">Programa 0-90 vs Quejas de servicio</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-xl-4" id="cards">
                        <div class="card mt-3" id="cardsg" style="width: 18rem; height: 18rem;">
                            <div id="chart3"></div>
                            <!--<img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">-->
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" style="text-align: center;">
                                    <a href="Grafica3.html">Staff certificado vs % Venta de tiendas cubiertas</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-sm-12 col-xl-4" id="cards">
                        <div class="card mt-3" id="cardsg" style="width: 18rem; height: 18rem;">
                            <div id="chart4"></div>
                            <!--<img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">-->
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" style="text-align: center;">
                                    <a href="Grafica4.html">Curso Norma 251 Certificado curso ICA Champion</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-xl-4" id="cards">
                        <div class="card mt-3" id="cardsg" style="width: 18rem; height: 18rem;">
                            <div id="chart5"></div>
                            <!--<img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">-->
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" style="text-align: center;">
                                    <a href="Grafica5.html">% Cubrimiento campaña de servicio KPI satisfacción del
                                        cliente</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Programas de entrenamiento-->

        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

            <div class="titulo">
                <h1 style="text-align: center;">Programas de entrenamiento</h1>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-4" id="cards">
                        <div class="card mt-3" id="cardsg" style="width: 18rem; height: 18rem;">
                            <!--<div id="chart"></div>-->
                            <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" style="text-align: center;">
                                    <a href="Grafica6.html">Entrenamiento nuevos ingresos inducción</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4" id="cards">
                        <div class="card mt-3" id="cardsg" style="width: 18rem; height: 18rem;">
                            <!--<div id="chart"></div>-->
                            <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" style="text-align: center;">
                                    <a href="Grafica.html">Entrenamiento sentido de pertenencia</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4" id="cards">
                        <div class="card mt-3" id="cardsg" style="width: 18rem; height: 18rem;">
                            <!--<div id="chart"></div>-->
                            <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" style="text-align: center;">
                                    <a href="Grafica.html">Certificación PPP</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-4" id="cards">
                        <div class="card mt-3" id="cardsg" style="width: 18rem; height: 18rem;">
                            <!--<div id="chart"></div>-->
                            <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" style="text-align: center;">
                                    <a href="Grafica.html">Ruta Domino´s</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4" id="cards">
                        <div class="card mt-3" id="cardsg" style="width: 18rem; height: 18rem;">
                            <!--<div id="chart"></div>-->
                            <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" style="text-align: center;">
                                    <a href="Grafica.html">Certificación ERS</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4" id="cards">
                        <div class="card mt-3" id="cardsg" style="width: 18rem; height: 18rem;">
                            <!--<div id="chart"></div>-->
                            <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" style="text-align: center;">
                                    <a href="Grafica.html">Certificación RSC</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-4" id="cards">
                        <div class="card mt-3" id="cardsg" style="width: 18rem; height: 18rem;">
                            <!--<div id="chart"></div>-->
                            <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" style="text-align: center;">
                                    <a href="Grafica.html">Entrenamiento Gerencial Formación de Supervisor</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4" id="cards">
                        <div class="card mt-3" id="cardsg" style="width: 18rem; height: 18rem;">
                            <!--<div id="chart"></div>-->
                            <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" style="text-align: center;">
                                    <a href="Grafica.html">Entrevista básico Norma 251</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4" id="cards">
                        <div class="card mt-3" id="cardsg" style="width: 18rem; height: 18rem;">
                            <!--<div id="chart"></div>-->
                            <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" style="text-align: center;">
                                    <a href="Grafica.html">Formación Subgerente</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-4" id="cards">
                        <div class="card mt-3" id="cardsg" style="width: 18rem; height: 18rem;">
                            <!--<div id="chart"></div>-->
                            <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" style="text-align: center;">
                                    <a href="Grafica.html">Seguridad y salud</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4" id="cards">
                        <div class="card mt-3" id="cardsg" style="width: 18rem; height: 18rem;">
                            <!--<div id="chart"></div>-->
                            <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" style="text-align: center;">
                                    <a href="Grafica.html">Formación gerente</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4" id="cards">
                        <div class="card mt-3" id="cardsg" style="width: 18rem; height: 18rem;">
                            <!--<div id="chart"></div>-->
                            <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" style="text-align: center;">
                                    <a href="Grafica.html">Entrenamiento franquicias Staff general</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Lanzamiento y campañas-->

        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
            <div class="titulo">
                <h1 style="text-align: center;">Lanzamientos y campañas</h1>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-4" id="cards">
                        <div class="card mt-3" id="cardsg" style="width: 18rem; height: 18rem;">
                            <!--<div id="chart"></div>-->
                            <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" style="text-align: center;">
                                    <a href="Grafica.html">Campañas seguridad y salud</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-4" id="cards">
                        <div class="card mt-3" id="cardsg" style="width: 18rem; height: 18rem;">
                            <!-- <div id="chart2"></div> -->
                            <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" style="text-align: center;">
                                    <a href="Grafica.html">Servicio</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-4" id="cards">
                        <div class="card mt-3" id="cardsg" style="width: 18rem; height: 18rem;">
                            <!--<div id="chart"></div>-->
                            <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" style="text-align: center;">
                                    <a href="Grafica.html">Calidad</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-4" id="cards">
                        <div class="card mt-3" id="cardsg" style="width: 18rem; height: 18rem;">
                            <!--<div id="chart"></div>-->
                            <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" style="text-align: center;">
                                    <a href="Grafica.html">Cursos en línea Alsea Anticorrupción</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-4" id="cards">
                        <div class="card mt-3" id="cardsg" style="width: 18rem; height: 18rem;">
                            <!--<div id="chart"></div>-->
                            <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" style="text-align: center;">
                                    <a href="Grafica.html">Privacidad de Datos</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-4" id="cards">
                        <div class="card mt-3" id="cardsg" style="width: 18rem; height: 18rem;">
                            <!--<div id="chart"></div>-->
                            <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" style="text-align: center;">
                                    <a href="Grafica.html">Certificaciones Liderazgo con enfoque humano</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-4" id="cards">
                        <div class="card mt-3" id="cardsg" style="width: 18rem; height: 18rem;">
                            <!--<div id="chart"></div>-->
                            <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" style="text-align: center;">
                                    <a href="Grafica.html">Gerente dueño</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-4" id="cards">
                        <div class="card mt-3" id="cardsg" style="width: 18rem; height: 18rem;">
                            <!--<div id="chart"></div>-->
                            <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" style="text-align: center;">
                                    <a href="Grafica.html">Certificaciones ICA Champion</a>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>


            </div>

        </div>
    </div>



</div>
<!-- Bootstrap core JavaScript -->
<!-- <script src="vendor/jquery/jquery.min.js"></script> -->
<script src="libs/c3.js"></script>
<script src="app.js"></script>
<script src="app2.js"></script>
<script src="app3.js"></script>
<script src="app4.js"></script>
<script src="app5.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Menu Toggle Script -->
<script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
</script>
              <!-- </div>
  <br>
  <br>



</body>

</html> -->


<?php
// echo local_dominosdashboard_format_response($course_elearning);
// echo "<br><br><br>";
// echo local_dominosdashboard_format_response($classroom);
// echo "<br><br><br>";
// echo local_dominosdashboard_format_response($comparison);
// echo "<br><br><br>";

// $result = array();
// $result['status'] = $status;
// $result['courses'] = $count;
// $result['count'] = $data;
// return json_encode($result);

// _print('local_dominosdashboard_get_courses_overview(1)', $course_elearning);
// _print('local_dominosdashboard_get_courses_overview(2)', $classroom);
// _print('local_dominosdashboard_get_courses_overview(3)', $comparison);
// Contenido del dashboard
echo $OUTPUT->footer();
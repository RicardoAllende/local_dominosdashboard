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
// require_login();
global $DB;
$PAGE->set_url($CFG->wwwroot . "/local/dominosdashboard/prueba_chart.php");
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'local_dominosdashboard'));

echo $OUTPUT->header();
echo "<script> var indicadores = '" . DOMINOSDASHBOARD_INDICATORS . "'</script>";

//$courses = local_dominosdashboard_get_courses();

//$indicators = local_dominosdashboard_get_indicators();
?>

<div class="" id="wrapper">

    <!-- Sidebar -->
    <div class="row mr-2" id="sidebar-wrapper">
      <div class="col-lg-3">
            <div id="accordion" align="left">
              <div class="card">
                <div class="card-header cuerpo-filtro" id="headingOne">
                  <h5 class="mb-0">
                    <input type="checkbox"> <button class="btn btn-link collapsed texto-filtro" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      Nacional
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
                <div class="card-header cuerpo-filtro" id="headingTwo">
                  <h5 class="mb-0">
                    <input type="checkbox"><button class="btn btn-link collapsed texto-filtro" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                      Regional
                    </button>
                  </h5>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                  <div class="card-body subgrupo-filtro">
                    <input type="checkbox"> <span class="subfiltro">Centro Norte </span><br>
                    <input type="checkbox"> <span class="subfiltro">Centro Sur </span><br>
                    <input type="checkbox"> <span class="subfiltro">Puebla-Veracruz </span><br>
                    <input type="checkbox"> <span class="subfiltro">Occidente </span><br>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header cuerpo-filtro" id="headingThree">
                  <h5 class="mb-0">
                  <input type="checkbox">  <button class="btn btn-link collapsed texto-filtro" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                      Distrital
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
                <div class="card-header cuerpo-filtro" id="headingFour">
                  <h5 class="mb-0">
                    <input type="checkbox"><button class="btn btn-link collapsed texto-filtro" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                      Entrenador
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
                <div class="card-header cuerpo-filtro" id="headingFive">
                  <h5 class="mb-0">
                    <input type="checkbox"><button class="btn btn-link collapsed texto-filtro" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseThree">
                      Tienda
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
                <div class="card-header cuerpo-filtro" id="headingSix">
                  <h5 class="mb-0">
                  <input type="checkbox">  <button class="btn btn-link collapsed texto-filtro" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                      Resultado acumulado
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

      <div class="col-lg-9" id="page-content-wrapper">


          <div id="navbarSupportedContent">
            <ul class="nav justify-content-center nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active dtag" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Cruce de indicadores</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Programas de entrenamiento</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Lanzamientos y campañas</a>
                </li>
              </ul>
          </div>


           <div class="tab-content" id="myTabContent">

               <!--Cruce de indicadores-->

                <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                   <div class="">
                          <h1 align="center">Cruce de indicadores</h1>
                   </div>
                    <div >
                        <div class="row">
                            <div class="col-sm-12 col-xl-6">
                                <div class="card bg-faded border-0 m-2" id="" >


                                       <div class="card-group">
                                          <div class="card border-0 m-2">
                                            <div class="card-body ">
                                              <p class="card-text text-primary text-center">Aprobados</p>
                                              <p class="card-text text-primary text-center">85%</p>
                                            </div>
                                          </div>
                                          <div class="card border-0 m-2">
                                            <div class="card-body text-center">
                                              <p class="card-text text-warning text-center">No Aprobados</p>
                                              <p class="card-text text-warning text-center">213</p>
                                            </div>
                                          </div>
                                          <div class="card border-0 m-2">
                                            <div class="card-body text-center ">
                                              <p class="card-text text-success text-center">Total de usuarios</p>
                                              <p class="card-text text-success text-center">1850</p>
                                            </div>
                                          </div>

                                        </div>
                                     <!-- <button type="button" class="btn btn-success col-sm-12 col-xl-4" id="texton"> <p class="numero">100</p></button>
                                     <button type="button" class="btn btn-danger col-sm-12 col-xl-4" id="texton"> <p class="numero">50</p></button>
                                     <button type="button" class="btn btn-info col-sm-12 col-xl-4" id="texton"> <p class="numero">150</p></button> -->



                                    <div class="bg-white m-2" id="chart"></div>
                                    <!--<img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">-->
                                    <div class="align-items-end">
                                        <!--<h5 class="card-title">Card title  style="background-color: chocolate; width: 100% !important; bottom: 0 !important;"</h5>-->
                                        <div class="fincard text-center">
                                            <a href="Grafica.html">Programa 0-90 vs % Rotación</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-xl-6">
                                <div class="card bg-gray border-0 m-2" id="" >


                                       <div class="card-group">
                                          <div class="card border-0 m-2">
                                            <div class="card-body text-center">
                                              <p class="card-text text-primary">Aprobados</p>
                                              <p class="card-text text-primary">85%</p>
                                            </div>
                                          </div>
                                          <div class="card border-0 m-2">
                                            <div class="card-body text-center">
                                              <p class="card-text text-warning">No Aprobados</p>
                                              <p class="card-text text-warning">213</p>
                                            </div>
                                          </div>
                                          <div class="card border-0 m-2">
                                            <div class="card-body text-center">
                                              <p class="card-text text-success">Total de usuarios</p>
                                              <p class="card-text text-success">1850</p>
                                            </div>
                                          </div>

                                        </div>
                                     <!-- <button type="button" class="btn btn-success col-sm-12 col-xl-4" id="texton"> <p class="numero">100</p></button>
                                     <button type="button" class="btn btn-danger col-sm-12 col-xl-4" id="texton"> <p class="numero">50</p></button>
                                     <button type="button" class="btn btn-info col-sm-12 col-xl-4" id="texton"> <p class="numero">150</p></button> -->



                                    <div class="chart_ bg-white m-2" id="chart2"></div>
                                    <!--<img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">-->
                                    <div class="align-items-end">
                                        <!--<h5 class="card-title">Card title  style="background-color: chocolate; width: 100% !important; bottom: 0 !important;"</h5>-->
                                        <div class="fincard text-center">
                                            <a href="Grafica.html">Programa 0-90 vs Quejas de servicio</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-12 col-xl-6">
                                <div class="card bg-gray border-0 m-2" id="" >


                                       <div class="card-group">
                                          <div class="card border-0 m-2">
                                            <div class="card-body text-center">
                                              <p class="card-text text-primary">Aprobados</p>
                                              <p class="card-text text-primary">85%</p>
                                            </div>
                                          </div>
                                          <div class="card border-0 m-2">
                                            <div class="card-body text-center">
                                              <p class="card-text text-warning">No Aprobados</p>
                                              <p class="card-text text-warning">213</p>
                                            </div>
                                          </div>
                                          <div class="card border-0 m-2">
                                            <div class="card-body text-center">
                                              <p class="card-text text-success">Total de usuarios</p>
                                              <p class="card-text text-success">1850</p>
                                            </div>
                                          </div>

                                        </div>
                                     <!-- <button type="button" class="btn btn-success col-sm-12 col-xl-4" id="texton"> <p class="numero">100</p></button>
                                     <button type="button" class="btn btn-danger col-sm-12 col-xl-4" id="texton"> <p class="numero">50</p></button>
                                     <button type="button" class="btn btn-info col-sm-12 col-xl-4" id="texton"> <p class="numero">150</p></button> -->



                                    <div class="chart_ bg-white m-2" id="chart3"></div>
                                    <!--<img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">-->
                                    <div class="align-items-end">
                                        <!--<h5 class="card-title">Card title  style="background-color: chocolate; width: 100% !important; bottom: 0 !important;"</h5>-->
                                        <div class="fincard text-center">
                                            <a href="Grafica.html">Staff certificado vs % Venta de tiendas cubiertas</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-xl-6">
                                <div class="card bg-gray border-0 m-2" id="" >


                                       <div class="card-group">
                                          <div class="card border-0 m-2">
                                            <div class="card-body text-center">
                                              <p class="card-text text-primary">Aprobados</p>
                                              <p class="card-text text-primary">85%</p>
                                            </div>
                                          </div>
                                          <div class="card border-0 m-2">
                                            <div class="card-body text-center">
                                              <p class="card-text text-warning">No Aprobados</p>
                                              <p class="card-text text-warning">213</p>
                                            </div>
                                          </div>
                                          <div class="card border-0 m-2">
                                            <div class="card-body text-center">
                                              <p class="card-text text-success">Total de usuarios</p>
                                              <p class="card-text text-success">1850</p>
                                            </div>
                                          </div>

                                        </div>
                                     <!-- <button type="button" class="btn btn-success col-sm-12 col-xl-4" id="texton"> <p class="numero">100</p></button>
                                     <button type="button" class="btn btn-danger col-sm-12 col-xl-4" id="texton"> <p class="numero">50</p></button>
                                     <button type="button" class="btn btn-info col-sm-12 col-xl-4" id="texton"> <p class="numero">150</p></button> -->



                                    <div class="chart_ bg-white m-2" id="chart4"></div>
                                    <!--<img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">-->
                                    <div class="align-items-end">
                                        <!--<h5 class="card-title">Card title  style="background-color: chocolate; width: 100% !important; bottom: 0 !important;"</h5>-->
                                        <div class="fincard text-center">
                                            <a href="Grafica.html">Curso Norma 251 Certificado curso ICA Champion</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-12 col-xl-6">
                                <div class="card bg-gray border-0 m-2" id="" >


                                       <div class="card-group">
                                          <div class="card border-0 m-2">
                                            <div class="card-body text-center">
                                              <p class="card-text text-primary">Aprobados</p>
                                              <p class="card-text text-primary">85%</p>
                                            </div>
                                          </div>
                                          <div class="card border-0 m-2">
                                            <div class="card-body text-center">
                                              <p class="card-text text-warning">No Aprobados</p>
                                              <p class="card-text text-warning">213</p>
                                            </div>
                                          </div>
                                          <div class="card border-0 m-2">
                                            <div class="card-body text-center">
                                              <p class="card-text text-success">Total de usuarios</p>
                                              <p class="card-text text-success">1850</p>
                                            </div>
                                          </div>

                                        </div>
                                     <!-- <button type="button" class="btn btn-success col-sm-12 col-xl-4" id="texton"> <p class="numero">100</p></button>
                                     <button type="button" class="btn btn-danger col-sm-12 col-xl-4" id="texton"> <p class="numero">50</p></button>
                                     <button type="button" class="btn btn-info col-sm-12 col-xl-4" id="texton"> <p class="numero">150</p></button> -->



                                    <div class="chart_ bg-white m-2" id="chart5"></div>
                                    <!--<img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">-->
                                    <div class="align-items-end">
                                        <!--<h5 class="card-title">Card title  style="background-color: chocolate; width: 100% !important; bottom: 0 !important;"</h5>-->
                                        <div class="fincard text-center">
                                            <a href="Grafica.html">% Cubrimiento campaña de servicio KPI satisfacción del cliente</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          </div>

                    </div>


                </div>

                <!--Programas de entrenamiento-->

                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                   <div class="">
                          <h1 align="center">Programas de entrenamiento</h1>
                   </div>
                    <div >
                       <div class="row">
                       <div class="col-6" id="cards">
                                <div class="card mt-3" id="" style="width: 18rem height: 18rem;">
                                <!--<div id="chart"></div>-->
                                <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                              <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" align="center">
                                <a href="Grafica6.html">Entrenamiento nuevos ingresos inducción</a>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="col-6" id="cards">
                            <div class="card mt-3" id="" style="width: 18rem height: 18rem;">
                                <!--<div id="chart"></div>-->
                                <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                              <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" align="center">
                                   <a href="Grafica.html">Entrenamiento sentido de pertenencia</a>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="col-4" id="cards">
                            <div class="card mt-3" id="" style="width: 18rem height: 18rem;">
                                <!--<div id="chart"></div>-->
                                <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" align="center">
                                   <a href="Grafica.html">Certificación PPP</a>
                                </div>
                              </div>
                            </div>
                        </div>
                        </div>

                        <div class="row">
                            <div class="col-6" id="cards">
                            <div class="card mt-3" id="" style="width: 18rem height: 18rem;">
                                <!--<div id="chart"></div>-->
                                <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                              <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" align="center">
                                <a href="Grafica.html">Ruta Domino´s</a>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="col-6" id="cards">
                            <div class="card mt-3" id="" style="width: 18rem height: 18rem;">
                                <!--<div id="chart"></div>-->
                                <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                                <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" align="center">
                                   <a href="Grafica.html">Certificación ERS</a>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="col-6" id="cards">
                            <div class="card mt-3" id="" style="width: 18rem height: 18rem;">
                                <!--<div id="chart"></div>-->
                                <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" align="center">
                                   <a href="Grafica.html">Certificación RSC</a>
                                </div>
                              </div>
                            </div>
                        </div>
                        </div>

                        <div class="row">
                       <div class="col-6" id="cards">
                            <div class="card mt-3" id="" style="width: 18rem height: 18rem;">
                                <!--<div id="chart"></div>-->
                                <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                              <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" align="center">
                                <a href="Grafica.html">Entrenamiento Gerencial Formación de Supervisor</a>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="col-6" id="cards">
                            <div class="card mt-3" id="" style="width: 18rem height: 18rem;">
                                <!--<div id="chart"></div>-->
                                <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                              <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" align="center">
                                   <a href="Grafica.html">Entrevista básico Norma 251</a>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="col-6" id="cards">
                            <div class="card mt-3" id="" style="width: 18rem height: 18rem;">
                                <!--<div id="chart"></div>-->
                                <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" align="center">
                                   <a href="Grafica.html">Formación Subgerente</a>
                                </div>
                              </div>
                            </div>
                        </div>
                        </div>

                        <div class="row">
                       <div class="col-6" id="cards">
                            <div class="card mt-3" id="" style="width: 18rem height: 18rem;">
                                <!--<div id="chart"></div>-->
                                <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                              <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" align="center">
                                <a href="Grafica.html">Seguridad y salud</a>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="col-6" id="cards">
                            <div class="card mt-3" id="" style="width: 18rem height: 18rem;">
                                <!--<div id="chart"></div>-->
                                <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                              <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" align="center">
                                   <a href="Grafica.html">Formación gerente</a>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="col-4" id="cards">
                            <div class="card mt-3" id="" style="width: 18rem height: 18rem;">
                               <!--<div id="chart"></div>-->
                                <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                                <div class="align-items-end">
                                    <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" align="center">
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
                   <div class="">
                          <h1 align="center">Lanzamientos y campañas</h1>
                   </div>

                    <div >
                       <div class="row">
                       <div class="col-6" id="cards">
                            <div class="card mt-3" id="" style="width: 18rem height: 18rem;">
                                <!--<div id="chart"></div>-->
                                <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                              <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" align="center">
                                <a href="Grafica.html">Campañas seguridad y salud</a>
                                </div>
                              </div>
                            </div>
                        </div>

                        <div class="col-6" id="cards">
                            <div class="card mt-3" id="" style="width: 18rem height: 18rem;">
                               <!-- <div id="chart2"></div> -->
                                <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                              <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" align="center">
                                   <a href="Grafica.html">Servicio</a>
                                </div>
                              </div>
                            </div>
                        </div>

                        <div class="col-4" id="cards">
                            <div class="card mt-3" id="" style="width: 18rem height: 18rem;">
                                <!--<div id="chart"></div>-->
                                <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" align="center">
                                   <a href="Grafica.html">Calidad</a>
                                </div>
                              </div>
                            </div>
                        </div>

                        </div>

                        <div class="row">
                       <div class="col-6" id="cards">
                            <div class="card mt-3" id="" style="width: 18rem height: 18rem;">
                                <!--<div id="chart"></div>-->
                                <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                              <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" align="center">
                                <a href="Grafica.html">Cursos en línea Alsea Anticorrupción</a>
                                </div>
                              </div>
                            </div>
                        </div>

                        <div class="col-6" id="cards">
                            <div class="card mt-3" id="" style="width: 18rem height: 18rem;">
                                <!--<div id="chart"></div>-->
                                <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                              <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" align="center">
                                   <a href="Grafica.html">Privacidad de Datos</a>
                                </div>
                              </div>
                            </div>
                        </div>

                        <div class="col-6" id="cards">
                            <div class="card mt-3" id="" style="width: 18rem height: 18rem;">
                                <!--<div id="chart"></div>-->
                                <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                            <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" align="center">
                                   <a href="Grafica.html">Certificaciones Liderazgo con enfoque humano</a>
                                </div>
                              </div>
                            </div>
                        </div>

                        </div>

                        <div class="row">
                       <div class="col-4" id="cards">
                            <div class="card mt-3" id="" style="width: 18rem height: 18rem;">
                                <!--<div id="chart"></div>-->
                                <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                              <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" align="center">
                                <a href="Grafica.html">Gerente dueño</a>
                                </div>
                              </div>
                            </div>
                        </div>

                        <div class="col-6" id="cards">
                            <div class="card mt-3" id="" style="width: 18rem height: 18rem;">
                                <!--<div id="chart"></div>-->
                                <img class="card-img-top" src="img/graficos-p.jpg" alt="Card image cap">
                              <div class="align-items-end">
                                <!--<h5 class="card-title">Card title</h5>-->
                                <div class="fincard" align="center">
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
    </div>


</div>


<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<link href="estilos.css" rel="stylesheet">

<link href="libs/c3.css" rel="stylesheet">
<script src="//d3js.org/d3.v3.min.js" charset="utf-8"></script>
<script src="libs/c3.js"></script>

<script>

    $('.chart_').each(function(){
        // console.log($(this).attr('id'));
        generarGraficaEstandar('#' + $(this).attr('id'));
    });
    var chart;
    function generarGraficaEstandar(id){
        return chart = c3.generate({
            bindto: id,
            data: {
                columns: [
                    ['data1', 30, 200, 100, 400, 150, 250],
                    ['data2', 50, 20, 10, 40, 15, 25]
                ]
            }
        });
    }
</script>     
    <script src="app.js"></script>

<?php
echo $OUTPUT->footer();

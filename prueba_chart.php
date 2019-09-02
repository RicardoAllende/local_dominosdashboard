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
            <div id="contenedor_filtros" align="left">
                
            <div class="card">
                <div class="card-header cuerpo-filtro" id="indicatorheading0">
                    <h5 class="mb-0">
                        <input type="checkbox"><button class="btn btn-link collapsed texto-filtro"
                            data-toggle="collapse" data-target="#collapse_0" aria-expanded="false"
                            aria-controls="collapse_0">
                            regiones
                        </button>
                    </h5>
                </div>
                <div id="collapse_0" class="collapse" aria-labelledby="indicatorheading0" data-parent="#contenedor_filtros">
                    <div class="card-body subgrupo-filtro">
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'></span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>Centro Norte</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CENTRO SUR</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>OCCIDENTE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>NOROESRE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>NORTE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SURESTE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PUEBLA-VERACRUZ</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>BAJIO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>Barranca del muerto</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>Tijuana</span><br>
            
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header cuerpo-filtro" id="indicatorheading1">
                    <h5 class="mb-0">
                        <input type="checkbox"><button class="btn btn-link collapsed texto-filtro"
                            data-toggle="collapse" data-target="#collapse_1" aria-expanded="false"
                            aria-controls="collapse_1">
                            distritos
                        </button>
                    </h5>
                </div>
                <div id="collapse_1" class="collapse" aria-labelledby="indicatorheading1" data-parent="#contenedor_filtros">
                    <div class="card-body subgrupo-filtro">
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header cuerpo-filtro" id="indicatorheading2">
                    <h5 class="mb-0">
                        <input type="checkbox"><button class="btn btn-link collapsed texto-filtro"
                            data-toggle="collapse" data-target="#collapse_2" aria-expanded="false"
                            aria-controls="collapse_2">
                            entrenadores
                        </button>
                    </h5>
                </div>
                <div id="collapse_2" class="collapse" aria-labelledby="indicatorheading2" data-parent="#contenedor_filtros">
                    <div class="card-body subgrupo-filtro">
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header cuerpo-filtro" id="indicatorheading3">
                    <h5 class="mb-0">
                        <input type="checkbox"><button class="btn btn-link collapsed texto-filtro"
                            data-toggle="collapse" data-target="#collapse_3" aria-expanded="false"
                            aria-controls="collapse_3">
                            tiendas
                        </button>
                    </h5>
                </div>
                <div id="collapse_3" class="collapse" aria-labelledby="indicatorheading3" data-parent="#contenedor_filtros">
                    <div class="card-body subgrupo-filtro">
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'></span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SAN ANGEL</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>VISTA HERMOSA CUERNAVACA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CAFETALES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>GUADALUPE INN</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>COSTA AZUL</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>BOSQUES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PEDREGAL</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CAMPESTRE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>NAPOLES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>VISTA HERMOSA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>AGUILAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>COPILCO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ACAPATZINGO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>DIVISION DEL NORTE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>MAGALLANES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ACOXPA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SAN JERONIMO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TLALTENANGO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PERIFERICO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>NARVARTE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>MIRAMONTES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TOLUCA PILARES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PLUTARCO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>INSURGENTES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>OLIVAR DE LOS PADRES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TEPEPAN</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TLAHUAC</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CIVAC</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>DEL VALLE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CUAUTLA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SANTA FE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>IZTAPALAPA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SANTA LUCIA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CHILPANCINGO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>XOCHIMILCO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>IGUALA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TOLUCA INDEPENDENCIA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>POPOCATEPETL</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CONTRERAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>OBSERVATORIO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>VOLCANES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ZAPOTITLAN</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>AJUSCO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CARREFOUR CUERNAVACA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TEMIXCO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PROGRESO (CIMA)</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>LERMA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>RENACIMIENTO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>INTERLOMAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ALFONSO XIII</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ACAPULCO CENTRO CONSTITUYENTES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PLAZA DIAMANTE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>AEROPUERTO TOLUCA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ZITACUARO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CUAJIMALPA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SANTA ANA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TECNOLOGICO METEPEC</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SAN ANDRES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CUERNAVACA CENTRO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>AZTECAS SUR</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>JESUS DEL MONTE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TORRE DIAMANTE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ACAPULCO COSTA AZUL</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>WALMART PLATEROS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>MEGA CUAUTLA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>IZTAPALAPA II</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>JOSE MARIA CASTORENA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>WM FELIX CUEVAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PUEBLO SANTA FE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PATIO ACAPULCO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PLAZA TENARIA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PLAZA LAS PALMAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>WTC</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>MADRE SELVA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>LOMAS DE CUERNAVACA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PLAZA EXHIBIMEX</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>FUENTES DEL PEDREGAL</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>AVENIDA UNIVERSIDAD</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CALETA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>GUADALUPE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>VERSALLES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PROVIDENCIA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>RESIDENCIAL VICTORIA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CAMELINAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CENTRO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>MADERO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ALCALDE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TEPIC</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>COLIMA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>WALL MART</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>REVOLUCION</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>JARDIN REAL</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>MANZANILLO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PLAZA LAS LOMAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>MOROLEON</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CHAPULTEPEC</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ZAMORA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>URUAPAN</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PASEOS DEL SOL MARIANO OTERO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CIRCUNVALACION</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>AJIJIC (PLAZA LA FLORESTA)</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PIPILA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>EL PITILLAL (VALLARTA)</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>LIBRAMIENTO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>AVILA CAMACHO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>COLON</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>NUEVO VALLARTA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>EL PALOMAR</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SAN ISIDRO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TONALA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>OBLATOS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CIUDAD GUZMAN</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>GOBERNADOR CURIEL</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>LAS JUNTAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>JAVIER MINA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>FRANCISCO VILLA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PLAZA INDEPENDENCIA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ACUEDUCTO PUERTA DE HIERRO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SAUCES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TLAJOMULCO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TEPIC SHOPPING CENTER</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ZAPOPAN REAL CENTER</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TESISTAN PLAZA LOS ROBLES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ESPACIO TLAQUEPAQUE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>MACROPLAZA ESTADIO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PALOMAR II</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>AV GUADALUPE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PLAZA AGORA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>VILLA DE ALVAREZ</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TLAQUEPAQUE CENTRO SUR</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>RIO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PALMAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ENSENADA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SOLER</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PLAYAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>OTAY</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>REVOLUCION  TIJUANA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SIMON BOLIVAR</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>FUNDADORES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ROSARITO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>GATO BRONCO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>VALLE PONIENTE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CUMBRES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SALTILLO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>VALLE ORIENTE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ANAHUAC</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>OCAMPO CENTRO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>AZTLAN</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ESCOBEDO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>LOURDES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ECONOMAX</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CHAPULTEPEC HEB CHAPULTEPEC</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SANTO DOMINGO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SANTA MARIA SORIANA SANTA MARIA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>LAS PALMAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SAN MIGUEL</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TECNOLOGICO MONTERREY</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>LAS QUINTAS SORIANA LAS QUINTAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SORIANA COSS SALTILLO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>GIGANTE LINCOLN</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>APODACA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SANTA CATARINA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SALTILLO FUNDADORES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SOLIDARIDAD</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SAN JERONIMO MTY</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SANTA CRUZ</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>APODACA II</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CARRETERA NACIONAL</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CHURUBUSCO MTY</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SOLIDARIDAD II</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>GARZA SADA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>MIRASIERRA SALTILLO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>VALLE SOLEADO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PASEO DE CUMBRES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>MITRAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SENDERO APODACA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SENDERO SUR</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SALTILLO 2000</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>VALLE LINCOLN</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PLAZA BELLA HUINALA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>COBA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>QUETZAL PLAZA QUETZAL</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ROYAL MAYAN</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>GARCIA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CHETUMAL</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CAMPECHE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CIUDAD DEL CARMEN</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CENTRO 2 PORTILLO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PLAZA ESMERALDA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PLAYA DEL CARMEN</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>FIESTA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>COZUMEL PLAZA CARIBE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>DEL RIO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SAN FRANCISCO CIUDAD DEL CARMEN 2</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>VALLADOLID</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CAMPO MILITAR</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ITZAES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>FRANCISCO DE MONTEJO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CHETUMAL 2</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>BONFIL</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PROGRESO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CHEDRAUI CANCUN</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>LA LUNA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CD DEL CARMEN III</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CHETUMAL III</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>MERIDA ORIENTE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PLAZA LAS AMERICAS II</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PLAYA DEL CARMEN II</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>MERIDA SUR</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>FELIPE CARRILLO PUERTO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>VILLAGE KABAH</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PARAISO MAYA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PLAYA LAS AMERICAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CD. DEL CARMEN IV</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CITY CENTER MERIDA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>VILLAMARINO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CAUCEL</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>KABAH II</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ARCO NORTE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>VILLAS DEL MAR</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CAN CUN 2000 PLAZA CANCUN 2000</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PUERTO CANCUN</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PUBLIC MARKET</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>JUAN PABLO II</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>LAS AMERICAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TECAMACHALCO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>POLANCO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PRADO NORTE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SANTA CRUZ DEL MONTE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>GUSTAVO BAZ</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>LOMAS VERDES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CONDESA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ATIZAPAN</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ZONA ROSA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ANZURES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>HERRADURA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>POLITECNICO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PERINORTE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>MELCHOR OCAMPO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TLATELOLCO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>JARDIN BALBUENA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>AZCAPOTZALCO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ARAGON</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TEPEYAC</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CHURUBUSCO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TLALNEPANTLA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>COACALCO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ROSARIO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ECATEPEC</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TOREO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>IZCALLI</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TEXCOCO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>EDUARDO MOLINA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>LAGO DE GUADALUPE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CUAUTITLAN II</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TULTITLAN</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TICOMAN</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>VALLEJO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ZONA ESMERALDA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PLAZA ORIENTE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>NAUCALPAN</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>LEGARIA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CIUDAD AZTECA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>VIA MORELOS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ROMA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CUAUTITLAN III</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>COACALCO II</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>W.M.ROJO GOMEZ</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PRADO VALLEJO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>VILLA NICOLAS ROMERO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PALOMAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>QUINIENTAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>EDISON</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>OJO DE AGUA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ARBOLEDAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>OBRERA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>XALOSTOC</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>AZTECAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PLAZA SAN MARCOS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>GRANJAS GUADALUPE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>AMERICAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TECAMAC</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CONTINENTES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ZUMPANGO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>MULTIPLAZA ARAGON II</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>IZCALLI SUR</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PATIO CLAVERIA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SAN MATEO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SENDERO ECATEPEC</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PARQUES POLANCO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CERVANTES SAAVEDRA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SANTA CECILIA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PATIO TEXCOCO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CORREGIDORA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TACUBA CENTRO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PLAZA AEROPUERTO (TIENDA EXPRES)</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>MULTIPLAZA ALAMEDAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SANTA MARIA LA RIBERA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PLAZA AHUEHUETES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>JAMAICA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>GALERIAS ATIZAPAN</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PLAZA CUAUHTEMOC</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>GRAND PLAZA ECATEPEC</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SAN MIGUEL IZCALLI</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>LA VILLA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>MIRADOR</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>RUIZ CORTINES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PACHUCA I</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ANIMAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CORDOBA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>FUERTES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>LIBANES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TEHUACAN</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>XALAPA 1</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ORIZABA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>NEZAHUALCOYOTL</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TULANCINGO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>EL SALADO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CHALCO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TLAXCALA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>XALAPA 2</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CHOLULA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>APIZACO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>POZA RICA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>VERACRUZ</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PACHUCA II</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>LOS REYES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TUXPAN</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>MAYORAZGO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>IZTAPALUCA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CAPU</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>EL COYOL VERACRUZ</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SAN MARTIN  TEXMELUCAN</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>XONACA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>MARGARITAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>BOCA DEL RIO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PANTITLAN IV</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PACHUCA III</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ONCE Y ONCE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>MARTINEZ DE LA TORRE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>VALSEQUILLO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ATLIXCO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>FLORESTA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>POZA RICA II</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TULA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>RIO MEDIO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>NEZAHUALCOYOTL II</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PUEBLA ANGELOPOLIS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SAN SEBASTIAN</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>IXTAPALUCA II</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PARQUE INDUSTRIAL</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CHOLULA 2</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TEPEJI DEL RIO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>XALAPA 5</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TEHUACAN II</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TIZAYUCA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>AYOTLA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>NUEVO VERACRUZ</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TEZIUTLAN</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ADAGIO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>FORTIN DE LAS FLORES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>VIA SAN JUAN</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>DIAZ MIRON</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PATIO AYOTLA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TLAXCALA CENTRO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SORIANA CHAPULTEPEC</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PLAZA CIUDAD CENTRAL</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>XALAPA 3</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CRUZ DEL SUR</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PLAZA CENTRO SUR</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>MUNDO FUTBOL</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>BORDO DE XOCHIACA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>16 DE SEPTIEMBRE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>BLVD. 5 DE MAYO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CENTRAL DE ABASTOS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>RIO BLANCO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>MALECON</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CONSTITUYENTES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CELAYA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ZACATECAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>FRESNILLO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>QUERETARO II</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SAN JUAN DEL RIO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PLAZA MAYOR</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SIGLO XXI</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>SAN MIGUEL DE ALLENDE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CARREFOUR</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>JURICA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CELAYA II</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>AGUASCALIENTES INEGI</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>GUADALUPE ZACATECAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>LEON 4 ARBIDE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>QUERETARO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>AMSTERDAM</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>JURIQUILLA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CELAYA III</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PLAZA LAS FUENTES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>BERNARDO QUINTANA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>FRAY JUNIPERO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CIUDAD DEL SOL</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PLAZA LUCIERNAGA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>UBIKA UNIVERSIDAD</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>DETECCION DE TALENTO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>WAL-MART TLAHUAC</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>GALERIAS CUERNAVACA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PERISUR</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CENTRO COMERCIAL SANTA FE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PASEO ACOXPA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>GALERIAS TOLUCA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PORTAL SAN ANGEL</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>LAS ANTENAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>GALERIAS GUADALAJARA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>GALERIAS VALLARTA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>GUADALAJARA CENTRO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CENTRO MORELOS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PLAZA MEXICO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>GALERIAS CAMPECHE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>MULTIPLAZA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CENTRO MEDICO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>METRO TAXQUENA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>METRO PINO SUAREZ</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>METRO LA RAZA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ESTADIO AZTECA EXPRESS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>INDIOS VERDES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>METRO TACUBAYA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>MUNDO E</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>TACUBA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>METRO CHABACANO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PARQUE DELTA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PARQUE LINDAVISTA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PARQUE TEZONTLE</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>REFORMA 222</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>METRO 4 CAMINOS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>METRO BALDERAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>CENTRO COMERCIAL EL ROSARIO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PARQUE VIA VALLEJO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PARQUE TOREO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PORTALES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>OUTLET</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>GALERIAS PACHUCA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>AMERICAS XALAPA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ZOCALO PUEBLA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>GALERIAS HERMANO SERDAN</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PARQUE PUEBLA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>LEON CENTRO MAX</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ALTARIA AGUASCALIENTES</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>GALERIAS ZACATECAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>LA GRAN PLAZA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>BUGAMBILIAS</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>AMERICAS ECATEPEC</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>ANTEA</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>UBIKA MILENIO</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>PLAZA LA CUSPIDE</span><br>
            
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header cuerpo-filtro" id="indicatorheading4">
                    <h5 class="mb-0">
                        <input type="checkbox"><button class="btn btn-link collapsed texto-filtro"
                            data-toggle="collapse" data-target="#collapse_4" aria-expanded="false"
                            aria-controls="collapse_4">
                            puestos
                        </button>
                    </h5>
                </div>
                <div id="collapse_4" class="collapse" aria-labelledby="indicatorheading4" data-parent="#contenedor_filtros">
                    <div class="card-body subgrupo-filtro">
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'></span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>Coordinador de entrenamiento</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>Gerente de Entrenamiento</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>Especialista en Reparto Seguro</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>Jefe de E.R.S.</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>Gerente de Tienda</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>Preparador de Pizzas Perfectas</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>Preparador de Pizzas Perfectas JR32</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>Jefe de PPP</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>Representante de Servicio al Cliente</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>Representante de Servicio al Cliente J24</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>Representante de Servicio al Cliente J32</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>Subgerente de Tienda</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>Supervisor de Turno</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>Ers</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>Asistente regional</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>Director de mercadotecnia</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>Director regional operaciones</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>Coordinador de mercadotecnia</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>Gerente de distrito (supervisor)</span><br>
            
                        <input type="checkbox"> <span class="subfiltro" style='color: black;'>Gerente sr. De mercadotecnia</span><br>
            
                    </div>
                </div>
            </div>
            <div class="card">
                    <div class="card-header cuerpo-filtro" id="headingThree">
                        <h5 class="mb-0">
                            <input type="checkbox"> <button class="btn btn-link collapsed texto-filtro"
                                data-toggle="collapse" data-target="#collapseThree" aria-expanded="false"
                                aria-controls="collapseThree">
                                Distrital
                            </button>
                        </h5>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#contenedor_filtros">
                        <!--<div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                  </div>-->
                    </div>
                </div>
                <div class="card">
                    <div class="card-header cuerpo-filtro" id="headingFour">
                        <h5 class="mb-0">
                            <input type="checkbox"><button class="btn btn-link collapsed texto-filtro"
                                data-toggle="collapse" data-target="#collapseFour" aria-expanded="false"
                                aria-controls="collapseFour">
                                Entrenador
                            </button>
                        </h5>
                    </div>
                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#contenedor_filtros">
                        <!--<div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                  </div>-->
                    </div>
                </div>

                <div class="card">
                    <div class="card-header cuerpo-filtro" id="headingFive">
                        <h5 class="mb-0">
                            <input type="checkbox"><button class="btn btn-link collapsed texto-filtro"
                                data-toggle="collapse" data-target="#collapseFive" aria-expanded="false"
                                aria-controls="collapseThree">
                                Tienda
                            </button>
                        </h5>
                    </div>
                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#contenedor_filtros">
                        <!--<div class="card-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                  </div>-->
                    </div>
                </div>

                <div class="card">
                    <div class="card-header cuerpo-filtro" id="headingSix">
                        <h5 class="mb-0">
                            <input type="checkbox"> <button class="btn btn-link collapsed texto-filtro"
                                data-toggle="collapse" data-target="#collapseSix" aria-expanded="false"
                                aria-controls="collapseSix">
                                Resultado acumulado
                            </button>
                        </h5>
                    </div>
                    <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#contenedor_filtros">
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
                        <a class="nav-link active dtag" id="home-tab" data-toggle="tab" href="#home" role="tab"
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


            <div class="tab-content" id="myTabContent">

                <!--Cruce de indicadores-->

                <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="">
                        <h1 align="center">Cruce de indicadores</h1>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-sm-12 col-xl-6">
                                <div class="card bg-faded border-0 m-2" id="">


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
                                <div class="card bg-gray border-0 m-2" id="">


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
                                <div class="card bg-gray border-0 m-2" id="">


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
                                <div class="card bg-gray border-0 m-2" id="">


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
                                <div class="card bg-gray border-0 m-2" id="">


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
                                            <a href="Grafica.html">% Cubrimiento campaña de servicio KPI satisfacción
                                                del cliente</a>
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
                    <div>
                        <div class="row">
                            <div class="col-6" id="cards">
                                <div class="card mt-3" id="" style="width: 18rem height: 18rem;">

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

                    <div>
                        <div class="row">
                            <div class="col-6" id="cards">
                                <div class="card mt-3" id="" style="width: 18rem height: 18rem;">

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
<script src="dominosdashboard_scripts.js"></script>

<script>

    addCharta(); // Medio círculo
    addChartb(); // Medio círculo igual
    addChartc(); // Barras
    addChartd(); // Barras igual
    addChartz(); // Puntos // Esta se utilizará

    function addChartc() {
        var chartc = c3.generate({
            data: {
                columns: [
                    ['Aprobado', 40],
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

    function addChartz() {
        var chartz = c3.generate({
            data: {
                columns: [
                    ['Aprobado', 30, 200, 100, 400, 150, 250],
                    ['No Aprobado', 100, 100, 140, 200, 150, 50],
                    ['Destacado', 140, 500, 240, 100, 350, 90]
                ],
                type: ''
            },
            bindto: "#chart2",
            tooltip: {
                format: {
                    title: function (d) { return 'Curso ' + d; },
                    value: function (value, ratio, id) {
                        var format = id === 'data1' ? d3.format(',') : d3.format('');
                        return format(value);
                    }

                }
            }
        });
    }

    function addCharta() {
        var charta = c3.generate({
            data: {
                columns: [
                    ['Aprobados', 30]

                ],
                colors: {
                    Aprobados: '#0e4bef'
                },
                type: 'gauge'
            },
            bindto: "#chart3",
            tooltip: {
                format: {
                    title: function (d) { return 'Aprobados '; },



                }
            }
        });
    }

    function addChartb() {
        var chartb = c3.generate({
            data: {
                columns: [
                    ['No_Aprobados', 70]

                ],
                colors: {
                    No_Aprobados: '#ffff00'
                },
                type: 'gauge'
            },
            bindto: "#chart4",
            tooltip: {
                format: {
                    title: function (d) { return 'Aprobados '; },



                }
            }
        });
    }

    function addChartd() {
        var chartd = c3.generate({
            data: {
                columns: [
                    ['Quejas', 30],
                    ['Porcentaje', 130]
                ],
                type: 'bar'
            },
            bindto: "#chart5",
            tooltip: {
                format: {
                    title: function (d) { return 'Quejas de servicio'; },
                    value: function (value, ratio, id) {
                        var format = id === 'data1' ? d3.format(',') : d3.format('');
                        return format(value);
                    }

                }
            }
        });
    }

</script>
<!-- <script src="app.js"></script> -->

<?php
echo $OUTPUT->footer();

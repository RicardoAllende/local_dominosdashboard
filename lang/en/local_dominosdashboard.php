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
 * @category    string
 * @copyright   2019 Subitus <contacto@subitus.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Domino\'s Dahsboard';
$string['dashboardtitle'] = 'Domino\'s Dahsboard';
$string['categoriestab'] = 'Categoría padre';
$string['upload_kpi'] = "Subir KPI";

$string['coursetypestab'] = 'Clasificación de cursos';

$string['tab_filtros'] = 'Filtros';
$string['tab_courses'] = 'Cursos';
$string['tab_kpis'] = 'Configuración de KPI\'S';
$string['tab_admins'] = 'Usuarios con acceso';

$string['ideal_rotacion'] = 'Ideal de rotación (0-100)';
$string['ideal_rotacion_desc'] = 'Introduzca el ideal de rotación de referencia para el KPI';

$string['ideal_cobertura'] = 'Ideal de cobertura (0-100)';
$string['ideal_cobertura_desc'] = 'Introduzca el ideal de cobertura de referencia para el KPI';

$string['parent_category'] = 'Seleccione la categoría padre';
$string['parent_category_desc'] = 'A partir de esta categoría se listarán los cursos, si elige miscellaneous, se listarán todos los cursos.<br><strong>Después de elegir la categoría padre, por favor haga clic en guardar para cargar los cursos correspondientes.</strong>';

$string['completion_mode'] = 'Modo de finalización';
$string['completion_mode_desc'] = 'Elegir la forma en la que el curso debe estar finalizado';
$string['kpi_relation'] = 'KPI';
$string['kpi_relation_desc'] = 'Seleccione los cursos con los que se relacionará este KPI, para seleccionar varios utilice ctrl';
$string['kpi_select'] = 'KPI';
$string['kpi_select_desc'] = 'Seleccione el tipo de KPI que está subiendo';

$string['filtro'] = 'Campo ';
$string['filtro_desc'] = 'Campo de perfil de donde se obtendrá el catálogo ';

$string['course_minimum_score'] = 'Calificación mínima aprobatoria';
$string['course_minimum_score_desc'] = 'Ingrese la calificación mínima aprobatoria para aprobar el curso (Tome en cuenta la calificación máxima configurada)';

$string['course_grade_activity_completion'] = 'Actividad ';
$string['course_grade_activity_completion_desc'] = 'Seleccione la actividad por la cual el curso está terminado';

$string['badge_completion'] = 'Insignia necesaria para completar el curso';
$string['badge_completion_desc'] = 'Seleccione la insignia que significa que el curso está terminado';

$string['chart'] = 'Tipo de gráfica en inicio';
$string['chart_desc'] = 'Seleccione el tipo de gráfica con el que se mostrará en el dashboard';

$string['course_main_chart_color'] = 'Color principal de gráfica en inicio';
$string['course_main_chart_color_desc'] = 'Seleccione el color principal que tendrá la gráfica con el que se mostrará este curso en el dashboard';

$string['LOCALDOMINOSDASHBOARD_PROGRAMAS_ENTRENAMIENTO'] = 'Seleccione los programas de entrenamiento';
$string['LOCALDOMINOSDASHBOARD_PROGRAMAS_ENTRENAMIENTO_desc'] = 'Presione la tecla ctrl para seleccionar múltiples elementos';

$string['LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS'] = 'Seleccione los cursos de la pestaña "Lanzamientos y campañas"';
$string['LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS_desc'] = 'Presione la tecla ctrl para seleccionar múltiples elementos, los cursos que no seleccione aparecerán en la pestaña "Programas de entrenamiento"';

$string['excluded_courses'] = 'Seleccione los cursos para excluir en los reportes';
$string['excluded_courses_desc'] = 'Presione la tecla ctrl para seleccionar múltiples elementos';

$string['course_completion_activity'] = 'Actividad que finaliza el curso';
$string['course_completion_activity_desc'] = 'Cuál actividad finaliza el curso';

$string['allowed_email_addresses_in_course']        = 'Terminación de correo electrónico';
$string['allowed_email_addresses_in_course_desc']   = 'El correo electrónico debe contener el texto para ser considerado dentro de las inscripciones';

$string['allowed_email_admins']        = 'Correos electrónicos';
$string['allowed_email_admins_desc']   = 'Escriba el correo electrónico de los usuarios que tendrán acceso al dashboard. <br> <strong>Para escribir distintos correos, utilice como separador un espacio</strong>';

$string['uploadkpis'] = "Subir KPI's";
$string['uploadkpis_help'] = "Usted debe agregar los archivos KPI's con el formato adecuado

* Para dar formato al archivo, abra el archivo excel y seleccione la opción Guardar como (en windows es con la tecla F12), busque la pestaña herramientas (está cerca del botón Guardar)

* Dentro de herramientas dar clic a Opciones web y buscar la pestaña codificación. En la opción Guardar este documento como seleccione 'Unicode' y después aceptar los cambios

* Después de ello ubicarse en la hoja que posee el KPI y guardar como csv.

* El paso final es limpiar las columnas iniciales que estén vacías y las columnas al inicio que no tengan valores y guardar como CSV.

";

$string['crontask_name'] = 'Creación de consultas para generación de históricos';

$string['course_details_title'] = 'Detalle del curso: ';

$string['seccion_a'] = 'Comparativa de aprovechamiento de cursos';
$string['seccion_a_desc'] = 'Contendra la informacion de avance de todos los cursos dividido por region';

$string['seccion_b'] = 'Programa de entrenamiento de nuevo ingreso';
$string['seccion_b_desc'] = 'Contendra la información de los cursos definidos por el administrador del dashboard (por el momento solo es nom 251 y curso de induccion), este tambien respondera a los filtros y mostrara la información de avance dividido por curso configurado en la seccion';

$string['seccion_c'] = 'Ruta dominos';
$string['seccion_c_desc'] = 'Contendra un comparativo de regiones, dividido por cursos definidos, responderá a los filtros de regiones';

$string['seccion_d'] = 'Programas de entrenamiento temporal';
$string['seccion_d_desc'] = 'Contendra la información de aprovechamiento (usuarios inscritos, usuarios aprobados y usuarios no aprobados) de los cursos seleccionados definidos por el usuario administrador y responderan a todos los filtros';
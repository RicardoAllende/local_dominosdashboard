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
// $string['kpi_relation_desc'] = 'Elija los KPI con los que se comparará este curso, para seleccionar varios utilice ctrl';
$string['kpi_relation_desc'] = 'Elija los cursos con los que se relacionará este KPI, para seleccionar varios utilice ctrl';
$string['kpi_select'] = 'KPI';
$string['kpi_select_desc'] = 'Seleccione el tipo de kpi que está subiendo';
// $string['parent_category'] = 'Seleccione la categoría padre';
// $string['categoria_padre_desc'] = 'A partir de esta categoría se listarán los cursos, si elige Misceláneos, se listarán todos los cursos';


$string['filtro'] = 'Campo ';
$string['filtro_desc'] = 'Campo de perfil de donde se obtendrá el catálogo ';

$string['filtro_puestos'] = 'Campo de puestos';
$string['filtro_puestos_desc'] = 'Campo de perfil donde se ubica el puestos';

// $string['filtro_indicadores'] = 'In';
// $string['filtro_indicadores_desc'] = 'A partir de esta categoría se listarán los cursos, si elige Misceláneos, se listarán todos los cursos';

$string['filtro_regiones'] = 'Campo de regiones';
$string['filtro_regiones_desc'] = 'Campo de perfil donde se ubican las regiones';

$string['filtro_tiendas'] = 'Campo de tiendas';
$string['filtro_tiendas_desc'] = 'Campo de perfil donde se ubican las tiendas';

$string['filtro_entrenadores'] = 'Campo de entrenadores';
$string['filtro_entrenadores_desc'] = 'Campo de perfil donde se ubican los entrenadores';

$string['course_minimum_score'] = 'Calificación mínima aprobatoria';
$string['course_minimum_score_desc'] = 'Ingrese la calificación mínima aprobatoria para aprobar el curso (Tome en cuenta la calificación máxima configurada)';

$string['course_grade_activity_completion'] = 'Actividad ';
$string['course_grade_activity_completion_desc'] = 'Elija la actividad por la cual el curso está terminado';

$string['badge_completion'] = 'Insignia necesaria para completar el curso';
$string['badge_completion_desc'] = 'Elija la insignia que significa que el curso está terminado';

// $string['course_attendance'] = 'Asistencia del curso';
// $string['course_attendance_desc'] = 'Asistencia del curso en porcentaje (0-100)';

$string['chart'] = 'Tipo de gráfica en inicio';
$string['chart_desc'] = 'Elija el tipo de gráfica con el que se mostrará en el dashboard';

$string['course_main_chart_color'] = 'Color principal de gráfica en inicio';
$string['course_main_chart_color_desc'] = 'Elija el color principal que tendrá la gráfica con el que se mostrará este curso en el dashboard';

$string['LOCALDOMINOSDASHBOARD_PROGRAMAS_ENTRENAMIENTO'] = 'Elija los programas de entrenamiento';
$string['LOCALDOMINOSDASHBOARD_PROGRAMAS_ENTRENAMIENTO_desc'] = 'Presione la tecla ctrl para seleccionar múltiples elementos';

$string['LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS'] = 'Elija los cursos no presenciales';
$string['LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS_desc'] = 'Presione la tecla ctrl para seleccionar múltiples elementos';

$string['excluded_courses'] = 'Elija los cursos para excluir en los reportes';
$string['excluded_courses_desc'] = 'Presione la tecla ctrl para seleccionar múltiples elementos';

$string['course_completion_activity'] = 'Actividad que finaliza el curso';
$string['course_completion_activity_desc'] = 'Cuál actividad finaliza el curso';

$string['allowed_email_addresses_in_course']        = 'Terminación de correo electrónico';
$string['allowed_email_addresses_in_course_desc']   = 'El correo electrónico debe contener el texto para ser considerado dentro de las inscripciones';

$string['allowed_email_admins']        = 'Correos electrónicos';
$string['allowed_email_admins_desc']   = 'Escriba el correo electrónico de los usuarios que tendrán acceso al dashboard. <br> <strong>Para escribir distintos correos, utilice como separador un espacio</strong>';
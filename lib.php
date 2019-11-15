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

$currentTab = null;

function local_dominosdashboard_user_has_access(bool $throwError = true){
    $has_capability = has_capability('local/dominosdashboard:view', context_system::instance()) || is_siteadmin();
    if(!$has_capability){ // si el rol del usuario no tiene permiso, buscar si está en la configuración: allowed_email_admins
        if(isloggedin()){
            $allowed_email_admins = get_config('local_dominosdashboard', 'allowed_email_admins');
            if(!empty($allowed_email_admins)){
                $allowed_email_admins = explode(' ', $allowed_email_admins);
                if(!empty($allowed_email_admins)){
                    global $USER;
                    $email = $USER->email;
                    if(in_array($email, $allowed_email_admins) !== false){
                        $has_capability = true;
                    }
                }
            }
        }
    }
    if($throwError){
        if(!$has_capability){
            print_error('Usted no tiene permiso para acceder a esta sección');
        }
    }else{
        return $has_capability;
    }
}

DEFINE('LOCAL_DOMINOSDASHBOARD_USERID_FIELD', '_userid_');

// Agrega enlace al Dashboard en el menú lateral de Moodle
function local_dominosdashboard_extend_navigation(global_navigation $nav) {
    $has_capability = local_dominosdashboard_user_has_access(false);
    if($has_capability){
        $this_plugin_name = get_string('pluginname', 'local_dominosdashboard');
        global $CFG;
        $node = $nav->add (
            get_string('pluginname', 'local_dominosdashboard'),
            new moodle_url( $CFG->wwwroot . '/local/dominosdashboard/dashboard.php' )
        );
        $node->showinflatnavigation = true;
        $node = $nav->add (
            'Configuraciones ' . $this_plugin_name,
            new moodle_url( $CFG->wwwroot . '/admin/settings.php?section=local_dominosdashboard' )
        );
        $node->showinflatnavigation = true;
        $node = $nav->add (
            "Subir KPI's en csv " . $this_plugin_name,
            new moodle_url( $CFG->wwwroot . '/local/dominosdashboard/subir_archivo.php' )
        );
        $node->showinflatnavigation = true;
        $node = $nav->add (
            "Administrar KPI's " . $this_plugin_name,
            new moodle_url( $CFG->wwwroot . 'local/dominosdashboard/administrar_KPIS.php' )
        );
        $node->showinflatnavigation = true;
        $node = $nav->add (
            "Reporte personalizado " . $this_plugin_name,
            new moodle_url( $CFG->wwwroot . '/local/dominosdashboard/descargar_reporte_personalizado.php' )
        );
        $node->showinflatnavigation = true;
    }
}

DEFINE("LOCALDOMINOSDASHBOARD_CATEGORY_PARENT_NAME", "parent_category");
DEFINE("LOCALDOMINOSDASHBOARD_DEBUG", true);

DEFINE("KPI_NA", 0);
DEFINE("KPI_OPS", 1);
DEFINE("KPI_HISTORICO", 2);
DEFINE("KPI_SCORCARD", 3);

DEFINE("LOCALDOMINOSDASHBOARD_NOFILTER", "__NOFILTER__");

DEFINE('LOCALDOMINOSDASHBOARD_ENTRENAMIENTO_NACIONAL', 1);
DEFINE('LOCALDOMINOSDASHBOARD_DETALLES_ENTRENAMIENTO', 2);
DEFINE('LOCALDOMINOSDASHBOARD_COURSE_KPI_COMPARATIVE', 3);
DEFINE('LOCALDOMINOSDASHBOARD_AVAILABLE_COURSES', 4);

function local_dominosdashboard_get_course_tabs(){
    return $tabOptions = [
        LOCALDOMINOSDASHBOARD_ENTRENAMIENTO_NACIONAL => 'Programas de entrenamiento',
        LOCALDOMINOSDASHBOARD_DETALLES_ENTRENAMIENTO => 'Detalles de entrenamiento',
        LOCALDOMINOSDASHBOARD_COURSE_KPI_COMPARATIVE => "Comparación de KPI's",
    ];
}

function local_dominosdashboard_get_course_tabs_as_js_script(){
    $result = json_encode(local_dominosdashboard_get_course_tabs());
    return "<script> var ldm_course_tabs = {$result}; </script>";
}

function local_dominosdashboard_get_KPIS($type = 'menu'){
    return local_dominosdashboard_get_kpi_list($type);
}

DEFINE("COMPLETION_DEFAULT", 1);
DEFINE("COMPLETION_DEFAULT_AND_GRADE", 2);
DEFINE("COMPLETION_BY_GRADE", 3);
DEFINE("COMPLETION_BY_BADGE", 4);
DEFINE("COMPLETION_BY_ACTIVITY", 5);
DEFINE("COMPLETION_BY_AVG", 6);
// DEFINE("COMPLETION_BY_ATTENDANCE", 5);

// DEFINE('DOMINOSDASHBOARD_INDICATORS', 'regiones/distritos/entrenadores/tiendas/puestos/ccosto');
DEFINE('DOMINOSDASHBOARD_INDICATORS', 'regiones/entrenadores/distritales/tiendas/puestos');

DEFINE('local_dominosdashboard_required_kpi_columns', array(
    'regiones' => 'profile_field_regiondp',
    'distritales' => 'profile_field_distritalcoachdp',
    'entrenadores' => 'profile_field_entrenadordp',
    'ccosto' => 'profile_field_ccosto',
    'ceco' => 'CECO',
));

DEFINE('DOMINOSDASHBOARD_CHARTS', 
    [
        'bar' => 'Barras',
        'pie' => 'Pay',
        'gauge' => 'Círculo',
        'grupo_cursos' => 'Grupo de cursos',
        // 'grupo' => 'Barras agrupadas',
        'comparativa_regiones' => 'Comparativa de regiones'    
    ]
); 


function local_dominosdashboard_relate_column_with_fields(array $columns, array $requiredFields, bool &$hasRequiredColumns){
    $response = array();
    $notFound = array();
    foreach($requiredFields as $field){
        $pos = array_search($field, $columns);
        if($pos === false){
            $hasRequiredColumns = false;
            array_push($notFound, $field);
        }else{
            $response[$field] = $pos;
        }
    }
    if(!$hasRequiredColumns){
        return $notFound;
    }
    return $response;
}

function local_dominosdashboard_read_kpis_from_columns(array $columns, bool &$hasRequiredColumns){
    $kpis = local_dominosdashboard_get_kpi_list();
    $response = new stdClass();

    $missingColumns = array();
    foreach(local_dominosdashboard_required_kpi_columns as $key =>$reqField){
        $position = array_search($reqField, $columns);
        if($position === false){ // No se encuentra esta columna, se mostrará error
            array_push($missingColumns, $reqField);
        }else{
            $response->$key = $position;
        }
    }
    if(!empty($missingColumns)){
        $hasRequiredColumns = false;
        $message = "Verifique que estén los siguientes campos: " . implode(', ', $missingColumns);
        return $message;
    }
    $response->kpis = array();
    $kpi_names = array();
    foreach($kpis as $kpi){
        $position = array_search($kpi->kpi_key, $columns);
        array_push($kpi_names, $kpi->kpi_key);
        if($position !== false){ // valor del kpi
            // $kpi_record = new stdClass();
            $kpi->position = $position;
            array_push($response->kpis, $kpi);
        }
    }
    if(empty($response->kpis)){
        $kpi_names = implode(',', $kpi_names);
        $hasRequiredColumns = false;
        return "No se tiene ninguna columna de KPI válida, las columnas válidas son: {$kpi_names}";
    }
    return $response;
}

function local_dominosdashboard_get_catalogue(string $key, string $andWhereSql = '', array $query_params = array()){
    $indicators = local_dominosdashboard_get_indicators();
    if(array_search($key, $indicators) === false){
        return [];
    }
    $fieldid = get_config('local_dominosdashboard', "filtro_" . $key);
    if($fieldid === false){
        return [];
    }
    $setting = "allow_empty_" . $key;
    $allow_empty = get_config('local_dominosdashboard', $setting);
    if($allow_empty) {
        $allow_empty = "";
    } else {
        $allow_empty = " AND data != '' AND data IS NOT NULL ";
    }
    $email_provider = local_dominosdashboard_get_email_provider_to_allow();
    if(!empty($email_provider)){
        $whereEmailProvider = " AND userid IN (SELECT id FROM {user} WHERE email LIKE '%{$email_provider}' AND suspended = 0 AND deleted = 0)"; 
    }else{
        $whereEmailProvider = " AND userid IN (SELECT id FROM {user} WHERE suspended = 0 AND deleted = 0)"; 
    }
    global $DB;
    // if($key == 'ccosto'){
    //     $ccomfield = get_config('local_dominosdashboard', "filtro_idccosto");
    //     if(!empty($ccomfield)){
    //         if(!empty($allow_empty)){
    //             $_allow_empty = " AND uid_.data != '' AND uid_.data IS NOT NULL ";
    //         }else{
    //             $_allow_empty = "";
    //         }
    //         $query = "SELECT distinct data as menu_id, COALESCE((SELECT data from {user_info_data} as uid_ WHERE uid_.fieldid = {$ccomfield} AND uid_.userid = uid.userid {$_allow_empty} LIMIT 1), '') as menu_value
    //          FROM {user_info_data} uid where fieldid = {$fieldid} {$andWhereSql} {$allow_empty} {$whereEmailProvider} group by menu_id HAVING menu_value != '' ORDER BY menu_value ASC";
    //         $result = $DB->get_records_sql_menu($query, $query_params);
    //         return $result;
    //     }
    // }
    $query = "SELECT distinct data FROM {user_info_data} where fieldid = {$fieldid} {$andWhereSql} {$allow_empty} {$whereEmailProvider} order by data ASC ";
    return $DB->get_fieldset_sql($query, $query_params);
}

function local_dominosdashboard_get_user_catalogues(array $params = array()){
    $response = array();
    $returnOnly = $indicators = local_dominosdashboard_get_indicators();
    if(!empty($params['selected_filter'])){
        $returnOnly = local_dominosdashboard_get_indicators($params['selected_filter']);
    }
    if(empty($returnOnly)){
        return [];
    }
    
    $conditions = local_dominosdashboard_get_wheresql_for_user_catalogues($params, $indicators);
    foreach($returnOnly as $indicator){
        $response[$indicator] = local_dominosdashboard_get_catalogue($indicator, $conditions->sql, $conditions->params);
    }
    return $response;
}

function local_dominosdashboard_get_wheresql_for_user_catalogues(array $params, $indicators){
    $query_params = array();
    $conditions = array();
    $andWhereSql = "";
    $response = new stdClass();
    if(!empty($params)){
        foreach($params as $key => $param){
            if(array_search($key, $indicators) !== false){
                $fieldid = get_config('local_dominosdashboard', "filtro_" . $key);
                if($fieldid !== false){
                    $data = $params[$key];
                    if(is_string($data) || is_numeric($data)){
                        array_push($conditions, " (fieldid = {$fieldid} AND data = ?)");
                        array_push($query_params, $data);
                    }elseif(is_array($data)){
                        $fieldConditions = array();
                        foreach ($data as $d) {
                            array_push($fieldConditions, " ? ");
                            array_push($query_params, $d);
                        }
                        if(!empty($fieldConditions)){
                            array_push($conditions, "(fieldid = {$fieldid} AND data in (" . implode(",", $fieldConditions) . "))");
                        }
                    }
                }
            }
        }
    }
    if(!empty($conditions)){
        $andWhereSql = " AND userid IN ( SELECT DISTINCT userid FROM {user_info_data} WHERE " . implode(' OR ', $conditions) . ")";
    }
    $response->sql = $andWhereSql;
    $response->params = $query_params;
    return $response;
}

function local_dominosdashboard_get_completion_modes(){
    return [
        COMPLETION_DEFAULT => "Finalizado/No finalizado (seguimiento de finalización configurado)",
        // COMPLETION_DEFAULT_AND_GRADE => "Finalizado/No finalizado más calificación (por ponderación en curso pero no establecida para finalización de curso)",
        COMPLETION_BY_GRADE => "Calificación de una actividad",
        COMPLETION_BY_BADGE => "Obtención de una insignia",
        COMPLETION_BY_ACTIVITY => "Finalización de una actividad",
    ];
}

function local_dominosdashboard_get_course_grade(int $userid, stdClass $course, $default = -1,  $scale = 10){
    global $DB;
    $grade = $default;
    $query = "SELECT grades.finalgrade as finalgrade, items.grademax as grademax FROM {grade_grades} grades JOIN {grade_items} items
    ON grades.itemid = items.id where items.itemtype = 'course' AND items.courseid = {$course->id} AND grades.userid = {$userid}";
    if($data = $DB->get_record_sql($query)){
        if($data->grademax > 0){
            $grade = $data->finalgrade / $data->grademax * $scale;
        }
    }
    return $grade;
}

/**
 * @param $userid int
 * @param $course_completion_info intance of $course = $DB->get_record('course', array('id' => $course->id)); $info = new completion_info($course);
 */
function local_dominosdashboard_get_completed_activities_in_course(int $userid, $course_completion_info){
    if(is_int($course_completion_info)){
        global $DB;
        $course = $DB->get_record('course', array('id' => $course_completion_info));
        $course_completion_info = new completion_info($course);
    }
    $defaultResponse = 0;
    if($course->id === 1){
        return $defaultResponse;
    }
    global $USER, $DB;
    
    $context = context_course::instance($course->id);

    // // Get course completion data.
    $course = $DB->get_record('course', array('id' => $course->id));
    $info = new completion_info($course);

    // Load criteria to display.
    $completions = $course_completion_info->get_completions($userid);

    // Check if this course has any criteria.
    if (empty($completions)){
        return $defaultResponse;
    }

    // Check this user is enroled.
    if ($info->is_tracked_user($userid)){

        $completed_activities = 0;

        // Loop through course criteria.
        foreach ($completions as $completion){
            $criteria = $completion->get_criteria();
            $complete = $completion->is_complete();
            if($complete){
                $completed_activities++;
            }
        }
        return $completed_activities;
    }

    return $defaultResponse;
}

function local_dominosdashboard_get_course_completion(int $userid, stdClass $course, $completion_info = null ){
    if($completion_info == null){
        require_once(__DIR__ . '/../../lib/completionlib.php');
        $completion_info = new completion_info($course);
    }

}

function local_dominosdashboard_get_module_grade(int $userid, int $moduleid, int $scale = 10){
    global $DB;
    $grade = 0;
    $query = "SELECT grades.finalgrade as finalgrade, items.grademax as grademax FROM {grade_grades} grades JOIN {grade_items} items
    ON grades.itemid = items.id where items.itemtype = 'mod' AND grades.userid = {$userid} AND items.iteminstance = {$moduleid}";
    if($data = $DB->get_record_sql($query)){
        if($data->grademax > 0){
            $grade = $data->finalgrade / $data->grademax * $scale;
        }
    }
    return $grade;
}

function local_dominosdashboard_is_enrolled(int $courseid, int $userid){
    return is_enrolled(context_course::instance($courseid), $userid);    
}

function local_dominosdashboard_get_email_provider_to_allow(){
    if($email_provider = get_config('local_dominosdashboard', 'allowed_email_addresses_in_course')){
        return $email_provider; // Ejemplo: @alsea.com.mx o @dominos.com.mx
    }else{
        return ""; // Permitirá todos los correos si no se configura esta sección
    }
}

function local_dominosdashboard_get_userid_with_dominos_mail(){
    global $DB;
    $query = "SELECT id FROM {user} WHERE ";
    return $DB->get_fieldset_sql($query);
}

function local_dominosdashboard_get_kpi_overview(array $params = array(), bool $allCourses = false){
    $kpis = local_dominosdashboard_get_KPIS('list');
    $wherecourseidin = array();
    $ids = array();
    $configs = array(); // Arreglo con los ajustes configurados, ejemplo de un ajuste: "1,2,3"

    foreach($kpis as $key => $kpi){
        $name = 'kpi_' . $key;
        if( $config = get_config('local_dominosdashboard', $name)){
            if(empty($config)){
                continue;
            }
            $configs[$key] = explode(',', $config);
            $ids = array_merge($ids, explode(',', $config));
        }
    }
    if(empty($ids)){
        return array();
    }
    $ids = array_unique($ids); // Cursos que serán consultados
    $ids = implode(',', $ids);
    $where = " AND id IN ({$ids}) ";
    $courses = local_dominosdashboard_get_courses($allCourses, $where);
    // Mostrar comparativa de filtros, en caso de no encontrarla se toma regiones por defecto
    $params['selected_filter'] = local_dominosdashboard_get_value_from_params($params, 'selected_filter', 'regiones'); 
    // foreach($courses as $key => $course){
    //     $courses[$key] = local_dominosdashboard_get_course_information($key, true, false, $params);
    // }
    $response = array();
    // $params['selected_filter'] = "regiones"; // Comparativa de las regiones
    foreach($configs as $kpi_id => $config){ // Iteración entre las configuraciones de los kpis
        // $kpi_result = local_dominosdashboard_get_kpi_results($kpi_id, $params);

        // $kpi_status = new stdClass();
        // $kpi_status->type = $kpis[$kpi_id]->type;
        // $kpi_status->name = $kpis[$kpi_id]->name;
        // $kpi_status->id = $kpis[$kpi_id]->id;
        // $kpi_status->status = $kpi_result;

        // $kpis[$kpi_id]->status = $kpi_result;
        // $_kpi = $kpis[$kpi_id];
        // $_kpi->status = $kpi_result;
        $params['kpi_id'] = $kpi_id;
        foreach($config as $courseid){ // Se agregan los cursos correspondientes
            if(isset($courses[$courseid])){
                // $ctemp = $courses[$courseid];
                // $ctemp = local_dominosdashboard_get_course_information($courseid, true, false, $params);
                $ctemp = local_dominosdashboard_get_course_comparative($courseid, $params);
                // $item = new stdClass();
                // $item->course_name = $ctemp->title;
                // $item->kpi_name = $_kpi->name;
                // $item->kpi = $_kpi;
                // $item->course_information = $ctemp;
                array_push($response, $ctemp);
            }
        }
    }
    return ['type' => 'kpi_list', 'result' => $response];
}

/**
 * @return array
 */
function local_dominosdashboard_get_courses_overview(int $type, array $params = array(), bool $allCourses = false){
    switch ($type) {
        case LOCALDOMINOSDASHBOARD_ENTRENAMIENTO_NACIONAL: // Listado de secciones
            
            $sections_array = array('b', 'c', 'd');
            $sections = array();
            $selected_courses = array();
            $configs = array();
            $course_sections = array();
            $config_name = 'seccion_a';
            $course_sections[$config_name] = new stdClass();
            $course_sections[$config_name]->name = get_string($config_name, "local_dominosdashboard");
            $course_sections[$config_name]->courses = array();

            foreach($sections_array as $s){
                $config_name = 'seccion_' . $s;
                $config = get_config('local_dominosdashboard', $config_name);
                $course_sections[$config_name] = new stdClass();
                $course_sections[$config_name]->name = get_string($config_name, "local_dominosdashboard");
                $course_sections[$config_name]->courses = array();
                if(empty($config)){
                    continue;
                }
                $temp_config = new stdClass();
                $temp_config->config = $config;
                if(strpos($config, ',') === false){
                    $temp_config->exploded_config = array($config);
                }else{ 
                    $temp_config->exploded_config = explode(',', $config);
                }
                $configs[$config_name] = $temp_config;
                $selected_courses = array_merge($selected_courses, $temp_config->exploded_config);
            }
            $selected_courses = array_unique($selected_courses);
            $selected_courses = implode(',', $selected_courses);
            if(empty($selected_courses)){
                $selected_courses = " AND 1 = 0";
            }else{
                $selected_courses = " AND id IN ($selected_courses)";
            }
            $courses = local_dominosdashboard_get_courses(false, $selected_courses);
            $courses_list = array();

            foreach($courses as $course){
                $course_information = local_dominosdashboard_get_course_information($course->id, $kpis = false, $activities = false, $params);
                if(empty($course_information)){
                    continue;
                }
                $courseid = $course_information->id;
                array_push($course_sections['seccion_a']->courses, $course_information);
                foreach($configs as $config_key => $config){
                    if(in_array($courseid, $config->exploded_config)){
                        array_push($course_sections[$config_key]->courses, $course_information);
                    }
                }
            }
            foreach($course_sections as $cs){
                usort($cs->courses, function ($a, $b) {return $a->percentage < $b->percentage;});
            }
            $response = ['sections' => $course_sections];
            return $response;

            break;
        
        case LOCALDOMINOSDASHBOARD_DETALLES_ENTRENAMIENTO: // Listado de cursos disponibles
        case LOCALDOMINOSDASHBOARD_AVAILABLE_COURSES:
            $response = array();
            $courses = local_dominosdashboard_get_courses($allCourses);
            foreach($courses as $course){
                $course_information = local_dominosdashboard_get_course_information($course->id, false, false, $params);
                array_push($response, $course_information);
            }
            if(!empty($response)){
                usort($response, function ($a, $b) {return $a->percentage < $b->percentage;});
            }
            return $response;
            break;
        
        case LOCALDOMINOSDASHBOARD_COURSE_KPI_COMPARATIVE:
            return local_dominosdashboard_get_kpi_overview($params, $allCourses);
            break;
        
        default:
            return array();
            break;
    }
}

function local_dominosdashboard_get_course_chart(int $courseid){
    if($response = get_config('local_dominosdashboard', 'course_main_chart_' . $courseid)){
        return $response;
    }
    return "bar";
}

// function local_dominosdashboard_get_course_color(int $courseid){
//     if($response = get_config('local_dominosdashboard', 'course_main_chart_color_' . $courseid)){
//         return $response;
//     }
//     return "#006491";
// }

function local_dominosdashboard_get_date_add_days(int $days = 1){
    $date = new DateTime(date('Y-m-d'));

    $date->modify("+{$days} day");
    return $date->format('Y-m-d');
}

function local_dominosdashboard_create_slug($str, $delimiter = '_'){
    $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
    return $slug;
} 

define('RETURN_RANDOM_DATA', false);
define('MAX_RANDOM_NUMBER', 500);
function local_dominosdashboard_get_course_information(int $courseid, bool $get_kpis = false, bool $get_activities = false, array $params = array()){ // realizando
    global $DB;
    $course = $DB->get_record('course', array('id' => $courseid));
    if($course === false){
        return false;
    }
    // $response = new stdClass();
    $response = local_dominosdashboard_get_info_from_cache($courseid, $params);
    $response->not_approved_users = $response->enrolled_users - $response->approved_users;
    // _log('', $response);
    $response->key = 'course' . $courseid;
    $response->id = $courseid;
    $response->chart = local_dominosdashboard_get_course_chart($courseid);
    $response->title = $course->fullname;
    // $response->status = 'ok';
    $fecha_inicial = local_dominosdashboard_get_value_from_params($params, 'fecha_inicial');
    $fecha_final = local_dominosdashboard_get_value_from_params($params, 'fecha_final');
    // $response->fecha_inicial = $fecha_inicial;
    // $response->fecha_final = $fecha_final;

    $userids = local_dominosdashboard_get_user_ids_with_params($courseid, $params);
    if($get_activities){
        $response->activities = local_dominosdashboard_get_activities_completion($courseid, $userids, $fecha_inicial, $fecha_final); //
    }

    $params['selected_filter'] =  local_dominosdashboard_get_value_from_params($params, 'selected_filter', 'regiones');
    $response->filter_comparative = local_dominosdashboard_get_course_comparative($courseid, $params);
    return $response;
}

/**
 * Devuelve la información de un curso en caso de existir, false de lo contrario
 * @param int courseid Id del curso que se desea tener información
 * @param array params Arreglo de parámetros de consulta del curso o del periodo que se trate
 * @return stdClass|false Objeto (id, title, key, enrolled_users, approved_users, percentage, value (percentage), startdate, enddate) con la información del curso
 */
function local_dominosdashboard_make_course_completion_information(int $courseid, array $params = array()){
    global $DB;
    $course = $DB->get_record('course', array('id' => $courseid), 'id, shortname, fullname');
    if($course === false){
        return false;
    }
    $response = new stdClass();
    $response->key = 'course' . $courseid;
    $response->id = $courseid;
    $response->title = $course->fullname;
    $fecha_inicial = local_dominosdashboard_get_value_from_params($params, 'fecha_inicial', null);
    $fecha_final = local_dominosdashboard_get_value_from_params($params, 'fecha_final', null);

    $userids = local_dominosdashboard_get_user_ids_with_params($courseid, $params);

    $response->enrolled_users = local_dominosdashboard_get_count_users($userids); // modificar
    if($response->enrolled_users == 0){
        $response->approved_users = 0;
    }else{
        $response->approved_users = local_dominosdashboard_get_approved_users($courseid, $userids, $fecha_inicial, $fecha_final); //
    }
    // $params['selected_filter'] = "regiones"; // Comparativa de las regiones
    // $response->filter_comparative = local_dominosdashboard_get_course_comparative($courseid, $params);
    $response->not_approved_users = $response->enrolled_users - $response->approved_users;
    $response->percentage = local_dominosdashboard_percentage_of($response->approved_users, $response->enrolled_users);
    $response->startdate = $fecha_inicial;
    $response->enddate = $fecha_final;
    $response->value = $response->percentage;
    return $response;
}

function local_dominosdashboard_get_whereids_clauses($filters, $fieldname){
    if(empty($filters)){
        return "";
    }
    $separator = " AND {$fieldname} IN ";
    return $separator . implode($separator, $filters);
}

function local_dominosdashboard_get_count_users($userids){
    global $DB;
    // $whereids = implode(' AND _us_.id IN ', $userids->filters);
    $whereids = local_dominosdashboard_get_whereids_clauses($userids->filters, '_us_.id');
    return $DB->count_records_sql("SELECT count(*) FROM {user} as _us_ WHERE 1 = 1 {$whereids}", $userids->params);
}

function local_dominosdashboard_get_course_comparative($courseid, array $params){
    $currentTab = local_dominosdashboard_get_value_from_params($params, 'type');
    // _log('currentTab', $currentTab);
    $returnkpi = $currentTab == LOCALDOMINOSDASHBOARD_COURSE_KPI_COMPARATIVE;
    // if($returnkpi){
    //     _log('Se regresará KPI');
    // }
    $response = new stdClass();
    global $DB;
    $course = $DB->get_record('course', array('id' => $courseid), 'id, shortname, fullname');
    // $response->info_kpi = $returnkpi ? 'Regresando kpi' : 'Sin kpi';
    $response->title = $course->fullname;
    $response->key = 'course_' . $course->id;
    $response->id = $course->id;
    // $response->shortname = $course->shortname;
    $response->fullname = $course->fullname;
    $indicators = local_dominosdashboard_get_indicators();
    $conditions = local_dominosdashboard_get_wheresql_for_user_catalogues($params, $indicators);
    if($course === false){
        return array();
    }
    $fecha_inicial = local_dominosdashboard_get_value_from_params($params, 'fecha_inicial');
    $fecha_final = local_dominosdashboard_get_value_from_params($params, 'fecha_final');
    $indicator = $params['selected_filter'] = local_dominosdashboard_get_value_from_params($params, 'selected_filter', 'regiones');
    // _log('Calculando selected_filter ', $indicator);
    // if($params['selected_filter'] != 'regiones') _log('Mostrando un resultado diferente a regiones');    
    $catalogue = local_dominosdashboard_get_catalogue($indicator, $conditions->sql, $conditions->params);
    // if(!in_array('', $catalogue)){
    //     array_push($catalogue, '');
    // }
    $kpi_id = $returnkpi ? local_dominosdashboard_get_value_from_params($params, 'kpi_id') : '';
    $kpi_info = $returnkpi ? $DB->get_record('dominos_kpi_list', array('id' => $kpi_id)) : false;
    if($returnkpi) $response->kpi_info = $kpi_info;
    $key = $indicator;
    $comparative = array();
    foreach($catalogue as $catalogue_item){
        $params[$key] = $catalogue_item;
        $item_to_compare = new stdClass();
        $item_to_compare = local_dominosdashboard_get_info_from_cache($courseid, $params, $return_regions = true);
        if($catalogue_item == ''){
            $item_to_compare->name = '(Vacío)';
        }else{
            $item_to_compare->name = $catalogue_item;
        }
        if($returnkpi){
            $kpi_params = $params;
            $kpi_params[$key] = $catalogue_item;
            // _log('Obteniendo KPIS');
            // $kpi_id = local_dominosdashboard_get_value_from_params($params, 'kpi_id');
            // $params['kpi_id']
            $item_to_compare->kpi = local_dominosdashboard_get_kpi_results($kpi_id, $kpi_params);
            // if($item_to_compare->kpi !== null){
            //     _log('Se encontró kpi no nulo', $item_to_compare);
            // }else{
            //     _log("Kpi {$kpi_id} null", $kpi_params[$key]);
            // }
        }
        array_push($comparative, $item_to_compare);
    }
    $response->comparative = $comparative;
    $response->filter = $indicator;
    return $response;
}

function local_dominosdashboard_get_value_from_params(array $params, string $search, $returnIfNotExists = '', bool $apply_not_empty = false){
    if(array_key_exists($search, $params)){
        if($apply_not_empty){
            if(!empty($params[$search])){
                return $params[$search];
            }
        }else{
            return $params[$search];
        }
    }
    return $returnIfNotExists;
}

define('local_dominosdashboard_kpi_empty_result', 0);
function local_dominosdashboard_get_kpi_results($id, array $params){
    global $DB;
    $whereClauses = array();

    $kpi = $DB->get_record('dominos_kpi_list', array('id' => $id));
    if(empty($kpi)){
        _log('No se encontró el kpi con el id', $id);
        return "KPI no encontrado";
        return null;
    }
    
    $sqlParams = array();
    $ccoms = "";

    $fecha_inicial = local_dominosdashboard_get_value_from_params($params, 'fecha_inicial');
    $fecha_final = local_dominosdashboard_get_value_from_params($params, 'fecha_final');
    
    $campo_fecha = 'kpi_date';
    $filtro_fecha = "";
    $filtro_fecha = local_dominosdashboard_create_sql_dates($campo_fecha, $fecha_inicial, $fecha_final);
    if($filtro_fecha == ''){
        $fecha_kpi = date('Y-m-d', time());
        $selected_date = new DateTime($fecha_kpi);
        $month = $selected_date->format('m');
        $year = $selected_date->format('Y');
        $filtro_fecha = " AND YEAR(FROM_UNIXTIME(kpi_date)) = {$year} AND MONTH(FROM_UNIXTIME(kpi_date)) = $month ";
    }

    array_push($whereClauses, " kpi_key = ? {$ccoms} {$filtro_fecha} ");
    // $whereClauses = " kpi_key = ? {$ccoms} {$filtro_fecha}";
    array_push($sqlParams, $kpi->kpi_key);


    // $where_filters = array();
    $allowed_columns = local_dominosdashboard_required_kpi_columns;

    $selected_filter = $params['selected_filter'] = local_dominosdashboard_get_value_from_params($params, 'selected_filter', 'regiones'); 

    foreach ($allowed_columns as $key => $requiredColumn) {
        // $position = array_search($key, $params);
        // if($position !== false){
        if(array_key_exists($key, $params)){
            if($key == 'tiendas'){
                $fieldid = get_config('local_dominosdashboard', "filtro_tiendas");
                if($fieldid === false){ // Campo no configurado
                    continue;
                }
                
                list($insql, $inparams) = $DB->get_in_or_equal($params[$key]);
                $sqlParams = array_merge($sqlParams, $inparams);
                $query = " ccosto IN (SELECT distinct data FROM {user_info_data} 
                WHERE userid IN 
                    (SELECT DISTINCT userid FROM {user_info_data} where fieldid = {$fieldid} AND data {$insql})) ";
                array_push($whereClauses, $query);
                // $query = "SELECT * FROM {user_info_data} WHERE ";
                // array_push()
            }else{
                list($insql, $inparams) = $DB->get_in_or_equal($params[$key]);
                $sqlParams = array_merge($sqlParams, $inparams);
                array_push($whereClauses, " {$key} {$insql} ");
            }
        }
    }

    $whereClauses = implode(' AND ', $whereClauses);

    // _log('La consulta de los KPIS es: ', $whereClauses);
    // _log($whereClauses, $sqlParams);

    $operation = empty($kpi->calculation) ? 'avg' : $kpi->calculation; // Operación de cálculo (avg o sum)

    switch($kpi->type){
        // case 'Texto': // Ejemplo: Aprobado, no aprobado y destacado
        //     $query = "SELECT value, COUNT(*) AS conteo FROM {dominos_kpis} WHERE {$whereClauses} GROUP BY value ";
        //     $result = $DB->get_records_sql_menu($query, $sqlParams);
        //     if($result === false || $result === null){
        //         // _log('Retornando valor por defecto');
        //         // _sql($query, $sqlParams);
        //         return local_dominosdashboard_kpi_empty_result;
        //     } 
        //     return $result;
        //     break;
        case 'Número entero': // 2 Ejemplo: devuelve el número de quejas
            $query = "SELECT ROUND({$operation}(value), 0) AS value FROM {dominos_kpis} WHERE {$whereClauses} ";
            $result = $DB->get_field_sql($query, $sqlParams);
            if($result === false || $result === null){
                // _log('Retornando valor por defecto');
                // _sql($query, $sqlParams);
                return local_dominosdashboard_kpi_empty_result;
            }
            return $result;
            break;
        case 'Decimal': // 2 Ejemplo: devuelve el número de quejas
            $query = "SELECT ROUND({$operation}(value), 2) AS value FROM {dominos_kpis} WHERE {$whereClauses} ";
            $result = $DB->get_field_sql($query, $sqlParams);
            if($result === false || $result === null){
                // _log('Retornando valor por defecto');
                // _sql($query, $sqlParams);
                return local_dominosdashboard_kpi_empty_result;
            }
            return $result;
            break;
        case 'Porcentaje': // 3
            $query = "SELECT ROUND({$operation}(value), 2) AS value FROM {dominos_kpis} WHERE {$whereClauses} ";
            $result = $DB->get_field_sql($query, $sqlParams);
            if($result === false || $result === null){
                // _log('Retornando valor por defecto');
                // _sql($query, $sqlParams);
                return local_dominosdashboard_kpi_empty_result;
            }
            return $result;
            break;
        default:
            return local_dominosdashboard_kpi_empty_result;
        break;
    }
}

function local_dominosdashboard_create_sql_dates($campo_fecha, $fecha_inicial, $fecha_final){
    $filtro_fecha = '';
    if(empty($fecha_inicial) && empty($fecha_final)){ // las 2 vacías
        $filtro_fecha = ""; // No se aplica filtro
    }elseif(!empty($fecha_inicial) && empty($fecha_final)){ // solamente fecha_inicial
        $filtro_fecha = " AND FROM_UNIXTIME({$campo_fecha}) > '{$fecha_inicial}'";
        // $filtro_fecha .= $campo_fecha . ' ';
    }elseif(empty($fecha_inicial) && !empty($fecha_final)){ // solamente fecha_final
        $filtro_fecha = " AND FROM_UNIXTIME({$campo_fecha}) < '{$fecha_final}'";
    }elseif(!empty($fecha_inicial) && !empty($fecha_final)){ // ambas requeridas
        $filtro_fecha = " AND (FROM_UNIXTIME({$campo_fecha}) BETWEEN '{$fecha_inicial}' AND '{$fecha_final}')";
    }
    return $filtro_fecha;
}

function local_dominosdashboard_get_time_from_month_and_year(int $month, int $year){
    $date = new DateTime("{$year}-{$month}-02");
    return $date->format('U');
}

// function local_dominosdashboard_get_ideales_as_js_script(){
//     $ideal_cobertura = get_config('local_dominosdashboard', 'ideal_cobertura');
//     if($ideal_cobertura === false){
//         $ideal_cobertura = 94;
//     }
//     $ideal_rotacion  = get_config('local_dominosdashboard', 'ideal_rotacion');
//     if($ideal_rotacion === false){
//         $ideal_rotacion = 85;
//     }
//     return "<script> var ideal_cobertura = {$ideal_cobertura}; var ideal_rotacion = {$ideal_rotacion}; </script>";
// }

function local_dominosdashboard_get_approved_users(int $courseid, $userids = '', $fecha_inicial, $fecha_final){ //
    $response = 0;
    if(empty($userids)){ // no users to search in query
        // _log('No se encuentran usuarios');
        return $response;
    }
    $completion_mode = get_config('local_dominosdashboard', "course_completion_" . $courseid);
    // // _log($completion_mode);
    $default = 0;
    if($completion_mode === false){ // missing configuration
        // _log("Missing configuration courseid", $courseid, 'completion mode returned false');
        return $response;
    }
    $query = "";
    // _log("Tipo de completado", $completion_mode);
    switch($completion_mode){
        case COMPLETION_DEFAULT:
            if(empty($userids)){
                // $whereids = "";
                return 0;
            }else{
                // $whereids = implode(' AND p.userid IN ', $userids->filters);
                $whereids = local_dominosdashboard_get_whereids_clauses($userids->filters, 'p.userid');
                // $whereids = " AND p.userid IN ({$userids})";
            }
            $campo_fecha = "p.timecompleted";
            $filtro_fecha = local_dominosdashboard_create_sql_dates($campo_fecha, $fecha_inicial, $fecha_final);    
            $query = "SELECT count(*) AS completions FROM {course_completions} AS p
            WHERE p.course = {$courseid} AND p.timecompleted IS NOT NULL {$filtro_fecha} {$whereids} ";
        break;
        case COMPLETION_BY_GRADE:
            if(empty($userids)){
                // $whereids = "";
                return 0;
            }else{
                // $whereids = implode(' AND gg.userid IN ', $userids->filters);
                $whereids = local_dominosdashboard_get_whereids_clauses($userids->filters, 'gg.userid');
                // $whereids = " AND p.userid IN ({$userids})";
            }
            $grade_item = get_config('local_dominosdashboard', 'course_grade_activity_completion_' . $courseid);
            $minimum_score = get_config('local_dominosdashboard', 'course_minimum_score_' . $courseid);
            if($grade_item === false || $minimum_score === false){ // Missing configuration
                // _log("Missing configuration courseid", $courseid, 'COMPLETION_BY_GRADE');
                return $response;
            }
            $campo_fecha = "gg.timemodified";
            $filtro_fecha = local_dominosdashboard_create_sql_dates($campo_fecha, $fecha_inicial, $fecha_final);
            $query = "SELECT count(*) AS completions FROM {grade_grades} AS gg WHERE
             gg.itemid = {$grade_item} AND final_grade >= {$minimum_score} {$whereids} {$filtro_fecha}";
        break;
        case COMPLETION_BY_ACTIVITY:
            if(empty($userids)){
                // $whereids = "";
                return 0;
            }else{
                // $whereids = implode(' AND cmc_.userid IN ', $userids->filters);
                $whereids = local_dominosdashboard_get_whereids_clauses($userids->filters, 'cmc_.userid');
                // $whereids = " AND p.userid IN ({$userids})";
            }
            $completion_activity = get_config('local_dominosdashboard', 'course_completion_activity_' . $courseid);
            /* completionstate 0 => 'In Progress' 1 => 'Completed' 2 => 'Completed with Pass' completionstate = 3 => 'Completed with Fail' */
            $campo_fecha = "timemodified";
            $filtro_fecha = local_dominosdashboard_create_sql_dates($campo_fecha, $fecha_inicial, $fecha_final);
            $query = "SELECT count(*) AS completions from {course_modules_completion} as cmc_ WHERE
             coursemoduleid = {$completion_activity} AND completionstate IN (1,2) $whereids {$filtro_fecha}";
        break;
        case COMPLETION_BY_BADGE:
            // Obtener los id de usuarios inscritos en el curso
            $completion_badge = get_config('local_dominosdashboard', 'badge_completion_' . $courseid);
            // $ids = implode(',', $ids);
            if(empty($userids)){
                // $whereids = "";
                return 0;
            }else{
                // $whereids = implode(' AND bi.userid IN ', $userids->filters);
                $whereids = local_dominosdashboard_get_whereids_clauses($userids->filters, 'bi.userid');
                // $whereids = " AND p.userid IN ({$userids})";
            }
            $campo_fecha = "dateissued";
            $filtro_fecha = local_dominosdashboard_create_sql_dates($campo_fecha, $fecha_inicial, $fecha_final);
        $query = "SELECT count(*) AS completions from {badge_issued} as bi_ WHERE badgeid = {$completion_badge} {$whereids} {$filtro_fecha}";
        break;
        default: 
            // $response->message = 'Completion not allowed';
            return $response;
        break;
    }
    if(!empty($query)){
        global $DB;
        if($result = $DB->get_record_sql($query, $userids->params)){
            $response = $result->completions;
            $response = intval($response);
            return $response;
        }
    }
    return $response;
}

function local_dominosdashboard_percentage_of(int $number, int $total, int $decimals = 2 ){
    if($total != 0){
        return round($number / $total * 100, $decimals);
    }else{
        return 0;
    }
}

function local_dominosdashboard_get_course_grade_item_id(int $courseid){
    global $DB;
    return $DB->get_field('grade_items', 'id', array('courseid' => $courseid, 'itemtype' => 'course'));
}

function local_dominosdashboard_get_selected_params(array $params){
    $result = array();
    if(!empty($params)){
        $indicators = local_dominosdashboard_get_indicators();
        foreach($params as $key => $param){
            if(array_search($key, $indicators) !== false){
                $filter = array();

                $data = $params[$key];
                if(is_string($data) || is_numeric($data)){
                    array_push($filter, $data);
                }elseif(is_array($data)){
                    foreach ($data as $d) {
                        array_push($filter, $d);
                    }
                }

                if(!empty($filter)){
                    $result[$key] = implode(', ', $filter);
                }
            }
        }

        $fecha_inicial = local_dominosdashboard_get_value_from_params($params, 'fecha_inicial');
        if(!empty($fecha_inicial)){
            $result['fecha_inicial'] = $fecha_inicial;
        }

        $fecha_final = local_dominosdashboard_get_value_from_params($params, 'fecha_final');
        if(!empty($fecha_final)){
            $result['fecha_final'] = $fecha_final;
        }
    }
    return $result;
}

/**
 * @param mixed $course Id de los cursos
 * @param string $fecha_inicial YYYY/MM/dd
 * @param string $fecha_final YYYY/MM/dd
 */
function local_dominosdashboard_get_enrolled_userids($course, $fecha_inicial, $fecha_final){
    $email_provider = local_dominosdashboard_get_email_provider_to_allow();
    if(!empty($email_provider)){
        $where = " AND email LIKE '%{$email_provider}'"; 
    }else{
        $where = ""; 
    }
    $many_courses = strpos($course, ',') !== false;
    $wherecourse = ($many_courses) ? " IN ({$course}) " : " = {$course} ";

    $campo_fecha = "__ue__.timestart";
    $filtro_fecha = local_dominosdashboard_create_sql_dates($campo_fecha, $fecha_inicial, $fecha_final);
    /* User is active participant (used in user_enrolments->status) -- Documentación tomada de enrollib.php 
    define('ENROL_USER_ACTIVE', 0);*/
    $query = "( SELECT DISTINCT __user__.id FROM {user} AS __user__
    JOIN {user_enrolments} AS __ue__ ON __ue__.userid = __user__.id
    JOIN {enrol} __enrol__ ON (__enrol__.id = __ue__.enrolid AND __enrol__.courseid {$wherecourse})
    WHERE __ue__.status = 0 AND __user__.deleted = 0 {$filtro_fecha} AND __user__.suspended = 0 {$where} AND __user__.id NOT IN 
    (SELECT DISTINCT __role_assignments__.userid as userid
        FROM {course} AS __course__
        LEFT JOIN {context} AS __context__ ON __course__.id = __context__.instanceid
        JOIN {role_assignments} AS __role_assignments__ ON __role_assignments__.contextid = __context__.id
        WHERE __course__.id {$wherecourse}
        AND __role_assignments__.roleid NOT IN (5) # No students
    ) )";

    return $query;
}

function local_dominosdashboard_get_user_ids_with_params(int $courseid, array $params = array(), bool $returnAsString = false){
    $fecha_inicial = local_dominosdashboard_get_value_from_params($params, 'fecha_inicial');
    $fecha_final = local_dominosdashboard_get_value_from_params($params, 'fecha_final');
    // Se omite $fecha_inicial debido a que si se incluye los usuarios inscritos anteriormente serían omitidos, activar si se pide explícitamente ese funcionamiento
    // $ids = local_dominosdashboard_get_enrolled_userids($courseid, $fecha_inicial, $fecha_final);
    $ids = local_dominosdashboard_get_enrolled_userids($courseid, '', $fecha_final);
    $filters_sql = array();
    array_push($filters_sql, $ids);
    $allow_users = array();
    $filter_active = false;
    $query_parameters = array();
    if(!empty($params)){
        global $DB;
        $indicators = local_dominosdashboard_get_indicators();
        // // _log('indicators', $indicators);
        // $position = array_search();
        $prefix = "___";
        $tableName = 'user_info_data';
        foreach($params as $key => $param){
            // // _log('$params as $key => $param', $key, $param);
            if(array_search($key, $indicators) !== false){
                $fieldid = get_config('local_dominosdashboard', "filtro_" . $key);
                if($fieldid !== false){
                    $prefix .= '_';
                    $alias = $prefix . $tableName;
                    $data = $params[$key];
                    $filter_active = true;
                    if(is_string($data) || is_numeric($data)){
                        array_push($filters_sql, " (SELECT DISTINCT {$alias}.userid FROM {user_info_data} AS {$alias} WHERE {$alias}.fieldid = ? AND {$alias}.data = ?) ");
                        array_push($query_parameters, $fieldid);
                        array_push($query_parameters, $data);
                        // $newIds = 
                        // $newIds = $DB->get_fieldset_select('user_info_data', 'distinct userid', ' fieldid = :fieldid AND data = :data ', array('fieldid' => $fieldid, 'data' => $params[$key]));
                    }elseif(is_array($data)){
                        $wheres = array();
                        $query_params = array();
                        $options = array();
                        foreach ($data as $d) {
                                array_push($wheres, " data = ? ");
                                array_push($query_params, $d);
                                array_push($options, $d);
                                array_push($query_parameters, $d);
                        }
                        if(!empty($options)){
                            $bindParams = array();
                            for($i = 0; $i < count($options); $i++){
                                array_push($bindParams, '?');
                            }
                            $bindParams = implode(',', $bindParams);
                            array_push($filters_sql, " (SELECT DISTINCT {$alias}.userid FROM {user_info_data} AS {$alias} WHERE {$alias}.data IN ({$bindParams}) AND {$alias}.fieldid = ? ) ");
                            array_push($query_parameters, $fieldid);                            
                            // $options = implode(',');
                        }
                        if(!empty($wheres)){
                            $wheres = " AND ( " . implode(" || ", $wheres) . " ) ";
                            $query = "SELECT DISTINCT userid FROM {user_info_data} WHERE fieldid = {$fieldid} " . $wheres;
                        }
                    }
                }
            }
        }
    }
    $response = new stdClass();
    $response->filters = $filters_sql;
    $response->params = $query_parameters;
    // _log($response);
    return $response;
}

function local_dominosdashboard_get_gradable_items(int $courseid, int $hidden = 0){
    if($hidden != 1 || $hidden != 0){
        $hidden = 0;
    }
    global $DB;
    return $DB->get_records_menu('grade_items', array('courseid' => $courseid, 'itemtype' => 'mod', 'hidden' => $hidden), '', 'id,itemname');
}

// function local_dominosdashboard_(){
    
// }

function local_dominosdashboard_get_indicators(string $from = ''){
    $indicators = explode('/', DOMINOSDASHBOARD_INDICATORS);
    if(!empty($from)){
        $exists = array_search($from, $indicators);
        if($exists !== false){
            $exists++;
            $filter = array();
            for ($i=$exists; $i < count($indicators); $i++) { 
                array_push($filter, $indicators[$i]);
            }
            $indicators = $filter;
        }
    }
    return $indicators;
}

function local_dominosdashboard_get_charts(){
    return DOMINOSDASHBOARD_CHARTS;
}

function local_dominosdashboard_get_profile_fields(){
    global $DB;
    $profileFields = array();
    foreach($DB->get_records('user_info_field', array(), 'id, shortname') as $profileField){
        $profileFields[$profileField->id] = $profileField->shortname;
    }
    return $profileFields;
}

if(!function_exists('dd')){
    function dd($element){
        die(var_dump($element));
    }
}

if(!function_exists('_log')){
    function _log(...$parameters){
        if(!LOCALDOMINOSDASHBOARD_DEBUG){
            return;
        }
        $output = "";
        foreach($parameters as $parameter){
            if($parameter === true){
                $output .= ' true ' . ' ';
            }
            elseif($parameter === false){
                $output .= ' false ' . ' ';
            }
            elseif($parameter === null){
                $output .= ' null ' . ' ';
            }else{
                $output .= print_r($parameter, true) . ' ';
            }
        }
        error_log($output);
    }
}

if(!function_exists('_print')){
    function _print(...$parameters){
        $output = "<pre>";
        foreach($parameters as $parameter){
            if($parameter === true){
                $output .= ' true ' . ' <br>';
            }
            elseif($parameter === false){
                $output .= ' false ' . ' <br>';
            }
            elseif($parameter === null){
                $output .= ' null ' . ' <br>';
            }else{
                $output .= print_r($parameter, true) . ' <br>';
            }
        }
        $output .= "</pre>";
        echo $output;
    }
}

function local_dominosdashboard_format_response($data, string $dataname = "data", string $status = 'ok'){
    if(is_array($data)){
        $count = count($data);
    } else {
        $count = 1;
    }
    if(empty($data)){
        if($status == 'ok'){
            $status = "No data found";
        }
        $count = 0;
    }
    $result = array();
    $result['status'] = $status;
    $result['count'] = $count;
    $result[$dataname] = $data;
    return json_encode($result);
}

function local_dominosdashboard_done_successfully($message = 'ok'){
    $result = new stdClass();
    $result->status  = 'ok';
    $result->message = $message;
    return json_encode($result);
}

function local_dominosdashboard_error_response($message = 'error'){
    $result = new stdClass();
    $result->status  = 'error';
    $result->message = $message;
    return json_encode($result);
}

function local_dominosdashboard_get_courses(bool $allCourses = false, $andWhereClause = ""){
    global $DB;
    $categories = local_dominosdashboard_get_categories_with_subcategories(local_dominosdashboard_get_category_parent(), false);
    if($allCourses){
        $query = "SELECT id, fullname, shortname FROM {course} where category in ({$categories}) {$andWhereClause} order by sortorder";
    }else{
        if($exclusion = get_config('local_dominosdashboard', 'excluded_courses')){
            if($exclusion != ''){
                $andWhereClause .= " AND id NOT IN ({$exclusion}) ";
            }
        }
        $query = "SELECT id, fullname, shortname FROM {course} where category in ({$categories}) {$andWhereClause} order by sortorder";
    }
    return $DB->get_records_sql($query);
}

function local_dominosdashboard_get_categories(){
    global $DB;
    $categories = $DB->get_records_sql('SELECT id, name, path FROM {course_categories}');
    if($categories == false){
        return [];
    }
    $cats = array();
    foreach($categories as $category){
        $cats[$category->id] = $category->name;
    }
    return $cats;
}

function local_dominosdashboard_get_categories_with_subcategories(int $category_id, bool $returnAsArray = true/*, string $path, array $categories*/){
    global $DB;
    $category = $DB->get_record('course_categories', array('id' => $category_id));
    $categories = array();
    if($category){
        $query = "SELECT id FROM {course_categories} WHERE path LIKE '{$category->path}%' AND id != {$category_id}";
        array_push($categories, $category_id);
        foreach($DB->get_records_sql($query) as $subc){
            array_push($categories, $subc->id);
        }
    }
    if(empty($categories)){
        array_push($categories, '-1'); // Agregar un id que no exista, course_categories tiene un id unsigned_int por lo que no debe dar problemas este id
    }
    if($returnAsArray){
        return $categories;
    }else{
        return implode(",", $categories);
    }
}

function local_dominosdashboard_get_category_parent(){
    if($data = get_config('local_dominosdashboard', LOCALDOMINOSDASHBOARD_CATEGORY_PARENT_NAME)){
        return $data;
    }else{
        return 1; // Miscelaneous
    }
}

/**
 * @param int $badge_status Badge status: 0 = inactive, 1 = active, 2 = active+locked, 3 = inactive+locked, 4 = archived, -1 = all badge status
 */
function local_dominosdashboard_get_badges(int $badge_status = -1){
    if(!is_int($badge_status)){
        $status = -1;
    }
    global $DB;
    if($badge_status != -1){
        return $DB->get_records_menu('badge', array('status' => $badge_status), '', 'id,name');
    }
    return $DB->get_records_menu('badge', array(), '', 'id,name');
}

function local_dominosdashboard_get_activities(int $courseid, string $andwhere = ""){
    global $DB;
    $actividades = array();
    $query  = "SELECT id, CASE ";
    $tiposDeModulos = $DB->get_records('modules', array('visible' => 1), 'id,name');
    foreach ($tiposDeModulos as $modulo) {
        $nombre  = $modulo->name;
        $alias = 'a'.$modulo->id;
        $query .= ' WHEN cm.module = '.$modulo->id.' THEN (SELECT '.$alias.'.name FROM {'.$nombre.'} '.$alias.' WHERE '.$alias.'.id = cm.instance) ';
    }
    $query .= " END AS name
    from {course_modules} cm
    where course = {$courseid} {$andwhere} ";
    return $DB->get_records_sql_menu($query);
}

function local_dominosdashboard_get_activities_completion(int $courseid, $userids, $fecha_inicial, $fecha_final){

    $activities = array();
    if(empty($userids)){
        return $activities;
    }
    global $DB;
    $courseactivities = local_dominosdashboard_get_activities($courseid, " AND completion != 0 ");
    foreach($courseactivities as $key => $activity){
        $activityInformation = local_dominosdashboard_get_activity_completions($activityid = $key, $userids, $title = $activity, $fecha_inicial, $fecha_final);
        array_push($activities, $activityInformation);
    }
    usort($activities, function ($a, $b) {return $a['completed'] < $b['completed'];});
    return $activities;
}

function local_dominosdashboard_get_activity_completions(int $activityid, $userids = "", $title = "", $fecha_inicial, $fecha_final){
    $campo_fecha = "timemodified";
    $filtro_fecha = "";
    $filtro_fecha = local_dominosdashboard_create_sql_dates($campo_fecha, $fecha_inicial, $fecha_final);
    global $DB;
    $key = "module" . $activityid;
    if(empty($userids)){
        $whereids = " AND 1 = 0 ";
    }else{
        // $whereids = implode(' AND _cmc_.userid IN ', $userids->filters);
        $whereids = local_dominosdashboard_get_whereids_clauses($userids->filters, '_cmc_.userid');
    }
    // $inProgress         = $DB->count_records_sql("SELECT count(*) FROM {course_modules_completion} WHERE coursemoduleid = {$activityid} AND userid IN ({$userids}) AND completionstate = 0");
    $completed          = $DB->count_records_sql("SELECT count(*) FROM {course_modules_completion} as _cmc_ WHERE coursemoduleid = {$activityid} {$whereids} AND completionstate IN (1,2) {$filtro_fecha}", $userids->params);
    // $completedWithFail  = $DB->count_records_sql("SELECT count(*) FROM {course_modules_completion} WHERE coursemoduleid = {$activityid} AND userid IN ({$userids}) AND completionstate = 3");
    return compact('key', 'title', 'inProgress', 'completed', 'completedWithFail');
}

function local_dominosdashboard_get_competencies($conditions = array()){
    global $DB;
    return $DB->get_records('competency', $conditions);
}

function local_dominosdashboard_get_user_competencies($userid){
    global $DB;
    $sql = "SELECT c.*
                FROM {competency_usercomp} uc
                JOIN {competency} c
                ON c.id = uc.competencyid
                WHERE uc.userid = ?";
    return $DB->get_records_sql($sql, array($userid));
}

function local_dominosdashboard_get_all_user_competencies(array $conditions = array()){
    global $DB;
    $competencies = $DB->get_records('competency', array(), '', 'id, shortname, shortname as title');
    foreach($competencies as $competency){
        $competency->proficiency = $DB->count_records('competency_usercomp', array('competencyid' => $competency->id, 'proficiency' => 1));
    }
    usort($competencies, function($a, $b){
        return $a->proficiency - $b->proficiency;
    });
    return $competencies;
}

function local_dominosdashboard_get_last_month_key(array $columns){
    $meses = "12_DICIEMBRE,11_NOVIEMBRE,10_OCTUBRE,9_SEPTIEMBRE,8_AGOSTO,7_JULIO,6_JUNIO,5_MAYO,4_ABRIL,3_MARZO,2_FEBRERO,1_ENERO";
    $meses = explode(',', $meses);
    foreach($meses as $mes){
        $search = array_search($mes, $columns);
        if($search !== false){
            // _log("El índice retornado es: ", $search);
            return $search;
        }
    }
    return -1; // it will throw an error
}

function local_dominosdashboard_get_last_month_name(array $columns){
    $meses = "12_DICIEMBRE,11_NOVIEMBRE,10_OCTUBRE,9_SEPTIEMBRE,8_AGOSTO,7_JULIO,6_JUNIO,5_MAYO,4_ABRIL,3_MARZO,2_FEBRERO,1_ENERO";
    $meses = explode(',', $meses);
    foreach($meses as $mes){
        $search = array_search($mes, $columns);
        if($search !== false){
            // _log("El índice retornado es: ", $search);
            return $mes;
        }
    }
    return -1; // it will throw an error
}

function local_dominosdashboard_convert_month_name(string $monthName){
    $parts = explode('_', $monthName);
    return $parts[0];
}

function local_dominosdashboard_format_month_from_kpi($m){
    if(empty($m)){
        return "";
    }
    if(is_int($m)){
        if($m <= 13){ // de 1 a 12
            return $m;
        }
    }
    if(is_string($m)){
        $m = strtoupper($m);
        $meses = [
            1 => 'ENERO',
            2 => 'FEBRERO',
            3 => 'MARZO',
            4 => 'ABRIL',
            5 => 'MAYO',
            6 => 'JUNIO',
            7 => 'JULIO',
            8 => 'AGOSTO',
            9 => 'SEPTIEMBRE',
            10 => 'OCTUBRE',
            11 => 'NOVIEMBRE',
            12 => 'DICIEMBRE',
        ];
        $busqueda = array_search($m, $meses);
        if($busqueda !== false){
            return $busqueda;
        }
        $meses = [
            1 => 'ENE',
            2 => 'FEB',
            3 => 'MAR',
            4 => 'ABR',
            5 => 'MAY',
            6 => 'JUN',
            7 => 'JUL',
            8 => 'AGO',
            9 => 'SEP',
            10 => 'OCT',
            11 => 'NOV',
            12 => 'DIC',
        ];
        $busqueda = array_search($m, $meses);
        if($busqueda !== false){
            return $busqueda;
        }
    }
    return $m;
}

function local_dominosdashboard_make_all_historic_reports(){
    $courses =  local_dominosdashboard_get_courses();
    foreach($courses as $course){
        local_dominosdashboard_make_historic_report($course->id);
    }
}

function local_dominosdashboard_get_cache_params(int $courseid, array $params, $prefix = '_cache'){ // realizando
    $indicators = local_dominosdashboard_get_indicators(); 
    $response = new stdClass();
    $indicators_request = array();

    $where_clauses = array(" {$prefix}.courseid = ? ");
    $where_params = array($courseid);
    foreach($indicators as $indicator){ // Sólo se agregan los indicadores, coninciden con la tabla dominos_d_cache
        $found = false;
        $response->$indicator = null;
        if(array_key_exists($indicator, $params)){
            if(!empty($params[$indicator])){
                $indicators_request[$indicator] = $params[$indicator];
                $found = true;
            }
        }
        if(!$found){
            array_push($where_clauses, " {$prefix}.{$indicator} IS NULL ");
        }
    }
    
    foreach($indicators_request as $key => $request){
        if(is_array($request)){
            sort($request);
            $request = implode(local_dominosdashboard_cache_separator, $request);
        }elseif(is_string($request) || is_numeric($request)){
            // array_push($where_clauses, '?'); // array_push($where_params, $request);
        }else{
            unset($indicators_request[$key]);
            continue;
        }
        $response->$key = $request;
        array_push($where_clauses, " {$prefix}.{$key} = ? ");
        array_push($where_params, $request);
    }

    $response->startdate = $fecha_inicial = local_dominosdashboard_get_value_from_params($params, 'fecha_inicial', null);
    $response->enddate = $fecha_final = local_dominosdashboard_get_value_from_params($params, 'fecha_final', null);
    if(!empty($fecha_inicial)){
        array_push($where_clauses, " date(from_unixtime({$prefix}.startdate)) = ? ");
        array_push($where_params, $fecha_inicial);        
    }else{
        array_push($where_clauses, " ({$prefix}.startdate IS NULL OR {$prefix}.startdate = 0) ");
    }
    if(!empty($fecha_final)){
        array_push($where_clauses, " date(from_unixtime({$prefix}.enddate)) = ? ");
        array_push($where_params, $fecha_final);                
    }else{
        array_push($where_clauses, " ({$prefix}.enddate IS NULL OR {$prefix}.enddate = 0) ");
    }
    $response->where_clauses = $where_clauses;
    $response->where_params = $where_params;
    // _log($response);
    return $response;
}

/**
 * Regresa la información del curso si se tiene almacenada, en caso de no existir, se devuleve false
 * @param int $courseid Id del curso a buscar
 * @param array $params Parámetros de búsqueda del curso, serán validados contra los indicadores
 * @return stdClass|false Devuelve el registro del curso si se encuentra o false si no existe.
 */
function local_dominosdashboard_get_info_from_cache(int $courseid, array $params = array(), bool $return_regions = false){ // realizando
    global $DB;
    if(!$DB->record_exists('course', array('id' => $courseid))){
        print_error('No se encuentra el curso');
    }
    $conditions = local_dominosdashboard_get_cache_params($courseid, $params);
    $regions = ($return_regions) ? ', regiones as name' : '';
    $where = implode(' AND ', $conditions->where_clauses);
    $query = "SELECT id, courseid, enrolled_users, approved_users, percentage, value {$regions} FROM {dominos_d_cache} AS _cache WHERE {$where} ORDER BY _cache.timemodified DESC limit 1";
    // if($DB->count_records_sql($query, $conditions->where_params) > 1){
    //     _sql($query, $conditions->where_params);
    // }
    $record = $DB->get_record_sql($query, $conditions->where_params);
    if(!empty($record)){
        $record->source = 'Caché';
        return $record;
    }else{ // Crear caché
        $record = local_dominosdashboard_make_cache_for_course($courseid, $params);
        $record->source = "Consulta";
        return $record;
    }
    // return false;
}

function local_dominosdashboard_date_to_time($date){
    try{
        if(empty($date)){
            return $date;
        }
        return strtotime($date);
    }catch(Exception $e){
        return null; // Nulo en este dashboard pretende no dejar fecha en ese campo
    }
}

function local_dominosdashboard_make_cache_for_course(int $courseid, array $params, bool $returninfo = true){ // realizando
    global $DB;
    $currenttime = time();
    $conditions = local_dominosdashboard_get_cache_params($courseid, $params);
    $course_information = local_dominosdashboard_make_course_completion_information($courseid, $params);
    $where = implode(' AND ', $conditions->where_clauses);
    $query = "SELECT * FROM {dominos_d_cache} AS _cache WHERE {$where}";
    $record = $DB->get_record_sql($query, $conditions->where_params);

    $course_information->startdate = local_dominosdashboard_date_to_time($course_information->startdate);
    $course_information->enddate = local_dominosdashboard_date_to_time($course_information->enddate);
    
    if(empty($record)){ // Crear
        $record = new stdClass();
        $record->courseid = $courseid;
        $record->title = $course_information->title;
        $record->enrolled_users = $course_information->enrolled_users;
        $record->approved_users = $course_information->approved_users;
        $record->percentage = $course_information->percentage;
        $record->value = $course_information->value;
        $record->startdate = $course_information->startdate;
        $record->enddate = $course_information->enddate;

        $record->regiones = $conditions->regiones;
        $record->distritales = $conditions->distritales;
        $record->entrenadores = $conditions->entrenadores;
        $record->tiendas = $conditions->tiendas;
        $record->puestos = $conditions->puestos;
        // $record->ccosto = $conditions->ccosto; // Se quita este filtro, por ello no se encuentra
        
        $record->timemodified = $currenttime;
        $DB->insert_record('dominos_d_cache', $record);
    }else{ // Actualizar
        $record->courseid = $courseid;
        $record->title = $course_information->title;
        $record->enrolled_users = $course_information->enrolled_users;
        $record->approved_users = $course_information->approved_users;
        $record->percentage = $course_information->percentage;
        $record->value = $course_information->value;
        $record->startdate = $course_information->startdate;
        $record->enddate = $course_information->enddate;
        $record->timemodified = $currenttime;
        $DB->update_record('dominos_d_cache', $record);
    }
    if($record->regiones != null){
        $record->name = $record->regiones;
    }
    if($returninfo){
        return $course_information;
    }else{
        return true;
    }
}

function local_dominosdashboard_make_courses_cache(){ // realizando
    $startprocesstime = microtime(true); //true es para que sea calculado en segundos
    global $DB;
    $courses = local_dominosdashboard_get_courses();
    $currenttime = time();
    $regiones = local_dominosdashboard_get_catalogue('regiones');
    $count = 0;
    foreach($courses as $course){
        local_dominosdashboard_make_cache_for_course($course->id, array(), false);
        $count++;
        foreach($regiones as $region){
            $params = array();
            $params['regiones'] = $region;
            local_dominosdashboard_make_cache_for_course($course->id, $params, false);
            $count++;
        }
    }
    $query = "SELECT * FROM {dominos_d_cache} where (distritales IS NOT NULL OR entrenadores IS NOT NULL OR tiendas IS NOT NULL
    OR puestos IS NOT NULL OR ccosto IS NOT NULL)";// AND (startdate IS NOT NULL OR ) AND enddate IS NOT NULL ";
    $custom_caches = $DB->get_records_sql($query); // Caché de consultas ejecutadas anteriormente
    foreach($custom_caches as $cc){
        $original_params = local_dominosdashboard_get_parameters_from_cache_record($cc);
        local_dominosdashboard_make_cache_for_course($cc->courseid, $original_params, false);
        $count++;
    }
    $finalprocesstime = microtime(true);
    $functiontime = $finalprocesstime - $startprocesstime; //este resultado estará en segundos
    return "Se ejecutaron {$count} actualizaciones en {$functiontime} segundos";
}

/**
 * Devuelve los parámetros con los que fue buscado el registro del caché de curso
 * @param stdClass|false Registro del caché del curso
 * @return array Devuelve un arreglo clave valor
 */
function local_dominosdashboard_get_parameters_from_cache_record($record){
    $params = array();
    if(empty($record)){
        return params();
    }
    $record = (array) $record;
    $indicators = local_dominosdashboard_get_indicators(); 
    foreach($indicators as $indicator){ // Sólo se agregan los indicadores, coninciden con la tabla dominos_d_cache
        if(array_key_exists($indicator, $record)){
            $value = $record[$indicator];
            if(!empty($value)){
                if(strpos($value, local_dominosdashboard_cache_separator) !== false){
                    $value = explode(local_dominosdashboard_cache_separator, $value);
                }
                $params[$indicator] = $record[$indicator];
            }
        }
    }
    $fecha_inicial = local_dominosdashboard_get_value_from_params($record, 'fecha_inicial');
    $fecha_final = local_dominosdashboard_get_value_from_params($record, 'fecha_final');
    if(!empty($fecha_inicial)){
        $params['fecha_inicial'] = $fecha_inicial;
    }
    if(!empty($fecha_final)){
        $params['fecha_final'] = $fecha_final;
    }
    return $params;
}

function local_dominosdashboard_make_historic_report(int $courseid){
    global $DB;
    $currenttime = time();
    $course = $DB->get_record('course', array('id' => $courseid), 'id, shortname, fullname');
    if($course == false){
        return false;
    }
    $course_information = local_dominosdashboard_get_course_information($course->id, $kpis = false, $activities = false, $params = array());
    local_dominosdashboard_insert_historic_record($course_information, $currenttime, $course);
    foreach (local_dominosdashboard_get_indicators() as $indicator) {
        foreach (local_dominosdashboard_get_catalogue($indicator) as $item) {
            $params = array();
            $params[$indicator] = $item;
            $course_information = local_dominosdashboard_get_course_information($courseid, $kpis = false, $activities = false, $params = array());
            local_dominosdashboard_insert_historic_record($course_information, $currenttime, $course, $indicator, $item);
        }
    }
}

function local_dominosdashboard_insert_historic_record(stdClass $course_information, $currenttime, stdClass $course, $filterid = "", $filtertext = ""){
    global $DB;
    // _log($course);
    $record = new stdClass();
    // $record->id             = ''; // autoincrement
    $record->courseid       = intval($course->id);
    $record->shortname      = $course->shortname;
    $record->fullname       = $course->fullname;
    $record->enrolled_users = $course_information->enrolled_users;
    $record->approved_users = $course_information->approved_users;
    $record->filterid       = $filterid;
    $record->filtertext     = $filtertext;
    $record->timecreated    = $currenttime;
    return $DB->insert_record('dominos_historico', $record);
}

function local_dominosdashboard_get_historic_reports(int $courseid, $params = array()){
    if(RETURN_RANDOM_DATA){
        $response = array();
        for($i = 0; $i < 10; $i++){
            $temp = new stdClass();
            $temp->id = $i;
            $temp->courseid = $courseid;
            $temp->shortname = "Curso " . $i;
            $temp->fullname = "" . $i;
            $temp->enrolled_users = random_int(0, MAX_RANDOM_NUMBER);
            $temp->approved_users = random_int(0, $temp->enrolled_users);
            $temp->filterid = "";
            $temp->filtertext = "";
            $temp->timecreated = "";
            $temp->fecha = date('d-m-Y');
            array_push($response, $temp);
        }
        return $response;
    }
    global $DB;
    return $DB->get_records('dominos_historico', array('courseid' => $courseid), ' fecha desc ', '*, from_unixtime(timecreated) as fecha' , $limitfrom = 0, $limintnum = 10);
    // Pass: Subitus2019! ALTER TABLE mdl_dominos_historico CHANGE COLUMN course courseid BIGINT(10) NULL DEFAULT NULL AFTER id;
}

function local_dominosdashboard_get_historic_dates(int $courseid){
    global $DB;
    $query = "SELECT distinct DATE(FROM_UNIXTIME(timecreated)) FROM {dominos_historico} WHERE courseid = ?";
    return $DB->get_recordset_sql($query, array($courseid));
}

function local_dominosdashboard_has_empty(... $params){
    foreach($params as $param){
        if(empty($param)){
            // if($param !== 0){ // Acepta el 0 como valor válido
                return true;
            // }
        }
    }
    return false;
}

function local_dominosdashboard_delete_kpi(array $params){
    try{
        global $DB;
        $id = local_dominosdashboard_get_value_from_params($params, 'id', false, true);
        // $kpi = $DB->get_record('dominos_kpi_list', array('id' => $id));
        // if($kpi === false){
        //     _log('No se encontró kpi');
        //     return 'No se encontró kpi a eliminar';
        // }
        // $DB->delete_records('dominos_kpis',  array('kpi' => $kpi->kpi));
        $DB->delete_records('dominos_kpi_list',  array('id' => $id));
        return 'ok';
    }catch(Exception $e){
        _log('Eliminar KPI EXCEPTION', $e);
        return 'Por favor, inténtelo de nuevo';
    }
}

function local_dominosdashboard_update_kpi(array $params){
    try{
        $id = local_dominosdashboard_get_value_from_params($params, 'id', false, true);
        global $DB;
        $kpi = $DB->get_record('dominos_kpi_list', array('id' => $id));
        if($kpi === false){
            _log('No se encontró kpi');
            return 'No se encontró KPI a editar';
        }
        
        $key = local_dominosdashboard_get_value_from_params($params, 'kpi_key', false, true);
        $name = local_dominosdashboard_get_value_from_params($params, 'kpi_name', false, true);
        $type = local_dominosdashboard_get_value_from_params($params, 'kpi_type', false, true);
        $calculation = local_dominosdashboard_get_value_from_params($params, 'kpi_calculation', false, true);
        $enabled = local_dominosdashboard_get_value_from_params($params, 'kpi_enabled', false, true);
        if($kpi->kpi_key != $key){
            if($DB->record_exists('dominos_kpi_list', array('kpi_key' => $key))){
                return "Ya existe un KPI con esta clave";
            }
        }
        $kpi->kpi_key = $key;
        $kpi->name = $name;
        $kpi->type = $type;
        $kpi->enabled = $enabled;
        $kpi->calculation = $calculation;
        $DB->update_record('dominos_kpi_list', $kpi);
        return 'ok';
    }catch(Exception $e){
        _log('Editar KPI EXCEPTION', $e);
        return 'Por favor, inténtelo de nuevo';
    }
}

DEFINE('local_dominosdashboard_cache_separator', '##');

function local_dominosdashboard_create_kpi(array $params){
    try{
        global $DB;
        $key = local_dominosdashboard_get_value_from_params($params, 'kpi_key', false, true);
        $name = local_dominosdashboard_get_value_from_params($params, 'kpi_name', false, true);
        $type = local_dominosdashboard_get_value_from_params($params, 'kpi_type', false, true);
        $enabled = local_dominosdashboard_get_value_from_params($params, 'kpi_enabled', false, true);
        $calculation = local_dominosdashboard_get_value_from_params($params, 'kpi_calculation', false, true);
        if(local_dominosdashboard_has_empty($key, $name, $type, $enabled, $calculation)){
            _log('Datos vacíos en creación de kpi');
            return 'Hay datos vacíos';
        }
        $existent = $DB->record_exists('dominos_kpi_list', array('kpi_key' => $key));
        if($existent){
            return "La clave ya está siendo utilizada en otro KPI, por favor utilice otra";
        }
        // $DB->record_exists($table, $conditions_array);
        $kpi = new stdClass();
        $kpi->kpi_key = $key;
        $kpi->name = $name;
        $kpi->type = $type;
        $kpi->enabled = $enabled;
        $kpi->calculation = $calculation;
        $insertion = $DB->insert_record('dominos_kpi_list', $kpi);
        
        return "ok";
    }catch(Exception $e){
        _log('Error al crear kpi', $e);
        return 'Por favor, inténtelo de nuevo';
    }
}

function local_dominosdashboard_get_kpi_list(string $type = 'all'){
    global $DB;
    switch($type){
        case 'all':
            return $DB->get_records('dominos_kpi_list');
        break;
        case 'menu':
            return $DB->get_records_menu('dominos_kpi_list', array('enabled' => 1), '', "id, CONCAT(name, ' (', kpi_key, ')') as name");

        break;
        case 'list':
            return $DB->get_records('dominos_kpi_list', array('enabled' => '1'));

        break;
    }
}

if(!function_exists('_sql')){
    /**
     * Imprime los parámetros enviados con la función error_log()
     * @param mixed ...$parameters Recibe varios parámetros e imprime su valor en el archivo log, para pasarlos a cadena de texto se utiliza print_r($var, true)
     */
    function _sql(string $query, array $params = array(), string $title = ''){
        global $CFG;
        $prefix = $CFG->prefix;
        $title .= ' ';
        $original = $query;
        $query = str_replace('{', $prefix, $query);
        $query = str_replace('}', '', $query);
        // buscar
        $error = "";
        $num_params = count($params);
        $nested_params = substr_count($query, '?');
        $showParams = false;
        $replaceParams = false;
        if($nested_params > $num_params){
            $error .= "Parámetros necesitados: {$nested_params} Parámetros enviados: {$num_params}";
            $showParams = true;
        }elseif($nested_params < $num_params){
            $replaceParams = $showParams = true;
            $error .= "Parámetros necesitados: {$nested_params} Parámetros enviados: {$num_params}";
        }else{
            $replaceParams = true;
        }
        if($replaceParams){
            for($i = 0; $i < $nested_params; $i++){
                $query = local_dominosdashboard_str_replace_first('?', "'".$params[$i] . "'", $query);
            }
        }
        if(!$showParams || empty($params)){ $params = ''; }
        _log($title, $query, $params, $error);
    }
}


/**
 * Devuelve la cadena con el texto remplazado en solo la primera ocurrencia
 * @param string $buscar Texto a buscar
 * @param string $remplazar Texto con el que será remplazado
 * @param string $str Cadena donde se remplazará la primera ocurrencia
 * @return string texto en el cual se remplaza sólo la primera ocurrencia
 */
function local_dominosdashboard_str_replace_first($buscar, $remplazar, $str){
    $pos = strpos($str, $buscar);
    if ($pos !== false) {
        $newstring = substr_replace($str, $remplazar, $pos, strlen($buscar));
    }
    return $newstring;
    // $buscar = '/'.preg_quote($buscar, '/').'/';
    // return preg_replace($buscar, $remplazar, $str, 1);
}

DEFINE('local_dominosdashboard_course_users_pagination', 1);
DEFINE('local_dominosdashboard_all_users_pagination', 2);
DEFINE('local_dominosdashboard_suspended_users_pagination', 3);
DEFINE('local_dominosdashboard_actived_users_pagination', 4);
DEFINE('local_dominosdashboard_oficina_central_pagination', 5);

/**
 * Regresa información para la paginación de usuarios compatible con datatables
 */
function local_dominosdashboard_get_paginated_users(array $params, $type = local_dominosdashboard_course_users_pagination){
    // $courseid = local_dominosdashboard_get_value_from_params($params, 'courseid');
    // $courseid = intval($courseid);
    // _log($params);

    // $courseids = implode(',', $courseids);
    
    if(empty($params)){
        return array(); // Dificilmente se podrá calcular esto pues datatables siempre envía estos parámetros, probablemente se trate de un error
    }
    switch($type){
        case local_dominosdashboard_course_users_pagination:
        // $enrol_sql_query = " user.id IN " . local_dominosdashboard_get_enrolled_userids($courseids, $desde = '', $hasta = '', $params);
        $email_provider = local_dominosdashboard_get_email_provider_to_allow();
        if(!empty($email_provider)){
            $whereEmailProvider = " AND email LIKE '%{$email_provider}'"; 
        }else{
            $whereEmailProvider = ""; 
        }
        $enrol_sql_query = " user.id > 1 AND user.deleted = 0 {$whereEmailProvider}";
        break;
    }
    global $DB;
    $draw = $params['draw'];
    $row = $params['start'];
    $rowperpage = $params['length']; // Rows display per page
    $columnIndex = $params['order'][0]['column']; // Column index
    $columnName = $params['columns'][$columnIndex]['data']; // Column name
    $columnSortOrder = $params['order'][0]['dir']; // asc or desc
    $searchValue = $params['search']['value']; // Search value

    ## Search 
    $searchQuery = " WHERE " . $enrol_sql_query;
    $searched = '';
    if(!empty($searchValue)){
        $searched = $columnName;
    }
    $queryParams = array();
    
    
    $report_info = local_dominosdashboard_get_report_columns($type, $searched);
    // _log($report_info);

    ## Fetch records
    $select_sql = $report_info->select_sql;
    $select_slim = $report_info->slim_query;
    $limit = " LIMIT {$row}, {$rowperpage}";
    if($rowperpage == -1){
        $limit = "";
    }
    
    ## Total number of records without filtering
    $query = 'SELECT COUNT(*) FROM {user} AS user WHERE ' . $enrol_sql_query;
    // _sql('Sin filtro ', $query, $queryParams);
    $totalRecords = $DB->count_records_sql($query);//($table, $conditions_array);
    // _log('Elementos totales', $totalRecords);    
    if($searchValue != ''){
        if($columnName == 'name'){ // Campo por defecto name
        // if(strpos('user.name',$columnName) !== false){
            $searchValue = "%{$searchValue}%";
            $searchQuery = " WHERE " . $enrol_sql_query . " AND CONCAT(firstname, ' ', lastname) like ? ";
            // array_push($queryParams, $searchValue);
        }elseif(strpos($columnName, 'custom_') !== false){ // Campo que requiere having
        // }elseif(strpos('user.',$columnName) !== false){
            $searchValue = "%{$searchValue}%";
            $searchQuery = " WHERE $enrol_sql_query HAVING {$columnName} like ?  " ;
        }else{ // Campo estándar de la tabla user
            $searchValue = "%{$searchValue}%";
            $searchQuery = " WHERE {$columnName} like ? AND " . $enrol_sql_query;
        }
        $searched = $columnName;
        array_push($queryParams, $searchValue);
    }

    ## Total number of record with filtering
    $query = "SELECT count(*) FROM (SELECT {$select_slim} FROM {user} AS user {$searchQuery}) AS t1";
    $queryParamsFilter = array($searchValue);
    
    $totalRecordwithFilter = $DB->count_records_sql($query, $queryParamsFilter);
    // _log('Elementos filtrados', $totalRecordwithFilter);
    // _sql('Filtrados ', $query, $queryParamsFilter);
    
    ## Consulta de los elementos
    // $queryParams = array();
    $query = "select {$select_sql} from {user} AS user {$searchQuery} order by {$columnName} {$columnSortOrder} {$limit}";
    // _log($query);
    // _log($queryParams);
    // _sql('Consulta de elementos ', $query, $queryParams);
    $records = $DB->get_records_sql($query, $queryParams);
    // _sql($query, $queryParams);

    ## Response
    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecordwithFilter,
        "iTotalDisplayRecords" => $totalRecords,
        "aaData" => array_values($records)
    );
    $json_response = json_encode($response);
    return $json_response;
}

function local_dominosdashboard_get_report_fields(){
    $default_fields = local_dominosdashboard_get_default_report_fields();
    $custom_fields = local_dominosdashboard_get_custom_report_fields();
    return array($default_fields, $custom_fields);
}

/**
 * Devuelve true si es un campo personalizado o false si es estándar de la tabla user de Moodle
 * @param string $field Nombre clave del campo
 * @return bool true si es campo personalizado o false si es de la tabla user
 */
function local_dominosdashboard_is_custom_field(string $field){
    // is_number();
    $response = is_number($field);
    return $response;
}

$cache_report_columns = null;
function local_dominosdashboard_get_report_columns(int $type = local_dominosdashboard_course_users_pagination, $searched = '', $prefix = 'user.'){
    global $cache_report_columns;
    if($cache_report_columns !== null){
        return $cache_report_columns;
    }
    $select_sql = array("{$prefix}id as id"); // Id como columna inicial por defecto para cumplir con reglas de data manipulation api
    $ajax_names = array();
    $visible_names = array();
    $slim_query = array("id");

    $report_fields = local_dominosdashboard_get_report_fields_in_order();

    if(empty($report_fields)){
        array_push($report_fields, 'name'); // Agregar name por si no se encuentra ningún campo
    }

    foreach($report_fields as $field_key => $field_name){
        if( local_dominosdashboard_is_custom_field($field_key) ){
            $new_key = "custom_" .$field_key;
            $select_key = " COALESCE((SELECT data FROM {user_info_data} AS uif WHERE uif.userid = user.id AND fieldid = {$field_key} LIMIT 1), '') AS {$new_key}";
            array_push($ajax_names, $new_key);
            array_push($select_sql, $select_key);
            array_push($visible_names, $field_name);
            if($new_key == $searched){
                array_push($slim_query, $select_key);
            }
        }else{
            switch ($field_key) {
                case 'name':
                    array_push($ajax_names, $field_key);
                    array_push($select_sql, "concat({$prefix}firstname, ' ', {$prefix}lastname ) as name");
                    array_push($visible_names, 'Nombre');
                break;

                case 'suspended':
                    if($field_key == $searched){
                        array_push($slim_query, $prefix . $field_key);
                    }
                    $query = "IF({$prefix}{$field_key} = 0, 'Activo', 'Suspendido') AS {$field_key}";
                    array_push($ajax_names, $field_key);
                    array_push($select_sql, $query);
                    array_push($visible_names, $field_name);
                break;
                
                default:
                    if($field_key == $searched){
                        array_push($slim_query, $prefix . $field_key);
                    }
                    array_push($ajax_names, $field_key);
                    array_push($select_sql, $prefix . $field_key);
                    array_push($visible_names, $field_name);
                break;
            }

        }
    }

    global $DB;
    $courseids = get_config('local_dominosdashboard', 'reportcourses');
    if(empty($courseids)){
        print_error('No se tienen cursos configurados');
    }
    $courses = $DB->get_records_sql("SELECT id, shortname, fullname FROM {course} WHERE id IN ({$courseids})");
    if(empty($courses)){
        print_error('No se encontraron los cursos previamente configurados');
    }
    foreach($courses as $course){
        $courseid = $course->id;
        $coursename = $course->shortname;

        $key_name = 'custom_completion' . $courseid;
        $field = "IF( EXISTS( SELECT id FROM {course_completions} AS cc WHERE user.id = cc.userid 
        AND cc.course = {$courseid} AND cc.timecompleted IS NOT NULL), 'Finalizado', 'No finalizado') as {$key_name}";
        array_push($select_sql, $field);
        array_push($ajax_names, $key_name);
        if($key_name == $searched){
            array_push($slim_query, $field);
        }
        array_push($visible_names, 'Estado ' . $coursename);

        $grade_item = local_dominosdashboard_get_course_grade_item_id($courseid);

        if($grade_item !== false){
            $key_name = "custom_grade" . $grade_item;
            $field = "COALESCE( ( SELECT ROUND(gg.finalgrade, 0) FROM {grade_grades} AS gg
            WHERE user.id = gg.userid AND gg.itemid = {$grade_item}), '-') as {$key_name}";
            $field_slim = $field;
            array_push($select_sql, $field);
            if($key_name == $searched){
                array_push($slim_query, $field_slim);
            }
            array_push($ajax_names, $key_name);
            array_push($visible_names, 'Calificación ' . $coursename);


            // "(SELECT ROUND(gg.finalgrade,0)
            // FROM prefix_grade_grades AS gg
            // JOIN prefix_grade_items AS gi 
            // JOIN prefix_course AS c ON gi.courseid = c.id
            // WHERE  c.shortname ='icawebdom' 
            // AND  gg.userid =u.id
            // AND gi.id = gg.itemid
            // AND gi.itemtype = 'course'
            // )";
        }else{
            $key_name = "sin_calificacion".$courseid;
            $field = "'-' as {$key_name}";
            array_push($select_sql, $field);
            array_push($ajax_names, $key_name);
            array_push($visible_names, $coursename . " --Sin calicación--");
        }
    }

    $imploded_sql = implode(', 
    ', $select_sql);
    $imploded_slim = implode(', 
    ', $slim_query);
    $ajax_code = "";
    $ajax_printed_rows = '';
    $ajax_link_fields = '';
    $count = 0;
    foreach($ajax_names as $an){
        $islink = true;
        switch($an){
            case 'link_suspend_user':
                $ajax_code .= "{data: '{$an}', render: 
                function ( data, type, row ) { 
                    parts = data.split('||');
                    id = parts[0];
                    suspended = parts[1];
                    texto = (suspended == '1') ? 'Quitar suspensión' : 'Suspender';
                    clase = (suspended == '1') ? 'btn Success' : 'btn Danger';
                    return '<a target=\"_blank\" class=\"' + clase + '\" href=\"administrar_usuarios.php?suspenduser=1&id=' + id + '\">' + texto + '</a>'; }  
                }, ";
            break;
            case 'link_edit_user':
                $ajax_code .= "{data: '{$an}', render: 
                function ( data, type, row ) { return '<a target=\"_blank\" class=\"btn btn-info\" href=\"administrar_usuarios.php?id=' + data + '\">Editar usuario</a>'; }  }, ";
                // $ajax_code .= "{data: '{$an}', render: function ( data, type, row ) { return data; }  }, ";            
            break;
            // case 'name':
                // $ajax_code .= "{data: '{$an}', render: 
                //     function ( data, type, row ) { 
                //         parts = data.split('||');
                //         return '<a class=\"\" href=\"administrar_usuarios.php?id=' + parts[1] + '\">' + parts[0] + '</a>'; 
                //     } 
                // }, ";
                // $ajax_code .= "{data: '{$an}', render: function ( data, type, row ) { return data; }  }, ";
            // break;
            // case 'link_libro_calificaciones':
            //     global $CFG;
            //     $ajax_code .= "{data: '{$an}', render: function ( data, type, row ) 
            //         { return '<a target=\"_blank\" class=\"btn btn-info\" href=\"{$CFG->wwwroot}/grade/report/user/index.php?id={$custom_information}&userid=' + data + '\">Libro de calificaciones</a>'; }  
            //     }, ";
            //     break;
            default:
            if(strpos($an, 'sin_calificacion') !== false){ // No buscar esta columna
                $ajax_code .= "{data: '{$an}', render: function ( data, type, row ) { return data; }  }, ";
                $ajax_printed_rows .= ($count . ',');
            }else{ // Columnas de la tabla usuarios
                $islink = false;
                $ajax_printed_rows .= ($count . ',');
                $ajax_code .= "{data: '{$an}' },";
            }
            break;
        }
        if($islink){
            $ajax_link_fields .= ($count . ","); // No permite ordenar este campo
        }
        $count++;
    }
    // $ajax_code .= "{data: '{$an}', render: function ( data, type, row ) // Ejemplo agregando una columna de alguna ya generada
    //                 { return 'Otra cosa con el mismo {$an}' + data; } // Ejemplo agregando una columna de alguna ya generada
    //             }, "; // Ejemplo agregando una columna de alguna ya generada
    $table_code = "";
    foreach($visible_names as $vn){
        $table_code .= "<th>{$vn}</th>";
    }
    // $table_code .= "<th>Una última columna</th>"; // Ejemplo agregando una columna de alguna ya generada
    $response = new stdClass();
    // $response->select_sql = $prefix . 'id, ' . $imploded_sql;
    $response->select_sql = $imploded_sql;
    $response->ajax_code = $ajax_code;
    $response->ajax_printed_rows = $ajax_printed_rows;
    $response->table_code = $table_code;
    $response->slim_query = $imploded_slim;
    $response->column_names = $ajax_names;
    $response->visible_names = $visible_names;
    // $response->default_fields = $default_fields;
    // $response->custom_fields = $custom_fields;
    $response->ajax_link_fields = $ajax_link_fields;

    $cache_report_columns = $response;
    return $response;
}

$default_report_fields_cache = null;
/**
 * Devuelve un arreglo de nombres de campos de la tabla de moodle user. Hay un campo diferente llamado fullname que equivale a CONCAT(firstname, ' ', lastname)
 * @return array ['firstname','lastname','email' ... ]
 */
function local_dominosdashboard_get_default_report_fields(){
    global $default_report_fields_cache;
    if($default_report_fields_cache !== null){
        return $default_report_fields_cache;
    }
    if($config = get_config('local_dominosdashboard', 'reportdefaultfields')){
        if(!empty($config)){
            $menu = local_dominosdashboard_get_default_profile_fields();
            $configs = explode(',', $config);
            $response = array();
            foreach($configs as $r){
                if(array_key_exists($r, $menu)){
                    $response[$r] = $menu[$r];
                }
            }
            $default_report_fields_cache = $response;
            return $response;
        }
    }
    return array();
}

$custom_report_fields_cache = null;
/**
 * Devuelve un arreglo de id => "nombre de campo" de los campos de usuario personalizados
 * @return array [1 => '',2 => '',3 => '', ...]
 */
function local_dominosdashboard_get_custom_report_fields(){
    global $custom_report_fields_cache;
    if($custom_report_fields_cache !== null){
        return $custom_report_fields_cache;
    }
    if($config = get_config('local_dominosdashboard', 'reportcustomfields')){
        if(!empty($config)){
            $menu = local_dominosdashboard_get_custom_profile_fields($config);
            $configs = explode(',', $config);
            $response = array();
            foreach($configs as $r){
                if(array_key_exists($r, $menu)){
                    $response[$r] = $menu[$r];
                }
            }
            $custom_report_fields_cache = $response;
            return $response;
        }
    }
    return array();
}


/**
 * Devuelve un menú (clave => valor ... ) de los campos de usuario personalizados
 * @param string $ids ids de los campos personalizados. Ejemplo: 1,2,3
 * @return array|false Menú de los campos de usuario personalizados o false si no se encuentran
 */
function local_dominosdashboard_get_custom_profile_fields(string $ids = ''){
    global $DB;
    if(!empty($ids)){
        return $DB->get_records_sql_menu("SELECT id, name FROM {user_info_field} WHERE id IN ({$ids}) ORDER BY name");
    }
    return $DB->get_records_menu('user_info_field', array(), 'name', "id, name");
}

/**
 * Devuelve la lista de campos que contiene la tabla user
 * @param bool $form true elimina las opciones username, firstname, lastname
 * @return array
 */
function local_dominosdashboard_get_default_profile_fields(){
    $fields = array(
        'name' => "Nombre",
        'username' => 'Nombre de usuario', 
        'email' => 'Dirección Email',
        'firstname' => 'Nombre (s)', 
        'lastname' => 'Apellido (s)', 
        'address' => 'Dirección', 
        'phone1' => 'Teléfono', 
        'phone2' => 'Teléfono móvil', 
        'icq' => 'Número de ICQ', 
        'skype' => 'ID Skype', 
        'yahoo' => 'ID Yahoo', 
        'aim' => 'ID AIM', 
        'msn' => 'ID MSN', 
        'department' => 'Departamento',
        'institution' => 'Institución', 
        'idnumber' => 'Número de ID', 
        'suspended' => 'Estatus',
        // 'lang', 
        // 'timezone', 
        'description' => 'Descripción',
        'city' => 'Ciudad', 
        'url' => 'Página web', 
        'country' => 'País',
    );
    return $fields;
}

/**
 * Devuelve los campos de reporte ordenados según la configuración o añade los últimos en caso de no existir
 * @return array
 */
function local_dominosdashboard_get_report_fields_in_order(){
    list($default_report_fields, $custom_report_fields) = local_dominosdashboard_get_report_fields();
    $all_filters = $default_report_fields + $custom_report_fields;

    $update_config = false;
    $original_config = $keys = get_config('local_dominosdashboard', 'sort_report_fields');
    if($keys === false){ // No existe orden, crearlo
        $keys = array_keys($all_filters);
        $update_config = true;
    }else{
        $keys = explode(',', $keys);
    }

    // ejemplo: $keys = ['username', 'name', 1, 3];

    foreach($all_filters as $filter_key => $filter_name){ // Agregando los filtros que no estaban antes
        if(!in_array($filter_key, $keys)){
            array_push($keys, $filter_key);
        }
    }

    $response = array();
    foreach ($keys as $key_id => $key) {
        if(!array_key_exists($key, $all_filters)){ // El filtro fue eliminado de ajustes
            unset($keys[$key_id]);
        }else{ // El filtro sigue en ajustes
            $response[$key] = $all_filters[$key];
        }
    }
    
    $keys_with_order = implode(',', array_keys($response));
    if($update_config || $keys_with_order != $original_config){
        set_config('sort_report_fields', $keys_with_order, 'local_dominosdashboard');
    }
    return $response;
}

function local_dominosdashboard_set_new_order($key, string $action){
    // _log($key, $action);
    if(!($action == 'up' || $action == 'down')){
        print_error("Acción '{$action}' no soportada");
    }
    $fields_in_order = local_dominosdashboard_get_report_fields_in_order();
    $keys_fields_in_order = array_keys($fields_in_order);
    // _log($key, $keys_fields_in_order);
    if(is_number($key)){
        $position = array_search($key, $keys_fields_in_order);
    }else{
        $position = array_search($key, $keys_fields_in_order, true);
    }
    if($position !== false){
        switch($action){
            case 'up':
                $new_config = local_dominosdashboard_array_up($keys_fields_in_order, $position);
            break;
            case 'down':
                $new_config = local_dominosdashboard_array_down($keys_fields_in_order, $position);
            break;
        }
        $new_config = implode(',', $new_config);
        // _log($new_config);
        set_config('sort_report_fields', $new_config, 'local_dominosdashboard');
    }else{
        print_error('No se encuentra el filtro');
    }
    return true;
}

function local_dominosdashboard_array_down($array,$position) {
    // _log($position, 'Antes', $array);
    if( count($array)-1 > $position ) {
		$response = array_slice($array,0,$position,true);
		$response[] = $array[$position+1];
		$response[] = $array[$position];
		$response += array_slice($array,$position+2,count($array),true);
		// return($response);
    } else { $response = $array; }
    // _log('Resultado', $response);
    return $response;
}
function local_dominosdashboard_array_up($array,$position) {
    // _log($position, 'Antes', $array);
	if( $position > 0 and $position < count($array) ) {
		$response = array_slice($array,0,($position-1),true);
		$response[] = $array[$position];
		$response[] = $array[$position-1];
		$response += array_slice($array,($position+1),count($array),true);
		// return($response);
	} else { $response = $array; }
    // _log('Resultado', $response);
    return $response;
}

function local_dominosdashboard_export_configurable_report(){
    global $CFG;
    require_once($CFG->dirroot.'/lib/excellib.class.php');

    $information = local_dominosdashboard_get_configurable_report_records();

    $currentdate = date("d-m-Y_H:i:s");
    $filename = 'Reporte_personalizado_' . $currentdate;

    $report_columns = local_dominosdashboard_get_report_columns();

    /*
     $response->ajax_names = $ajax_names;
     $response->visible_names = $visible_names;
     */
    // Headers

    
    // foreach ($report_columns->column_names as $columnName) {
        
    // }
    // $column_reports

    $matrix = array();

    // Headers
    array_push($matrix, array_values($report_columns->visible_names));

    foreach ($information as $line) { // Obtener sólo los campos del reporte
        $line = (array) $line;
        $line = array_values($line);
        array_shift($line);
        array_push($matrix, $line);
    }

    $downloadfilename = clean_filename($filename);
    // Creating a workbook.
    $workbook = new MoodleExcelWorkbook("-");
    // Sending HTTP headers.
    $workbook->send($downloadfilename);
    // Adding the worksheet.
    $myxls = $workbook->add_worksheet($filename);

    foreach ($matrix as $ri => $col) {
        foreach ($col as $ci => $cv) {
            $myxls->write_string($ri, $ci, $cv);
        }
    }

    $workbook->close();
    exit;    
}

function local_dominosdashboard_get_configurable_report_records(){
    $type = local_dominosdashboard_course_users_pagination;

    switch($type){
        case local_dominosdashboard_course_users_pagination:
        $email_provider = local_dominosdashboard_get_email_provider_to_allow();
        if(!empty($email_provider)){
            $whereEmailProvider = " AND email LIKE '%{$email_provider}'"; 
        }else{
            $whereEmailProvider = ""; 
        }
        $enrol_sql_query = " user.id > 1 AND user.deleted = 0 {$whereEmailProvider}";
        break;
    }
    global $DB;
    
    $report_info = local_dominosdashboard_get_report_columns($type);
    $queryParams = array();
    $searchQuery = " WHERE " . $enrol_sql_query;
    $select_sql = $report_info->select_sql;
    $limit = '';
    $columnName = 'name';
    $columnSortOrder = 'ASC';
    $query = "select {$select_sql} from {user} AS user {$searchQuery} order by {$columnName} {$columnSortOrder} {$limit}";
    // _sql($query, $queryParams);
    $records = $DB->get_records_sql($query, $queryParams);

    return $records;
}
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

// Agrega enlace al Dashboard en el menú lateral de Moodle
function local_dominosdashboard_extend_navigation(global_navigation $nav) {
    $has_capability = has_capability('local/dominosdashboard:view', context_system::instance());
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
    if($has_capability){
        global $CFG;
        $node = $nav->add (
            get_string('pluginname', 'local_dominosdashboard'),
            new moodle_url( $CFG->wwwroot . '/local/dominosdashboard/dashboard.php' )
        );
        $node->showinflatnavigation = true;
        $node = $nav->add (
            'Configuraciones ' . get_string('pluginname', 'local_dominosdashboard'),
            new moodle_url( $CFG->wwwroot . '/admin/settings.php?section=local_dominosdashboard' )
        );
        $node->showinflatnavigation = true;
        $node = $nav->add (
            'Subir kpis en csv ' . get_string('pluginname', 'local_dominosdashboard'),
            new moodle_url( $CFG->wwwroot . '/local/dominosdashboard/subir_archivo.php' )
        );
        $node->showinflatnavigation = true;
        if(LOCALDOMINOSDASHBOARD_DEBUG){
            $node = $nav->add (
                'Directorio del plugin ' . get_string('pluginname', 'local_dominosdashboard'),
                new moodle_url( $CFG->wwwroot . '/local/dominosdashboard/' )
            );
            $node->showinflatnavigation = true;
            // $node = $nav->add (
            //     'Prueba de peticiones por pestaña ' . get_string('pluginname', 'local_dominosdashboard'),
            //     new moodle_url( $CFG->wwwroot . '/local/dominosdashboard/todos_los_cursos.php' )
            // );
            // $node->showinflatnavigation = true;
        }
    }
}

/**
 * Serve the files from the MYPLUGIN file areas
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param stdClass $context the context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool false if the file not found, just send the file otherwise and do not return anything
 */
function local_dominosdashboard_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    // Check the contextlevel is as expected - if your plugin is a block, this becomes CONTEXT_BLOCK, etc.
    // if ($context->contextlevel != CONTEXT_MODULE) {
    //     return false; 
    // }
 
    // Make sure the filearea is one of those used by the plugin.
    if ($filearea !== 'local_dominosdashboard') {
        return false;
    }
 
    // Make sure the user is logged in and has access to the module (plugins that are not course modules should leave out the 'cm' part).
    require_login($course, true, $cm);
 
    // Check the relevant capabilities - these may vary depending on the filearea being accessed.
    // if (!has_capability('mod/MYPLUGIN:view', $context)) {
    //     return false;
    // }
 
    // Leave this line out if you set the itemid to null in make_pluginfile_url (set $itemid to 0 instead).
    $itemid = array_shift($args); // The first item in the $args array.
 
    // Use the itemid to retrieve any relevant data records and perform any security checks to see if the
    // user really does have access to the file in question.
 
    // Extract the filename / filepath from the $args array.
    $filename = array_pop($args); // The last item in the $args array.
    if (!$args) {
        $filepath = '/'; // $args is empty => the path is '/'
    } else {
        $filepath = '/'.implode('/', $args).'/'; // $args contains elements of the filepath
    }
 
    // Retrieve the file from the Files API.
    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'local_dominosdashboard', $filearea, $itemid, $filepath, $filename);
    if (!$file) {
        return false; // The file does not exist.
    }
 
    // We can now send the file back to the browser - in this case with a cache lifetime of 1 day and no filtering. 
    send_stored_file($file, 86400, 0, $forcedownload, $options);
}

DEFINE("LOCALDOMINOSDASHBOARD_CATEGORY_PARENT_NAME", "parent_category");
DEFINE("LOCALDOMINOSDASHBOARD_DEBUG", true);

DEFINE("KPI_NA", 0);
DEFINE("KPI_OPS", 1);
DEFINE("KPI_HISTORICO", 2);
DEFINE("KPI_SCORCARD", 3);

DEFINE("LOCALDOMINOSDASHBOARD_NOFILTER", "__NOFILTER__");

DEFINE('LOCALDOMINOSDASHBOARD_PROGRAMAS_ENTRENAMIENTO', 1);
DEFINE('LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS', 2);
DEFINE('LOCALDOMINOSDASHBOARD_COURSE_KPI_COMPARISON', 3);

function local_dominosdashboard_get_course_tabs(){
    return $tabOptions = [
        LOCALDOMINOSDASHBOARD_PROGRAMAS_ENTRENAMIENTO => 'Programas de entrenamiento',
        LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS => 'Campañas',
        LOCALDOMINOSDASHBOARD_COURSE_KPI_COMPARISON => 'Comparación de kpis',
    ];
}

function local_dominosdashboard_get_course_tabs_as_js_script(){
    $result = json_encode(local_dominosdashboard_get_course_tabs());
    return "<script> var ldm_course_tabs = {$result}; </script>";
}

function local_dominosdashboard_get_KPIS(){
    return [
        // KPI_NA => "N/A", // No kpi
        KPI_OPS => "OPS MÉXICO W",
        KPI_HISTORICO => "Reporte de Casos Histórico por tiendas",
        KPI_SCORCARD => "Scorcard RRHH"
    ];
}

DEFINE("COMPLETION_DEFAULT", 1);
DEFINE("COMPLETION_DEFAULT_AND_GRADE", 2);
DEFINE("COMPLETION_BY_GRADE", 3);
DEFINE("COMPLETION_BY_BADGE", 4);
DEFINE("COMPLETION_BY_ACTIVITY", 5);
DEFINE("COMPLETION_BY_AVG", 6);
// DEFINE("COMPLETION_BY_ATTENDANCE", 5);

DEFINE('DOMINOSDASHBOARD_INDICATORS', 'regiones/distritos/entrenadores/tiendas/puestos');
DEFINE('DOMINOSDASHBOARD_INDICATORS_FOR_KPIS', 'regiones/distritos/tiendas/periodos');
DEFINE('DOMINOSDASHBOARD_CHARTS', ['bar' => 'Barras', 'pie' => 'Pay', 'gauge' => 'Círculo']); //'bar/pie/gauge');


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

function local_dominosdashboard_get_catalogue(string $key, string $andWhereSql = '', array $query_params = array()){
    $indicators = local_dominosdashboard_get_indicators();
    if(array_search($key, $indicators) === false){
        return [];
    }
    $fieldid = get_config('local_dominosdashboard', "filtro_" . $key);
    if($fieldid === false){
        return [];
    }
    global $DB;
    $query = "SELECT distinct data FROM {user_info_data} where fieldid = {$fieldid} {$andWhereSql}";
    $result = $DB->get_fieldset_sql($query, $query_params);
    return $result;
}

function local_dominosdashboard_get_user_catalogues($params = array()){
    $response = array();
    $returnOnly = $indicators = local_dominosdashboard_get_indicators();
    if(!empty($params['selected_filter'])){
        $returnOnly = local_dominosdashboard_get_indicators($params['selected_filter']);
    }
    if(empty($returnOnly)){
        return [];
    }
    $andWhereSql = "";
    $query_params = array();
    $conditions = array();

    global $DB;
    if(!empty($params)){
        foreach($params as $key => $param){
            if(array_search($key, $indicators) !== false){
                $fieldid = get_config('local_dominosdashboard', "filtro_" . $key);
                if($fieldid !== false){
                    $data = $params[$key];
                    $filter_active = true;
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
        // _log('$andWhereSql', $andWhereSql, $query_params);
    }

    foreach($returnOnly as $indicator){
        $response[$indicator] = local_dominosdashboard_get_catalogue($indicator, $andWhereSql, $query_params);
    }
    return $response;
}

function local_dominosdashboard_get_all_catalogues_for_kpi($kpi, $params = array()){
    $indicators = array();
    foreach(local_dominosdashboard_get_kpi_indicators() as $indicator){
        $indicators[$indicator] = local_dominosdashboard_get_kpi_catalogue($indicator, $kpi, $params);
    }
    _log($indicators);
    return $indicators;
}

function local_dominosdashboard_get_kpi_catalogue(string $key, $kpi = '', array $params = array()){
    $conditions = array();
    $sqlParams = array();
    $andWhereSql = "";
    if(!empty($params)){
        $indicators = local_dominosdashboard_get_kpi_indicators();
        foreach($params as $key => $param){
            if(array_search($key, $indicators) !== false){
                // DEFINE('DOMINOSDASHBOARD_INDICATORS_FOR_KPIS', 'regiones/distritos/tiendas');
                switch($key){
                    case 'regiones': 
                        $field = "region";
                    break;
                    case 'distritos': 
                        $field = "distrital";
                    break;
                    case 'tiendas': // Se espera que sea un entero
                        $field = "ccosto";
                    break;
                    default: 
                        continue; // Continuar con el siguiente parámetro, este no está dentro de los planeados
                    break;
                }
                $data = $params[$key];
                if(is_string($data) || is_numeric($data)){
                    $condition = " $field = ? ";
                    array_push($conditions, $condition);
                    array_push($sqlParams, $data);
                }elseif(is_array($data)){
                    $condition = array();
                    foreach($data as $d){
                        $c = " {$field} = ? ";
                        array_push($condition, $c);
                        array_push($sqlParams, $d);
                    }
                    if(!empty($condition)){
                        $condition = implode(" OR ", $condition);
                        array_push($conditions, $condition);
                    }
                }
                if(!empty($conditions)){
                    $andWhereSql = ' AND ' . implode(" AND ", $conditions);
                }
            }
        }
    }

    switch($kpi){
        case KPI_OPS: 
        case KPI_HISTORICO: 
        case KPI_SCORCARD: 
            $where = " AND kpi = {$kpi} ";
        break;
        default: 
            $where = '';
        break;
    }
    global $DB;
    switch($key){
        case 'regiones':
            $query = "SELECT distinct region, region as t1 FROM {dominos_kpis} WHERE region != '' {$where} {$andWhereSql}";
            break;
        case 'distritos':
            $query = "SELECT distinct distrital, distrital as t1 FROM {dominos_kpis} where distrital != '' {$where} {$andWhereSql}";
            break;
        case 'tiendas':
            $query = "SELECT ccosto, nom_ccosto FROM {dominos_kpis} WHERE ccosto != '' {$where} {$andWhereSql} GROUP BY ccosto";
            break;
        case 'periodos':
            $query = "SELECT distinct original_time, original_time as t1 FROM {dominos_kpis} WHERE original_time != '' {$where} {$andWhereSql}";
            break;
        default: 
            return [];
        break;
    }
    return $DB->get_records_sql_menu($query);
}

function local_dominosdashboard_get_completion_modes(){
    return [
        COMPLETION_DEFAULT => "Finalizado/No finalizado (seguimiento de finalización configurado)",
        COMPLETION_DEFAULT_AND_GRADE => "Finalizado/No finalizado más calificación (por ponderación en curso pero no establecida para finalización de curso)",
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

function local_dominosdashboard_get_enrolled_users_count(int $courseid, string $userids = ''){
    $email_provider = local_dominosdashboard_get_email_provider_to_allow();
    if(empty($userids)){
        // _log("Retornando default en local_dominosdashboard_get_enrolled_users_count porque no hay usuarios que tengan las características");
        return 0; // default
    }else{
        $whereids = " AND ra.userid IN ({$userids})";
    }
    if(!empty($email_provider)){
        $where = " AND email LIKE '{$email_provider}'"; 
    }else{
        $where = ""; 
    }
    $query = "SELECT COUNT(DISTINCT ra.userid) AS learners
    FROM {course} AS c
    LEFT JOIN {context} AS ctx ON c.id = ctx.instanceid
    JOIN {role_assignments} AS ra ON ra.contextid = ctx.id
    WHERE c.id = {$courseid}
    AND ra.userid IN(SELECT id from {user} WHERE deleted = 0 AND suspended = 0 {$where} {$whereids})
    AND ra.roleid IN (5) # default student role";
    global $DB;
    if($result = $DB->get_record_sql($query)){
        return intval($result->learners);
    }
    return 0; // default
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

function local_dominosdashboard_get_enrolled_users_ids(int $courseid){
    $email_provider = local_dominosdashboard_get_email_provider_to_allow();
    if(!empty($email_provider)){
        $where = " AND email LIKE '{$email_provider}'"; 
    }else{
        $where = ""; 
    }
    $query = "SELECT DISTINCT ra.userid as userid
    FROM {course} AS c
    LEFT JOIN {context} AS ctx ON c.id = ctx.instanceid
    JOIN {role_assignments} AS ra ON ra.contextid = ctx.id
    WHERE c.id = {$courseid}
    AND ra.userid IN(SELECT id from {user} WHERE deleted = 0 AND suspended = 0 {$where})
    AND ra.roleid IN (5) # default student role";
    global $DB;
    if($result = $DB->get_fieldset_sql($query)){
        return $result;
    }
    return array(); // default
}

function local_dominosdashboard_get_courses_with_filter(bool $allCourses = false, int $type){
    $LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS = get_config('local_dominosdashboard', 'LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS');
    if($LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS === false && $LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS == ''){
        $LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS = "";
    }
    switch ($type) {
        case LOCALDOMINOSDASHBOARD_PROGRAMAS_ENTRENAMIENTO: // Cursos en línea
        # not in
            // $LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS = get_config('local_dominosdashboard', 'LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS');
            if($LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS != ""){
                $where = " AND id NOT IN ({$LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS}) ";
            }else{
                $where = "";
            }
            return local_dominosdashboard_get_courses($allCourses, $where);
            break;
        
        case LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS: // Cursos presenciales
        # where id in
            // $LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS = get_config('local_dominosdashboard', 'LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS');
            if($LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS != ""){
                $where = " AND id IN ({$LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS}) ";
            }else{
                return array();
                $where = "";
            }
            return local_dominosdashboard_get_courses($allCourses, $where);
            break;
        
        case LOCALDOMINOSDASHBOARD_COURSE_KPI_COMPARISON: // Cruce de kpis KPI_NA
            $kpis = local_dominosdashboard_get_KPIS();
            $wherecourseidin = array();

            foreach($kpis as $key => $kpi){
                $name = 'kpi_' . $key;
                if( $config = get_config('local_dominosdashboard', $name)){
                    array_push($wherecourseidin, $config);
                }
                // $title = get_string('kpi_relation', $ldm_pluginname) . ': ' . $kpi;
                // $description = get_string('kpi_relation' . '_desc', $ldm_pluginname);        
                // $setting = new admin_setting_configmultiselect($name, $title, $description, array(), $courses_min);
                // $page->add($setting);
            }
            if(!empty($wherecourseidin)){
                $wherecourseidin = array_unique($wherecourseidin);
                $wherecourseidin = implode(',', $wherecourseidin);
                $where = " AND id IN ({$wherecourseidin}) ";
                return local_dominosdashboard_get_courses($allCourses, $where);
            }
            return array();

            return array_filter(local_dominosdashboard_get_courses($allCourses), function ($element){
                $config = get_config('local_dominosdashboard', 'course_kpi_' . $element->id);
                $result = ($config !== false && $config != KPI_NA);
                return $result;
            });
            break;
        
        default:
            return array();
            break;
    }
}

function local_dominosdashboard_get_kpi_overview(array $params = array(), bool $allCourses = false){
    $kpis = local_dominosdashboard_get_KPIS();
    $wherecourseidin = array();
    $ids = array();
    $configs = array();

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
    $ids = array_unique($ids);
    $ids = implode(',', $ids);
    $where = " AND id IN ({$ids}) ";
    $courses = local_dominosdashboard_get_courses($allCourses, $where);
    foreach($courses as $key => $course){
        $courses[$key] = local_dominosdashboard_get_course_information($key, false, false, $params);
    }
    $response = array();
    foreach($configs as $kpi => $config){
        $kpi_status = new stdClass();
        $kpi_courses = array();
        foreach($config as $course_id){
            array_push($kpi_courses, $courses[$course_id]);
        }
        switch($kpi){
            case KPI_OPS: // 1 // Aprobado, no aprobado y destacado
                $kpi_status->type = "CALIFICACION";
                break;
            case KPI_HISTORICO: // 2 retorna el número de quejas
                $kpi_status->type = "NUMERO DE QUEJAS";
                
                break;
            case KPI_SCORCARD: // 3
                $kpi_status->type = "ROTACION";
                break;
        }
        $kpi_status->name = $kpis[$kpi];
        $kpi_status->id = $kpi;
        $kpi_status->courses = $kpi_courses;
        $kpi_status->status = local_dominosdashboard_get_kpi_results($kpi, $params);
        array_push($response, $kpi_status);
    }
    return ['type' => 'kpi_list', 'result' => $response];
    

    if(!empty($wherecourseidin)){
        $wherecourseidin = array_unique($wherecourseidin);
        $wherecourseidin = implode(',', $wherecourseidin);
        $where = " AND id IN ({$wherecourseidin}) ";
        return local_dominosdashboard_get_courses($allCourses, $where);
    }
    return array();
}

/**
 * @return array
 */
function local_dominosdashboard_get_courses_overview(int $type, array $params = array(), bool $allCourses = false){
    if($type === LOCALDOMINOSDASHBOARD_COURSE_KPI_COMPARISON){
        return local_dominosdashboard_get_kpi_overview($params, $allCourses);
    }
    $courses = local_dominosdashboard_get_courses_with_filter($allCourses, $type);
    $courses_in_order = array();
    foreach($courses as $course){
        $course_information = local_dominosdashboard_get_course_information($course->id, $kpis = false, $activities = false, $params);        
        if(empty($course_information)){
            continue;
        }
        if(empty($courses_in_order)){
            array_push($courses_in_order, $course_information);
        }else{
            $max = count($courses_in_order) - 1;
            if($course_information->percentage > $courses_in_order[$max]->percentage){
                array_unshift($courses_in_order, $course_information);
            }else{
                array_push($courses_in_order, $course_information);
            }
        }
    }
    return ['type' => 'course_list', 'result' => $courses_in_order];
}

function local_dominosdashboard_get_course_chart(int $courseid){
    if($response = get_config('local_dominosdashboard', 'course_main_chart_' . $courseid)){
        return $response;
    }
    return "bar";
}

function local_dominosdashboard_get_course_color(int $courseid){
    if($response = get_config('local_dominosdashboard', 'course_main_chart_color_' . $courseid)){
        return $response;
    }
    return "#006491";
}

define('RETURN_RANDOM_DATA', true);
define('MAX_RANDOM_NUMBER', 500);
function local_dominosdashboard_get_course_information(int $courseid, bool $get_kpis = false, bool $get_activities = false, array $params = array()){
    global $DB;
    $course = $DB->get_record('course', array('id' => $courseid));
    if($course === false){
        return false;
    }
    $response = new stdClass();
    $response->key = 'course' . $courseid;
    $response->id = $courseid;
    $response->chart = local_dominosdashboard_get_course_chart($courseid);
    $response->title = $course->fullname;
    $response->status = 'ok';
    if(RETURN_RANDOM_DATA){
        $response->enrolled_users = random_int(100, MAX_RANDOM_NUMBER);
        $response->approved_users = random_int(5, $response->enrolled_users);
        $response->not_viewed = random_int(0, $response->enrolled_users - $response->approved_users);
        $response->percentage = local_dominosdashboard_percentage_of($response->approved_users, $response->enrolled_users, 2);
        $response->not_approved_users = $response->enrolled_users - $response->approved_users;
        $response->value = $response->percentage;
        if($get_activities){
            /* Actividades aleatorias */
            $activities = array();
            for($i = 0; $i < 6; $i++){
                $key                = "module" . $i;
                $title              = random_int(22, 900) . " A " . $i;
                $completed          = $response->enrolled_users - ($i * 10);
                if($completed < 1){
                    $completed = 1;
                }
                array_push($activities, compact('key', 'title', 'inProgress', 'completed', 'completedWithFail'));
            }
            $response->activities = $activities;
        }
        if($get_kpis){
            $response->kpi = local_dominosdashboard_get_kpi_info($courseid, $params);
        }
        return $response;
    }
    if($get_kpis){
        $response->kpi = local_dominosdashboard_get_kpi_info($courseid, $params);
    }
    $userids = local_dominosdashboard_get_user_ids_with_params($courseid, $params);
    if($userids === false){
        $response->activities = [];
        $response->enrolled_users = 0;
        $response->approved_users = 0;
        $response->not_viewed = 0;
        $response->percentage = 0;
        $response->not_approved_users = $response->enrolled_users - $response->approved_users;
        $response->value = 0;
        return $response;
    }
    if($get_activities){
        $response->activities = local_dominosdashboard_get_activities_completion($courseid, $userids);
    }
    $num_users = count($userids);
    $userids = implode(',', $userids);

    $response->enrolled_users = local_dominosdashboard_get_enrolled_users_count($courseid, $userids);
    $response->approved_users = local_dominosdashboard_get_approved_users($courseid, $userids);
    $response->not_approved_users = $response->enrolled_users - $response->approved_users;
    $response->not_viewed = local_dominosdashboard_get_not_viewed_users_in_course($courseid, $userids, $num_users);
    $response->percentage = local_dominosdashboard_percentage_of($response->approved_users, $response->enrolled_users, 2);
    $response->value = $response->percentage;
    return $response;
}

function local_dominosdashboard_get_not_viewed_users_in_course(int $courseid, string $userids, int $num_users){
    global $DB;
    if(empty($userids)){
        return 0;
    }
    $query = "SELECT count(distinct userid) FROM {logstore_standard_log} WHERE target = 'course' AND action = 'viewed' AND courseid = {$courseid} AND userid IN ({$userids})";
    $count = $DB->count_records_sql($query);
    if(is_numeric($count)){
        return $num_users - $count;
    }
    return 0;
}

function local_dominosdashboard_get_kpi_info(int $courseid, array $params = array()){
    $kpis = array();
    foreach(local_dominosdashboard_get_KPIS() as $key => $kpi){
        if($setting = get_config('local_dominosdashboard', 'kpi_' . $key)){
            $courses = explode(',', $setting);
            if(array_search($courseid, $courses) !== false){
                $kpi_info = new stdClass();
                $kpi_info->kpi_name = $kpi;
                $kpi_info->kpi = $key;
                switch($key){
                    case KPI_OPS: // 1 // Aprobado, no aprobado y destacado
                        $kpi_info->type = "CALIFICACION";
                        break;
                    case KPI_HISTORICO: // 2 retorna el número de quejas
                        $kpi_info->type = "NUMERO DE QUEJAS";
                        
                        break;
                    case KPI_SCORCARD: // 3
                        $kpi_info->type = "ROTACION";
                        break;
                    default:
                    break;
                }
                $kpi_info->value    = local_dominosdashboard_get_kpi_results($key, $params);
                
                array_push($kpis, $kpi_info);
            }
        }
    }
    return $kpis;
}

function local_dominosdashboard_get_kpi_results($kpi, $params){
    global $DB;
    
    $conditions = array();
    $sqlParams = array();
    $andWhereSql = "";
    if(!empty($params)){
        $indicators = local_dominosdashboard_get_kpi_indicators();
        foreach($params as $key => $param){
            if(array_search($key, $indicators) !== false){
                // DEFINE('DOMINOSDASHBOARD_INDICATORS_FOR_KPIS', 'regiones/distritos/tiendas');
                switch($key){
                    case 'regiones': 
                        $field = "region";
                    break;
                    case 'distritos': 
                        $field = "distrital";
                    break;
                    case 'tiendas': // Se espera que sea un entero
                        $field = "nom_ccosto";
                    break;
                    default: 
                        continue; // Continuar con el siguiente parámetro, este no está dentro de los planeados
                    break;
                }
                $data = $params[$key];
                if(is_string($data) || is_numeric($data)){
                    $condition = " $field = ? ";
                    array_push($conditions, $condition);
                    array_push($sqlParams, $data);
                }elseif(is_array($data)){
                    $condition = array();
                    foreach($data as $d){
                        $c = " {$field} = ? ";
                        array_push($condition, $c);
                        array_push($sqlParams, $d);
                    }
                    if(!empty($condition)){
                        $condition = implode(" OR ", $condition);
                        array_push($conditions, $condition);
                    }
                }
                if(!empty($conditions)){
                    $andWhereSql = ' AND ' . implode(" AND ", $conditions);
                }
            }
        }
    }
    
    switch($kpi){
        case KPI_OPS: // 1 // Aprobado, no aprobado y destacado
            $query = "SELECT valor, COUNT(*) AS conteo FROM {dominos_kpis} WHERE kpi = 1 AND valor != '' {$andWhereSql} GROUP BY valor order by conteo";
            $result = $DB->get_records_sql_menu($query, $sqlParams);
            if(empty($result)) return null;
            return $result;
            break;
        case KPI_HISTORICO: // 2 retorna el número de quejas
            $query = "SELECT AVG(valor) AS numero FROM {dominos_kpis} WHERE kpi = 2 AND valor != '' {$andWhereSql}";
            $result = $DB->get_field_sql($query, $sqlParams);
            if(empty($result)) return null;
            return $result;
            break;
        case KPI_SCORCARD: // 3
            $query = "SELECT AVG(valor) AS numero FROM {dominos_kpis} WHERE kpi = 3 AND valor != '' {$andWhereSql}";
            $result = $DB->get_field_sql($query, $sqlParams);
            if(empty($result)) return null;
            return $result;
            break;
        default:
            return "";
        break;
    }
}

function local_dominosdashboard_get_ideales_as_js_script(){
    $ideal_cobertura = get_config('local_dominosdashboard', 'ideal_cobertura');
    if($ideal_cobertura === false){
        $ideal_cobertura = 94;
    }
    $ideal_rotacion  = get_config('local_dominosdashboard', 'ideal_rotacion');
    if($ideal_rotacion === false){
        $ideal_rotacion = 85;
    }
    return "<script> var ideal_cobertura = {$ideal_cobertura}; var ideal_rotacion = {$ideal_rotacion}; </script>";
}

function local_dominosdashboard_get_approved_users(int $courseid, string $userids = ''){
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
                $whereids = "";
            }else{
                $whereids = " AND p.userid IN ({$userids})";
            }
            $query = "SELECT count(*) AS completions FROM {course_completions} AS p
            WHERE p.course = {$courseid} AND p.timecompleted IS NOT NULL {$whereids} ";
        break;
        case COMPLETION_DEFAULT_AND_GRADE:
            if(empty($userids)){
                $whereids = "";
            }else{
                $whereids = " AND p.userid IN ({$userids})";
            }
            $grade_item = local_dominosdashboard_get_course_grade_item_id($courseid);
            $minimum_score = get_config('local_dominosdashboard', 'course_minimum_score_' . $courseid);
            if($grade_item === false || $minimum_score === false){ // Missing configuration
                // _log("Missing configuration courseid", $courseid, 'COMPLETION_DEFAULT_AND_GRADE');
                return $response;
            }
            $query = "SELECT count(*) AS completions 
            FROM {grade_grades} AS gg JOIN {user} AS user ON user.id = gg.userid WHERE gg.itemid = {$grade_item} AND final_grade >= {$minimum_score} AND user.id IN 
            (SELECT u.id FROM {course_completions} AS p WHERE p.course = {$courseid} AND p.timecompleted IS NOT NULL {$whereids})";
        break;
        case COMPLETION_BY_GRADE:
            if(empty($userids)){
                $whereids = "";
            }else{
                $whereids = " AND gg.userid IN ({$userids})";
            }
            $grade_item = get_config('local_dominosdashboard', 'course_grade_activity_completion_' . $courseid);
            $minimum_score = get_config('local_dominosdashboard', 'course_minimum_score_' . $courseid);
            if($grade_item === false || $minimum_score === false){ // Missing configuration
                // _log("Missing configuration courseid", $courseid, 'COMPLETION_BY_GRADE');
                return $response;
            }
            $query = "SELECT count(*) AS completions FROM {grade_grades} AS gg WHERE
             gg.itemid = {$grade_item} AND final_grade >= {$minimum_score} {$whereids}";
        break;
        // case COMPLETION_BY_AVG:
        //     $query = "Query COMPLETION_BY_AVG";
        // break;
        case COMPLETION_BY_ACTIVITY:
            if(empty($userids)){
                $whereids = "";
            }else{
                $whereids = " AND userid IN ({$userids})";
            }
            $completion_activity = get_config('local_dominosdashboard', 'course_completion_activity_' . $courseid);
            /* completionstate 0 => 'In Progress' 1 => 'Completed' 2 => 'Completed with Pass' completionstate = 3 => 'Completed with Fail' */
            $query = "SELECT count(*) AS completions from {course_modules_completion} WHERE
             coursemoduleid = {$completion_activity} AND completionstate IN (1,2) $whereids";
        break;
        case COMPLETION_BY_BADGE:
            // Obtener los id de usuarios inscritos en el curso
            $completion_badge = get_config('local_dominosdashboard', 'badge_completion_' . $courseid);
            // $ids = local_dominosdashboard_get_enrolled_users_ids($courseid);
            // $ids = implode(',', $ids);
            if(empty($userids)){
                $whereids = "";
            }else{
                $whereids = " AND userid IN ({$userids})";
            }
            $query = "SELECT count(*) AS completions from {badge_issued} WHERE badgeid = {$completion_badge} {$whereids}";
        break;
        default: 
            // _log("method not allowed");
            // $response->message = 'Completion not allowed';
            return $response;
        break;
    }
    // // _log("Consulta de curso finalizado", $query);
    if(!empty($query)){
        global $DB;
        if($result = $DB->get_record_sql($query)){
            $response = $result->completions;
            return $response;
            // _log($result);
            $response->enrolled_users = local_dominosdashboard_get_enrolled_users_count($courseid);
            $response->approved_users = $result->completions;
            // _log($response);
            $response->percentage = local_dominosdashboard_percentage_of($response->approved_users, $response->enrolled_users, 2);
            $response->status = 'ok';
            return $response;
        }else{
            // _log("query returns false ", $query);
        }
    }
    // _log("Default response returned");
    return $response;
}

function local_dominosdashboard_percentage_of(int $number, int $total, int $decimals = 0 ){
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

function local_dominosdashboard_get_user_ids_with_params(int $courseid, array $params = array(), bool $returnAsString = false){
    $ids = local_dominosdashboard_get_enrolled_users_ids($courseid);
    if(empty($ids)){
        return false;
    }
    $allow_users = array();
    $filter_active = false;
    if(!empty($params)){
        global $DB;
        $indicators = local_dominosdashboard_get_indicators();
        // // _log('indicators', $indicators);
        // $position = array_search();
        foreach($params as $key => $param){
            // // _log('$params as $key => $param', $key, $param);
            if(array_search($key, $indicators) !== false){
                $fieldid = get_config('local_dominosdashboard', "filtro_" . $key);
                if($fieldid !== false){
                    $data = $params[$key];
                    $filter_active = true;
                    if(is_string($data) || is_numeric($data)){
                        // $newIds = 
                        $newIds = $DB->get_fieldset_select('user_info_data', 'distinct userid', ' fieldid = :fieldid AND data = :data ', array('fieldid' => $fieldid, 'data' => $params[$key]));
                    }elseif(is_array($data)){
                        $wheres = array();
                        $query_params = array();
                        foreach ($data as $d) {
                                array_push($wheres, " data = ? ");
                                array_push($query_params, $d);
                        }
                        if(!empty($wheres)){
                            $wheres = " AND ( " . implode(" || ", $wheres) . " ) ";
                            $query = "SELECT DISTINCT userid FROM {user_info_data} WHERE fieldid = {$fieldid} " . $wheres;
                            // _log("Consulta de múltiples valores en un campo de usuario", $query);
                            $newIds = $DB->get_fieldset_sql($query, $query_params);
                        }else{
                            $newIds = false;
                        }
                    }else{
                        $newIds = false;
                    }

                    if($newIds){
                        if(is_array($newIds)){
                            $allow_users[] = $newIds;
                        }else{
                            // _log("no existen usuarios con esta coincidencia",array('fieldid' => $fieldid, 'data' => $params[$key]));
                        }
                    }else{
                        // _log('no newIds', array('fieldid' => $fieldid, 'data' => $params[$key]));
                        return array(); // Search returns empty
                    }
                }else{
                    // _log('no fieldid');
                }
            }else{
                // // _log('array_key_exists returns false');
            }
        }
    }
    $ids = array_unique($ids);
    // // _log('$allow_users', $allow_users);
    if($filter_active){
        if(count($allow_users) > 1){
            $allow_users = call_user_func_array('array_intersect', $allow_users);
        }else{
            $allow_users = $allow_users[0];
        }
        $ids = array_filter($ids, function($element) use($allow_users){
            return array_search($element, $allow_users) !== false;
        });
    }

    if($returnAsString){
        return implode(',', $ids);
    }else{
        return $ids;
    }
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

function local_dominosdashboard_get_kpi_indicators(string $from = ''){
    $indicators = explode('/', DOMINOSDASHBOARD_INDICATORS_FOR_KPIS);
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
        $query = "SELECT id, fullname, shortname FROM {course} where category in ({$categories}) AND visible = 1 {$andWhereClause} order by sortorder";
    }else{
        if($exclusion = get_config('local_dominosdashboard', 'excluded_courses')){
            if($exclusion != ''){
                $andWhereClause .= " AND id NOT IN ({$exclusion})";
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

function local_dominosdashboard_get_activities_completion(int $courseid, string $userids){

    $activities = array();
    if(empty($userids)){
        return $activities;
    }
    global $DB;
    $courseactivities = local_dominosdashboard_get_activities($courseid, " AND completion != 0 ");
    foreach($courseactivities as $key => $activity){
        $activityInformation = local_dominos_dashboard_get_activity_completions($activityid = $key, $userids, $title = $activity);
        // _log($activityInformation);
        if(empty($activities)){
            array_push($activities,
             $activityInformation);
        }else{
            $max = count($activities) - 1;
            if($activityInformation['completed'] > $activities[$max]['completed']){
                array_unshift($activities, $activityInformation);
            }else{
                array_push($activities, $activityInformation);
            }
        }
    }
    return $activities;
}

function local_dominos_dashboard_get_activity_completions(int $activityid, string $userids = "", $title = ""){
    global $DB;
    $key = "module" . $activityid;
    // $inProgress         = $DB->count_records_sql("SELECT count(*) FROM {course_modules_completion} WHERE coursemoduleid = {$activityid} AND userid IN ({$userids}) AND completionstate = 0");
    $completed          = $DB->count_records_sql("SELECT count(*) FROM {course_modules_completion} WHERE coursemoduleid = {$activityid} AND userid IN ({$userids}) AND completionstate IN (1,2)");
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
    _log($course);
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

function local_dominosdashboard_get_historic_reports(int $courseid){
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
            // $temp->filterid = "";
            // $temp->filtertext = "";
            // $temp->timecreated = "";
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
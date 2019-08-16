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
        if(LOCALDOMINOSDASHBOARD_DEBUG){
            $node = $nav->add (
                'Configuración ' . get_string('pluginname', 'local_dominosdashboard'),
                new moodle_url( $CFG->wwwroot . '/admin/settings.php?section=local_dominosdashboard' )
            );
            $node = $nav->add (
                'Prueba de peticiones por curso',
                new moodle_url( $CFG->wwwroot . '/local/dominosdashboard/dashboard.php' )
            );
            $node = $nav->add (
                'Prueba de peticiones por pestaña',
                new moodle_url( $CFG->wwwroot . '/local/dominosdashboard/todos_los_cursos.php' )
            );
        }
        $node->showinflatnavigation = true;
    }
}

DEFINE("LOCALDOMINOSDASHBOARD_CATEGORY_PARENT_NAME", "parent_category");
DEFINE("LOCALDOMINOSDASHBOARD_DEBUG", true);
DEFINE("LOCALDOMINOSDASHBOARD_DUMMY_RESPONSE", false);

DEFINE("KPI_NA", 0);
DEFINE("KPI_OPS", 1);
DEFINE("KPI_HISTORICO", 2);
DEFINE("KPI_SCORCARD", 3);

DEFINE("LOCALDOMINOSDASHBOARD_NOFILTER", "__NOFILTER__");

DEFINE('LOCALDOMINOSDASHBOARD_PROGRAMAS_ENTRENAMIENTO', 1);
DEFINE('LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS', 2);
DEFINE('LOCALDOMINOSDASHBOARD_COURSE_KPI_COMPARISON', 3);

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
DEFINE('DOMINOSDASHBOARD_CHARTS', 'bar/donut/chart3/chart4');


function local_dominosdashboard_relate_column_with_fields(string $column, array $requiredFields){
    $response = array();
    foreach($requiredFields as $field){
        // $response[]
        if(strpos($columns, $field) === false){
            return false;
        }
    }
    return true;
}

function local_dominosdashboard_check_column_has_one_of_fields(string $columns, $options){
    $found = false;
    foreach($options as $option){
        if(strpos($columns !== false)){
            $found = true;
        }
    }
}

// function uu_validate_user_upload_columns(csv_import_reader $cir, $stdfields, $profilefields, moodle_url $returnurl) {
//     $columns = $cir->get_columns();

//     if (empty($columns)) {
//         $cir->close();
//         $cir->cleanup();
//         print_error('cannotreadtmpfile', 'error', $returnurl);
//     }
//     if (count($columns) < 2) {
//         $cir->close();
//         $cir->cleanup();
//         print_error('csvfewcolumns', 'error', $returnurl);
//     }

//     // test columns
//     $processed = array();
//     foreach ($columns as $key=>$unused) {
//         $field = $columns[$key];
//         $lcfield = core_text::strtolower($field);
//         if (in_array($field, $stdfields) or in_array($lcfield, $stdfields)) {
//             // standard fields are only lowercase
//             $newfield = $lcfield;

//         } else if (in_array($field, $profilefields)) {
//             // exact profile field name match - these are case sensitive
//             $newfield = $field;

//         } else if (in_array($lcfield, $profilefields)) {
//             // hack: somebody wrote uppercase in csv file, but the system knows only lowercase profile field
//             $newfield = $lcfield;

//         } else if (preg_match('/^(sysrole|cohort|course|group|type|role|enrolperiod|enrolstatus)\d+$/', $lcfield)) {
//             // special fields for enrolments
//             $newfield = $lcfield;

//         } else {
//             $cir->close();
//             $cir->cleanup();
//             print_error('invalidfieldname', 'error', $returnurl, $field);
//         }
//         if (in_array($newfield, $processed)) {
//             $cir->close();
//             $cir->cleanup();
//             print_error('duplicatefieldname', 'error', $returnurl, $newfield);
//         }
//         $processed[$key] = $newfield;
//     }

//     return $processed;
// }

function local_dominosdashboard_get_catalogue(string $key){
    $indicators = local_dominosdashboard_get_indicators();
    if(array_search($key, $indicators) === false){
        return [];
    }
    $fieldid = get_config('local_dominosdashboard', "filtro_" . $key);
    if($fieldid === false){
        return [];
    }
    global $DB;
    $query = "SELECT distinct data FROM {user_info_data} where fieldid = {$fieldid}";
    return $DB->get_fieldset_sql($query);
}

function local_dominosdashboard_get_course_all_catalogues($courseid){
    global $DB;
    $course = $DB->get_record('course', array('id' => $courseid));
    if(empty($course)){
        _log('No existe el curso');
        die('no existe curso');
    }
    foreach (local_dominosdashboard_get_indicators() as $indicator) {
        _log('indicator', $indicator);
        foreach (local_dominosdashboard_get_catalogue($indicator) as $item) {
            _log('item', $item);
            _print("Indicador: " . $indicator, 'Buscando ' . $item);
            $params = array();
            $params[$indicator] = $item;
            $course_information = local_dominosdashboard_get_course_information($course->id, $course->fullname, true, $params);
            _log($course_information);
            echo local_dominosdashboard_format_response($course_information);
            echo "<br><br><br>";
            _print($course_information);
        }
    }
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
    // $course = $DB->get_record('course', array('id' => $course->id));
    // $info = new completion_info($course);

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

function local_dominosdashboard_get_course_completion_by_grade(){
    
}

function local_dominosdashboard_get_course_completion_by_activity_grade(){
    
}

function local_dominosdashboard_get_course_completion_by_badge(){
    
}

function local_dominosdashboard_get_course_completion_by_activity_completed(){
    
}

function local_dominosdashboard_get_course_completion_standard(){
    
}

function local_dominosdashboard_get_course_completion_by_average(){
    
}

function local_dominosdashboard_get_module_grade(int $userid, int $moduleid){
    global $DB;
    $grade = $default;
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
        _log("Retornando default en local_dominosdashboard_get_enrolled_users_count porque no hay usuarios que tengan las características, params", $params);
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
        return $result->learners;
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
    // return $DB->get_fieldset_selet('')
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
    _log($query);
    global $DB;
    if($result = $DB->get_fieldset_sql($query)){
        return $result;
    }
    return array(); // default
}

function local_dominosdashboard_get_courses_with_filter(bool $allCourses = false, int $type){
    $LOCALDOMINOSDASHBOARD_PROGRAMAS_ENTRENAMIENTO = get_config('local_dominosdashboard', 'LOCALDOMINOSDASHBOARD_PROGRAMAS_ENTRENAMIENTO');
    if($LOCALDOMINOSDASHBOARD_PROGRAMAS_ENTRENAMIENTO === false && $LOCALDOMINOSDASHBOARD_PROGRAMAS_ENTRENAMIENTO == ''){
        $LOCALDOMINOSDASHBOARD_PROGRAMAS_ENTRENAMIENTO = "";
    }
    switch ($type) {
        case LOCALDOMINOSDASHBOARD_PROGRAMAS_ENTRENAMIENTO: // Cursos en línea
        # not in
            $LOCALDOMINOSDASHBOARD_PROGRAMAS_ENTRENAMIENTO = get_config('local_dominosdashboard', 'LOCALDOMINOSDASHBOARD_PROGRAMAS_ENTRENAMIENTO');
            if($LOCALDOMINOSDASHBOARD_PROGRAMAS_ENTRENAMIENTO != ""){
                $where = " AND id NOT IN ({$LOCALDOMINOSDASHBOARD_PROGRAMAS_ENTRENAMIENTO}) ";
            }else{
                $where = "";
            }
            return local_dominosdashboard_get_courses($allCourses, $where);
            break;
        
        case LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS: // Cursos presenciales
        # where id in
            $LOCALDOMINOSDASHBOARD_PROGRAMAS_ENTRENAMIENTO = get_config('local_dominosdashboard', 'LOCALDOMINOSDASHBOARD_PROGRAMAS_ENTRENAMIENTO');
            if($LOCALDOMINOSDASHBOARD_PROGRAMAS_ENTRENAMIENTO != ""){
                $where = " AND id IN ({$LOCALDOMINOSDASHBOARD_PROGRAMAS_ENTRENAMIENTO}) ";
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

/**
 * @return array
 */
function local_dominosdashboard_get_courses_overview(int $type, array $params = array(), bool $allCourses = false){
    $courses = local_dominosdashboard_get_courses_with_filter($allCourses, $type);
    $courses_in_order = array();
    switch ($type) {
        case LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS: // Cursos presenciales
        case LOCALDOMINOSDASHBOARD_PROGRAMAS_ENTRENAMIENTO: // Cursos en línea
        case LOCALDOMINOSDASHBOARD_COURSE_KPI_COMPARISON: // Cruce de kpis
            foreach($courses as $course){
                if($type === LOCALDOMINOSDASHBOARD_COURSE_KPI_COMPARISON){
                    $course_information = local_dominosdashboard_get_course_information($course->id, $course->fullname, true, $params);
                }else{
                    $course_information = local_dominosdashboard_get_course_information($course->id, $course->fullname, false, $params);
                }
                if(empty($course_information)){
                    continue;
                }
                if(empty($courses_in_order)){
                    array_push($courses_in_order, $course_information);
                }else{
                    if($course_information->percentage > $courses_in_order[0]->percentage){
                        array_unshift($courses_in_order, $course_information);
                    }else{
                        array_push($courses_in_order, $course_information);
                    }
                }
            }
            return $courses_in_order;
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

function local_dominosdashboard_get_course_color(int $courseid){
    if($response = get_config('local_dominosdashboard', 'course_main_chart_color_' . $courseid)){
        return $response;
    }
    return "#006491";
}

function local_dominosdashboard_get_course_information(int $courseid, string $coursename = "", bool $get_kpi_comparison = false, array $params = array()){
    global $DB;
    $course = $DB->get_record('course', array('id' => $courseid));
    if($course === false){
        return false;
    }
    $dummy_response = new stdClass();
    $dummy_response->key = 'course' . $courseid;
    $dummy_response->chart = local_dominosdashboard_get_course_chart($courseid);
    $dummy_response->color = local_dominosdashboard_get_course_color($courseid);
    $dummy_response->title = $coursename;
    $dummy_response->enrolled_users = 100;
    $dummy_response->approved_users = random_int(0, 100);
    $dummy_response->not_attempted = 2;
    $dummy_response->percentage = $dummy_response->approved_users;
    $dummy_response->value = $dummy_response->percentage;
    $dummy_response->status = 'ok';
    $dummy_response->kpi = random_int(0, 100);
    if(LOCALDOMINOSDASHBOARD_DUMMY_RESPONSE){
        return $dummy_response;
    }
    $userids = local_dominosdashboard_get_user_ids_with_params($courseid, $params);
    if($userids === false){
        return false;
    }
    _log("Línea con error de userids que retorna null", $userids);
    $num_users = count($userids);
    $userids = implode(',', $userids);
    _log('$userids = local_dominosdashboard_get_user_ids_with_params($courseid, $params, true);', $userids);

    $response = new stdClass();
    $response->key = 'course' . $courseid;
    $response->chart = local_dominosdashboard_get_course_chart($courseid);
    $response->title = $course->fullname;
    $response->enrolled_users = local_dominosdashboard_get_enrolled_users_count($courseid, $userids);
    $response->approved_users = local_dominosdashboard_get_approved_users($courseid, $userids);
    $response->not_attempted = local_dominosdashboard_get_not_viewed_users_in_course($courseid, $userids, $num_users);
    $response->percentage = local_dominosdashboard_percentage_of($response->approved_users, $response->enrolled_users, 2);
    $response->value = $response->percentage;
    $response->status = 'ok';
    if($get_kpi_comparison){
        $response->kpi = local_dominosdashboard_compare_kpi($courseid);
    }else{
        $response->kpi = "N/A";
    }
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

function local_dominosdashboard_compare_kpi($courseid){
    return random_int(0,100);
}

function local_dominosdashboard_get_approved_users(int $courseid, string $userids = ''){
    $response = 0;
    if(empty($userids)){ // no users to search in query
        _log('No se encuentran usuarios');
        return $response;
    }
    $completion_mode = get_config('local_dominosdashboard', "course_completion_" . $courseid);
    // _log($completion_mode);
    $default = 0;
    if($completion_mode === false){ // missing configuration
        _log("Missing configuration courseid", $courseid, 'completion mode returned false');
        return $response;
    }
    $query = "";
    _log("Tipo de completado", $completion_mode);
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
                _log("Missing configuration courseid", $courseid, 'COMPLETION_DEFAULT_AND_GRADE');
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
                _log("Missing configuration courseid", $courseid, 'COMPLETION_BY_GRADE');
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
            _log("method not allowed");
            // $response->message = 'Completion not allowed';
            return $response;
        break;
    }
    // _log("Consulta de curso finalizado", $query);
    if(!empty($query)){
        global $DB;
        if($result = $DB->get_record_sql($query)){
            $response = $result->completions;
            return $response;
            _log($result);
            $response->enrolled_users = local_dominosdashboard_get_enrolled_users_count($courseid);
            $response->approved_users = $result->completions;
            _log($response);
            $response->percentage = local_dominosdashboard_percentage_of($response->approved_users, $response->enrolled_users, 2);
            $response->status = 'ok';
            return $response;
        }else{
            _log("query returns false ", $query);
        }
    }
    _log("Default response returned");
    return $response;
}

function local_dominosdashboard_percentage_of(int $number, int $total, int $decimals = 0 ){
    if($total != 0){
        return round($number / $total * 100, $decimals);
    }else{
        return 0;
    }
}

function local_dominosdashboard_get_course_grade_item_id(int $courseid, array $params){
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
        // _log('indicators', $indicators);
        // $position = array_search();
        foreach($params as $key => $param){
            // _log('$params as $key => $param', $key, $param);
            if(array_search($key, $indicators) !== false){
                $fieldid = get_config('local_dominosdashboard', "filtro_" . $key);
                if($fieldid !== false){
                    $data = $params[$key];
                    $filter_active = true;
                    if(is_string($data)){
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
                            _log("Consulta de múltiples valores en un campo de usuario", $query);
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
                            _log("no existen usuarios con esta coincidencia",array('fieldid' => $fieldid, 'data' => $params[$key]));
                        }
                    }else{
                        _log('no newIds', array('fieldid' => $fieldid, 'data' => $params[$key]));
                        return array(); // Search returns empty
                    }
                }else{
                    _log('no fieldid');
                }
            }else{
                // _log('array_key_exists returns false');
            }
        }
    }
    $ids = array_unique($ids);
    _log('$allow_users', $allow_users);
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

function local_dominosdashboard_get_indicators(){
    return explode('/', DOMINOSDASHBOARD_INDICATORS);
}

function local_dominosdashboard_get_charts(){
    $response = array();
    foreach(explode('/', DOMINOSDASHBOARD_CHARTS) as $chart){
        $response[$chart] = $chart;
    }
    return $response;
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
        $status = "No data found";
        $count = 0;
    }
    $result = array();
    $result['status'] = $status;
    $result['count'] = $count;
    $result[$dataname] = $data;
    return json_encode($result);
}

function local_dominosdashboard_done_successfully($message = 'ok'){
    return local_dominosdashboard_format_response(null, 'data', $message);
}

function local_dominosdashboard_error_response($message = 'error'){
    return local_dominosdashboard_format_response(null, 'data', $message);
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
        // $exploded_path = explode($category->path);
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

function local_dominosdashboard_get_activities(int $courseid/*, int $visible = 1*/){
    // if($visible != 1 || $visible != 0){
    //     $visible = 1;
    // }
    global $DB;
    // $query = "SELECT * ";
    $actividades = array();
    $query  = "SELECT id, CASE ";
    $tiposDeModulos = $DB->get_records('modules', array(/*'visible' => $visible*/), 'id,name');
    foreach ($tiposDeModulos as $modulo) {
        $nombre  = $modulo->name;
        $alias = 'a'.$modulo->id;
        $query .= ' WHEN cm.module = '.$modulo->id.' THEN (SELECT '.$alias.'.name FROM {'.$nombre.'} '.$alias.' WHERE '.$alias.'.id = cm.instance) ';
    }
    $query .= " END AS name
    from {course_modules} cm
    where course = {$courseid}"; // visible = ' . $visible . ' ';
        // $DB->get_records_sql_menu($sql);
    return $DB->get_records_sql_menu($query);
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
    // $query = "SELECT c.id, c.shortname as name, count(*) as proficiency FROM {competency_usercomp} as uc join {competency} as c on uc.competencyid = c.id group by uc.competencyid";
    // return $DB->get_records_sql($query);
    foreach($competencies as $competency){
        if(LOCALDOMINOSDASHBOARD_DUMMY_RESPONSE){
            $competency->proficiency = random_int(0, 1000);
        }else{
            $competency->proficiency = $DB->count_records('competency_usercomp', array('competencyid' => $competency->id, 'proficiency' => 1));
        }
    }
    usort($competencies, function($a, $b){
        return $a->proficiency - $b->proficiency;
    });
    return $competencies;
}
/*
var data = {
    type: "bar",
    courses: [
        {
            key: "course1",
            value: 1,
            color: "#265a88",
            title: "Curso 1"
        },
        {
            key: "course2",
            value: 2,
            color: "#419641",
            title: "Curso 2"
        },
        {
            key: "course3",
            value: 3,
            color: "#2aabd2",
            title: "Curso 3"
        },
        {
            key: "course4",
            value: 4,
            color: "#eb9316",
            title: "Curso 4"
        }
    ]
};
*/
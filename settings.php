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
 * Plugin administration pages are defined here.
 *
 * @package     local_dominosdashboard
 * @category    admin
 * @copyright   2019 Subitus <contacto@subitus.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    // TODO: Define the plugin settings page.
    // https://docs.moodle.org/dev/Admin_settings

    $ldm_pluginname = "local_dominosdashboard";
    require_once(__DIR__ . '/lib.php');
    $settings = new theme_boost_admin_settingspage_tabs($ldm_pluginname, get_string('pluginname', $ldm_pluginname));
    $ADMIN->add('localplugins', $settings);

    if(isset($_GET['section'])){
        if($_GET['section'] == 'local_dominosdashboard'){
            $PAGE->requires->js(new moodle_url($CFG->wwwroot . '/local/dominosdashboard/settings.js'));
            $courses = local_dominosdashboard_get_courses(true);
            
            $page = new admin_settingpage($ldm_pluginname . 'tab_category', get_string('categoriestab', $ldm_pluginname));

            $name = $ldm_pluginname . '/' . LOCALDOMINOSDASHBOARD_CATEGORY_PARENT_NAME;
            $title = get_string(LOCALDOMINOSDASHBOARD_CATEGORY_PARENT_NAME, $ldm_pluginname);
            $description = get_string(LOCALDOMINOSDASHBOARD_CATEGORY_PARENT_NAME . '_desc', $ldm_pluginname);
            $options = local_dominosdashboard_get_categories();
            $setting = new admin_setting_configselect($name, $title, $description, 1, $options);
            $page->add($setting);

            $settings->add($page);

            $page = new admin_settingpage($ldm_pluginname . 'tab_admins', get_string('tab_admins', $ldm_pluginname));

            $config_name = 'allowed_email_admins';
            $name = $ldm_pluginname . '/' . $config_name;
            $title = get_string($config_name, $ldm_pluginname);
            $description = get_string($config_name . '_desc', $ldm_pluginname);
            $setting = new admin_setting_configtext($name, $title, $description, "@alsea.com.mx", PARAM_TEXT);
            $page->add($setting);

            $settings->add($page);

            $page = new admin_settingpage($ldm_pluginname . 'tab_courses', get_string('tab_courses', $ldm_pluginname));

            $completions = local_dominosdashboard_get_completion_modes();
            $badges = local_dominosdashboard_get_badges();
            $default_completion_mode = 1;
            $charts = local_dominosdashboard_get_charts();
            $courses_min = array();
            foreach($courses as $course){
                $page->add(new admin_setting_heading('course_config'.$course->id, $course->fullname, $course->fullname));
                $config_name = 'course_completion_' . $course->id;
                $name = $ldm_pluginname . '/' . $config_name;
                $title = get_string('completion_mode', $ldm_pluginname);
                $description = get_string('completion_mode' . '_desc', $ldm_pluginname);
                $setting = new admin_setting_configselect($name, $title, $description, 0, $completions);
                $page->add($setting);
                
                $name = $ldm_pluginname . '/course_main_chart_' . $course->id;
                $title = get_string('chart', $ldm_pluginname);
                $description = get_string('chart' . '_desc', $ldm_pluginname);        
                $setting = new admin_setting_configselect($name, $title, $description, null, $charts);
                $page->add($setting);

                // $name = $ldm_pluginname . '/course_main_chart_color_' . $course->id;
                // $title = get_string('course_main_chart_color', $ldm_pluginname);
                // $description = get_string('course_main_chart_color' . '_desc', $ldm_pluginname);        
                // $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
                // $page->add($setting);
                
                $gradable_items = local_dominosdashboard_get_gradable_items($course->id);
                $config_name = 'course_grade_activity_completion_' . $course->id;
                $name = $ldm_pluginname . '/' . $config_name;
                $title = get_string('course_grade_activity_completion', $ldm_pluginname);
                $description = get_string('course_grade_activity_completion_desc', $ldm_pluginname);
                $setting = new admin_setting_configselect($name, $title, $description, null, $gradable_items);
                $page->add($setting);
                
                $name = $ldm_pluginname . '/course_minimum_score_' . $course->id;
                $title = get_string('course_minimum_score', $ldm_pluginname);
                $description = get_string('course_minimum_score_desc', $ldm_pluginname);
                $setting = new admin_setting_configtext($name, $title, $description, 80, PARAM_INT);
                $page->add($setting);

                $config_name = 'badge_completion_' . $course->id;
                $name = $ldm_pluginname . '/' . $config_name;
                $title = get_string('badge_completion', $ldm_pluginname);
                $description = get_string('badge_completion' . '_desc', $ldm_pluginname);
                $setting = new admin_setting_configselect($name, $title, $description, null, $badges);
                $page->add($setting);
                
                $activities = local_dominosdashboard_get_activities($course->id);
                $config_name = 'course_completion_activity_' . $course->id;
                $name = $ldm_pluginname . '/' . $config_name;
                $title = get_string('course_completion_activity', $ldm_pluginname);
                $description = get_string('course_completion_activity_desc', $ldm_pluginname);
                $setting = new admin_setting_configselect($name, $title, $description, null, $activities);
                $page->add($setting);

                $courses_min[$course->id] = $course->fullname;
            }
            $settings->add($page);


            $page = new admin_settingpage($ldm_pluginname . 'tab_kpis', get_string('tab_kpis', $ldm_pluginname));
            $kpis = local_dominosdashboard_get_KPIS();

            foreach($kpis as $key => $kpi){
                $name = $ldm_pluginname . '/kpi_' . $key;
                $title = get_string('kpi_relation', $ldm_pluginname) . ': ' . $kpi;
                $description = get_string('kpi_relation' . '_desc', $ldm_pluginname);        
                $setting = new admin_setting_configmultiselect($name, $title, $description, array(), $courses_min);
                $page->add($setting);
            }

            $name = $ldm_pluginname . '/ideal_rotacion';
            $title = get_string('ideal_rotacion', $ldm_pluginname);
            $description = get_string('ideal_rotacion' . '_desc', $ldm_pluginname);
            $setting = new admin_setting_configtext($name, $title, $description, 94, PARAM_INT);
            $page->add($setting);

            $name = $ldm_pluginname . '/ideal_cobertura';
            $title = get_string('ideal_cobertura', $ldm_pluginname);
            $description = get_string('ideal_cobertura' . '_desc', $ldm_pluginname);
            $setting = new admin_setting_configtext($name, $title, $description, 85, PARAM_INT);
            $page->add($setting);

            $settings->add($page);

            $page = new admin_settingpage($ldm_pluginname . 'tab_filtros', get_string('tab_filtros', $ldm_pluginname));

            $profileFields = local_dominosdashboard_get_profile_fields();
            
            $config_name = 'allowed_email_addresses_in_course';
            $name = $ldm_pluginname . '/' . $config_name;
            $title = get_string($config_name, $ldm_pluginname);
            $description = get_string($config_name . '_desc', $ldm_pluginname);
            $setting = new admin_setting_configtext($name, $title, $description, "@alsea.com.mx", PARAM_TEXT);
            $page->add($setting);

            foreach(local_dominosdashboard_get_indicators() as $indicator){
                $original_indicator = $indicator;
                if($original_indicator == 'ccosto'){
                    $title = get_string('filtro', $ldm_pluginname) . ' nombre del ccosto';
                }else{
                    $title = get_string('filtro', $ldm_pluginname) . ' ' .$original_indicator;
                }
                $indicator = "filtro_" . $indicator;
                $name = $ldm_pluginname . '/' . $indicator;
                $description = get_string('filtro' . '_desc', $ldm_pluginname) . ' ' .$original_indicator;
                $setting = new admin_setting_configselect($name, $title, $description, null, $profileFields);
                $page->add($setting);

                $title = "El catálogo {$original_indicator} permite valores vacíos";
                $indicator = "allow_empty_" . $original_indicator;
                $name = $ldm_pluginname . '/' . $indicator;
                $description = "Si selecciona esta opción, el filtro {$original_indicator} devolverá como en el catálogo a las personas con este campo vacío";
                $setting = new admin_setting_configselect($name, $title, $description, null, [0 => 'No', 1 => 'Sí']);
                $page->add($setting);
            }

            $title = get_string('filtro', $ldm_pluginname) . ' id del ccosto';
            $indicator = "idccosto"; // local_dominosdashboard/ccosto get_config('local_dominosdashboard', 'ccosto')
            $name = $ldm_pluginname . '/filtro_' . $indicator;
            $description = get_string('filtro' . '_desc', $ldm_pluginname) . ' ' .$indicator;
            $setting = new admin_setting_configselect($name, $title, $description, null, $profileFields);
            $page->add($setting);

            $settings->add($page);


            $page = new admin_settingpage($ldm_pluginname . 'coursetypestab', get_string('coursetypestab', $ldm_pluginname));

            $name = $ldm_pluginname . '/' . 'LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS';
            $title = get_string('LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS', $ldm_pluginname);
            $description = get_string('LOCALDOMINOSDASHBOARD_CURSOS_CAMPANAS' . '_desc', $ldm_pluginname);
            $setting = new admin_setting_configmultiselect($name, $title, $description, array(), $courses_min);
            $page->add($setting);

            $name = $ldm_pluginname . '/' . 'excluded_courses';
            $title = get_string('excluded_courses', $ldm_pluginname);
            $description = get_string('excluded_courses' . '_desc', $ldm_pluginname);
            $setting = new admin_setting_configmultiselect($name, $title, $description, array(), $courses_min);
            $page->add($setting);

            $settings->add($page);


        }
    }

}

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

// defined('MOODLE_INTERNAL') || die();

header("Content-Type: application/json");
require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');
if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['request_type'])){
    // die(local_dominosdashboard_format_response($_POST));
    $request_type = $_POST['request_type'];
    switch($request_type){
        case 'catalogue':
        // die($_POST['catalogue_name']);
            if(!empty($_POST['catalogue_name'])){
                $catalogue_name = $_POST['catalogue_name'];
                die(local_dominosdashboard_format_response(local_dominosdashboard_get_catalogue($catalogue_name)));
            }else{
                die(local_dominosdashboard_error_response('catalogue_name (string) not found'));
            }
            break;
        case 'course_completion':
            if(!empty($_POST['courseid'])){
                $courseid = $_POST['courseid'];
                _log("Parámetros enviados", $_POST);
                die(local_dominosdashboard_format_response(local_dominosdashboard_get_course_information($courseid,  $coursename = "",  $get_kpi_comparison = false,  $params = $_POST)));
            }else{
                die(local_dominosdashboard_error_response("courseid (int) not found"));
            }
            break;
        case 'course_list':
            if(!empty($_POST['type'])){
                die(local_dominosdashboard_format_response(local_dominosdashboard_get_courses_overview($_POST['type'], $_POST)));
            }else{
                die(local_dominosdashboard_error_response('type (int) not found'));
            }
            break;
        default:
            die(local_dominosdashboard_error_response("request_type not allowed"));
            break;
    }
}
die(local_dominosdashboard_error_response($_SERVER['REQUEST_METHOD'] . " method not allowed"));
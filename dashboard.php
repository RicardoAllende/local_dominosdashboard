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
$context_system = context_system::instance();
require_once(__DIR__ . '/lib.php');
local_dominosdashboard_user_has_access();
require_login();

$PAGE->set_url($CFG->wwwroot . "/local/dominosdashboard/dashboard.php");
$PAGE->set_context($context_system);
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'local_dominosdashboard'));

echo $OUTPUT->header();


?>
<link href="estilos.css" rel="stylesheet">
<iframe src="dashboard_iframe.php" id="iframe_ldm" frameborder="0" style="width: 100%; overflow: hidden;"></iframe>
<div class="btnimprimir">
    <button class="btn btn-primary" onclick="imprimir();">Imprimir</button>       
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('region-main').style.width = "100%";
        require(['jquery'], function ($) {
            setInterval(function() { iResize('iframe_ldm'); }, 100);
        });
    });
    function iResize(frame_id) {
        element = document.getElementById(frame_id);
        if(element != null){
            if(element.contentWindow.document.body.offsetHeight != null){
                size = element.contentWindow.document.body.offsetHeight + 'px';
                document.getElementById(frame_id).style.height = size;
            }
        }
    }

    function imprimir() {
        window.print();
    }

</script>
<?php

echo $OUTPUT->footer();
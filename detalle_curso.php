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
require_capability('local/dominosdashboard:view', $context_system);
require_once(__DIR__ . '/lib.php');
require_login();
$courseid = optional_param('id', 0, PARAM_INT);
$PAGE->set_url($CFG->wwwroot . "/local/dominosdashboard/detalle_curso.php");
$PAGE->set_context($context_system);
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'local_dominosdashboard'));

echo $OUTPUT->header();
$url = "detalle_curso_iframe.php?id={$courseid}";
?>
<iframe src="<?php echo $url; ?>" id="iframe_ldm" frameborder="0" style="width: 100%; overflow: hidden;"></iframe>
<div>
        <center>
        <button onclick="imprimir();">Imprimir</button>
        </center>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('region-main').style.width = "100%"
        require(['jquery'], function ($) {
            setTimeout(() => { iResize('iframe_ldm'); }, 1000);
        });
    });
    function iResize(frame_id) {
        size = document.getElementById(frame_id).contentWindow.document.body.offsetHeight + 'px';
        document.getElementById(frame_id).style.height = size;
    }
    function imprimir() {
        window.print();
    }

</script>
<?php

echo $OUTPUT->footer();
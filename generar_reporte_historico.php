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
local_dominosdashboard_user_has_access();
$context_system = context_system::instance();
require_once(__DIR__ . '/lib.php');
require_once($CFG->libdir.'/formslib.php');
// require_login();
global $DB;
$PAGE->set_url($CFG->wwwroot . "/local/dominosdashboard/generar_reporte_historico.php");
$PAGE->set_context($context_system);
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('pluginname', 'local_dominosdashboard'));

echo $OUTPUT->header();

class local_dominosdashboard_create_historic_report extends moodleform {
    function definition () {
        $mform = $this->_form;
        $mform->addElement('header', 'settingsheader', "¿Desea crear reporte histórico ahora?");
        $this->add_action_buttons(false, "Crear reporte histórico");
    }
}
$mform = new local_dominosdashboard_create_historic_report();
if ($formdata = $mform->get_data()) {
    local_dominosdashboard_make_all_historic_reports();
    echo $OUTPUT->heading("Reportes creados en: " . date('d-m-Y'));
    $mform->display();
}else{
    $mform->display();
}
?>
<?php
echo $OUTPUT->footer();
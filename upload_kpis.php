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
 *
 * @package     local_dominosdashboard
 * @category    admin
 * @copyright   2019 Subitus <contacto@subitus.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot . '/user/editlib.php');
require_once(__DIR__ . '/lib.php');

/**
 * Upload a file CVS file with KPI'S
 *
 * @copyright  2019 Subitus <contacto@subitus.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class local_dominosdashboard_upload_kpis extends moodleform {
    function definition () {
        $mform = $this->_form;

        $mform->addElement('header', 'settingsheader', get_string('upload'));

        $mform->addElement('filepicker', 'userfile', get_string('file'));
        $mform->addRule('userfile', null, 'required');

        $choices = csv_import_reader::get_delimiter_list();
        $mform->addElement('select', 'delimiter_name', get_string('csvdelimiter', 'tool_uploaduser'), $choices);
        $mform->setDefault('delimiter_name', 'comma');

        $choices = core_text::get_encodings();
        $mform->addElement('select', 'encoding', get_string('encoding', 'tool_uploaduser'), $choices);
        $mform->setDefault('encoding', 'ISO-8859-1'); // default de excel

        // $choices = local_dominosdashboard_get_KPIS();
        // $choices[0] = "Seleccionar tipo de KPI";
        // $mform->addElement('select', 'kpi', get_string('kpi_select', 'local_dominosdashboard'), $choices);
        // $mform->setDefault('kpi', 0);

        $mform->addElement('text', 'year', 'Año correspondiente');
        $mform->setDefault('year', date('Y'));
        $mform->setType('year', PARAM_INT);
        $mform->addRule('year', null, 'required');

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
        $mform->addElement('select', 'month', 'Mes correspondiente', $meses);
        $mform->setDefault('month', date('m'));
        // $mform->setType('month', PARAM_INT);
        // $mform->addRule('month', null, 'required');

        $mform->addElement('selectyesno', 'updateIfExists', "Actualizar si ya existe");

        $this->add_action_buttons(false, get_string('upload_kpi', 'local_dominosdashboard'));
        // $mform->disabledIf('submitbutton', 'kpi', 'eq', 0);
        // $mform->disabledIf('year', 'kpi', 'eq', KPI_SCORCARD);
    }
}



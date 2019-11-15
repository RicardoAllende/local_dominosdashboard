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
 * Dominos Dashboard
 * @package local
 * @author: Subitus <contacto@subitus.com>
 * @date: 2019
 */

require_once(__DIR__ . "/../../config.php");
require_once(__DIR__ . '/lib.php');

// Large exports are likely to take their time and memory.
core_php_time_limit::raise();
raise_memory_limit(MEMORY_EXTRA);
local_dominosdashboard_export_configurable_report();

die;

// Never reached if download = true.
echo $OUTPUT->footer();

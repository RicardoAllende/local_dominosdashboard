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

$tasks = [
    [
        'classname' => 'local_dominosdashboard\task\make_historic_logs',
        'blocking'  => 0,   // cambiar a 1 si la tarea tendrá un impacto alto en la plataforma
        'minute'    => 'R', // Minuto aleatorio
        'hour'      => '3', // 3 AM
        'day'       => '*', // Todos los días
        'month'     => '*', // Todos los meses
        'dayofweek' => '6', // cada sábado
    ],
    [
        'classname' => 'local_dominosdashboard\task\make_course_cache',
        'blocking'  => 0,   // cambiar a 1 si la tarea tendrá un impacto alto en la plataforma
        'minute'    => 'R', // Minuto aleatorio
        'hour'      => '8', // 8 AM
        'day'       => '*', // Todos los días
        'month'     => '*', // Todos los meses
        'dayofweek' => '6', // cada sábado
    ],
    [
        'classname' => 'local_dominosdashboard\task\make_course_cache',
        'blocking'  => 0,   // cambiar a 1 si la tarea tendrá un impacto alto en la plataforma
        'minute'    => 'R', // Minuto aleatorio
        'hour'      => '15', // 3 PM
        'day'       => '*', // Todos los días
        'month'     => '*', // Todos los meses
        'dayofweek' => '6', // cada sábado
    ],
    // [ // prueba cada minuto
    //     'classname' => 'local_dominosdashboard\task\make_historic_logs',
    //     'blocking'  => 0,   // cambiar a 1 si la tarea tendrá un impacto alto en la plataforma
    //     'minute'    => '*', // cada minuto
    //     'hour'      => '*', // cada hora
    //     'day'       => '*', // Todos los días
    //     'month'     => '*', // Todos los meses
    //     'dayofweek' => '*', // todos los días
    // ],
];
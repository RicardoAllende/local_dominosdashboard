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
 * Listado de usuarios inscritos en un curso
 *
 * @package     local_dominosdashboard
 * @category    admin
 * @copyright   2019 Subitus <contacto@subitus.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');
local_dominosdashboard_user_has_access();
$PAGE->set_context(context_system::instance());

$currentdate = date('d-M-Y');
$PAGE->set_url($CFG->wwwroot . '/local/dominosdashboard/reporte_personalizado.php');

// $PAGE->set_pagelayout('admin');
// $PAGE->set_title('Reporte personalizado ' . $currentdate);

$title = 'Reporte personalizado ' . $currentdate;
$orderFields = $CFG->wwwroot . '/local/dominosdashboard/orden.php';
$settingsurl = $CFG->wwwroot . '/admin/settings.php?section=local_dominosdashboard';

// echo $OUTPUT->header();
$report_info = local_dominosdashboard_get_report_columns(local_dominosdashboard_course_users_pagination);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css" integrity="sha384-2hfp1SzUoho7/TsGGGDaFdsuuDL0LX2hnUp6VkX3CUQ2K4K+xjboZdsXyp4oUHZj" crossorigin="anonymous">
    <link rel="stylesheet" href="css/jquery.loadingModal.css">
    <link href="estilos.css" rel="stylesheet">
</head>
<body>    

<table id='empTable' class='display dataTable table table-bordered'>    
    <thead>
        <tr>
            <?php echo $report_info->table_code; ?>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <?php echo $report_info->table_code; ?>
        </tr>
    </tfoot>
</table>


<div class="row" style="padding-bottom: 2%; padding-top: 2%; max-width: 100%">
    <div class="col-sm-6" style="text-align: center;">
        <h4>Si el reporte no tiene la estructura necesaria, por favor ordene los campos del reporte en el siguiente enlace: </h4>
        <a class="btn btn-primary btn-lg" href="#" onclick="top.window.location.href='<?php echo $orderFields; ?>'">Configurar posiciones</a>
    </div>
    <div class="col-sm-6" style="text-align: center;">
        <h4>Si desea modificar los campos que aparecerán, o los cursos incluídos por favor edítelo en Configuraciones del plugin-> campos del reporte </h4>
        <a class="btn btn-primary btn-lg" href="#" onclick="top.window.location.href='<?php echo $settingsurl; ?>'">Configuraciones del plugin</a>
    </div>
</div>

<!-- Datatable CSS -->
<link href='datatables/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>

<!-- <link href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'> -->
<link href="datatables/buttons.dataTables.min.css" rel="stylesheet">

<!-- jQuery Library -->
<script src="js/jquery.min.js"></script>

<!-- Datatable JS -->
<script src="datatables/jquery.dataTables.min.js"></script>
<script src="datatables/dataTables.buttons.min.js"></script>
<script src="datatables/buttons.flash.min.js"></script>
<script src="datatables/jszip.min.js"></script>
<script src="datatables/pdfmake.min.js"></script>
<script src="datatables/vfs_fonts.js"></script>
<script src="datatables/buttons.html5.min.js"></script>
<script src="datatables/buttons.print.min.js"></script>

<!-- Table -->
<script>
    $(document).ready(function(){
        $('#empTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url':'services.php',
                data: {
                    request_type: 'course_users_pagination',
                }
            },
            lengthMenu: [[10, 15, 20, 100, -1], [10, 15, 20, 100, "Todos los registros"]],
            'dom': 'Bfrtip',
            "pageLength": 10,
            buttons: [
                {
                    extend: 'excel',
                    text: '<span class="fa fa-file-excel-o"></span> Exportar a excel',
                    exportOptions: {
                        modifier: {
                            search: 'applied',
                            order: 'applied'
                        },
                        columns: [<?php echo $report_info->ajax_printed_rows; ?>],
                    },
                },
                {
                    extend: 'excel',
                    text: '<span class="fa fa-file-o"></span> Exportar a CSV',
                    exportOptions: {
                        modifier: {
                            search: 'applied',
                            order: 'applied'
                        },
                        columns: [<?php echo $report_info->ajax_printed_rows; ?>],
                    },
                },
                'pageLength',
                'copy',
            ],
            'columns': [
                <?php echo $report_info->ajax_code; ?>
            ],
            language: {
                "url": "datatables/Spanish.json",
            },
            "columnDefs": [
                { "targets": [<?php echo $report_info->ajax_link_fields; ?>], "orderable": false }
            ]
            // language: {
            // },
            // buttons: [ { extend: 'excel', action: newExportAction } ],
        });
    });
</script>
</body>
</html>
<?php
// echo $OUTPUT->footer();
<?php
ob_start();
$fecha=date('Y-m-d');
header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: attachment; filename=evaluacion_objetiva_$fecha.xls");
header('Pragma: public');
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1 
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1 
header('Pragma: no-cache');
header('Expires: 0');
header('Content-Transfer-Encoding: none');
header('Content-type: application/vnd.ms-excel;charset=utf-8');// This should work for IE & Opera 
header('Content-type: application/x-msexcel; charset=utf-8'); // This should work for the rest 
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
error_reporting(0);

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');
// _log($_POST);
if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['request_type'])){
    $request_type = $_POST['request_type'];
    switch($request_type){
        case 'course_completion':
            if(!empty($_POST['courseid'])){
                $courseid = $_POST['courseid'];
                $resultado = local_dominosdashboard_get_course_information($courseid, $get_kpis = true, $get_activities = true, $params = $_POST, true);
                _log($resultado);
            // }else{
            //     die(local_dominosdashboard_error_response("courseid (int) not found"));
            }
            break;
        case 'course_list':
            $resultado = local_dominosdashboard_get_courses_overview(LOCALDOMINOSDASHBOARD_AVAILABLE_COURSES, $_POST);
            _log($resultado);
            break;
    }
}
?>
<!-- <style>
table, th, td {
  border: 1px solid black;
}
</style>
<table id="example">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Número de empleado</th>
            <th>Centro</th>
            <th>Puesto</th>
            <th>Fecha de ingreso</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // for ($i=1; $i <= 100; $i++) { 
        //     echo "<tr>";
        //     echo "<td>Nombre {$i}</td>";
        //     echo "<td>Número de empleado{$i}</td>";
        //     echo "<td>Centro {$i}</td>";
        //     echo "<td>Puesto {$i}</td>";
        //     echo "<td>Fecha de ingreso {$i}</td>";
        //     echo "</tr>";
        // }
        ?>
    </tbody>
</table> -->
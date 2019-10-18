<?php
ob_start();
$fecha = date('Y-m-d_H-i-s');
header("Content-type: application/vnd.ms-excel; name='excel'");

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
$error = "Solo se permite la petición de tipo POST";
if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['report_type'])){
    $report_type = $_POST['report_type'];
    switch($report_type){
        // case 'course_completion':
        //     if(!empty($_POST['courseid'])){
        //         $courseid = $_POST['courseid'];
        //         $resultado = local_dominosdashboard_get_course_information($courseid, $get_kpis = true, $get_activities = true, $params = $_POST, false);
        //         if(!empty($resultado)){
        //             $nombre = local_dominosdashboard_create_slug($resultado->title);
        //             header("Content-Disposition: attachment; filename={$nombre}_{$fecha}.xls");
        //             echo "<style> table, th, td { border: 1px solid black; } </style>";
        //             echo '<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">';
        //             dd($resultado);
        //         }else{
        //             $error = "No se encontró el curso en la base de datos";
        //             // dd('Resultado vacío: detalle del curso');
        //         }
        //     // }else{
        //     //     die(local_dominosdashboard_error_response("courseid (int) not found"));
        //     }
        //     break;
        case 'course_list':
            $ideal_cobertura = get_config('local_dominosdashboard', 'ideal_cobertura');
            if($ideal_cobertura === false){
                $ideal_cobertura = 94;
            }
            $cursos = local_dominosdashboard_get_courses_overview(LOCALDOMINOSDASHBOARD_AVAILABLE_COURSES, $_POST);
            if(!empty($cursos)){
                $nombre = local_dominosdashboard_create_slug('Detalle de los cursos');
                header("Content-Disposition: attachment; filename=cursos_dominos_dashboard_$fecha.xls");
                echo '<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">';
                echo "<style> table, th, td { border: 1px solid black; } </style>";
                echo "<table id=\"example\">
                <thead>
                    <tr>
                        <th>Nombre del curso</th>
                        <th>Usuarios inscritos</th>
                        <th>Usuarios aprobados</th>
                        <th>Ideal de cobertura</th>
                        <th>Porcentaje de aprobación</th>
                    </tr>
                </thead>
                <tbody>";
                // _log($cursos);
                foreach($cursos['result'] as $curso){
                    // _log($curso);
                    echo "<tr>";
                    echo "<td>{$curso->title}</td>";
                    echo "<td>{$curso->enrolled_users}</td>";
                    echo "<td>{$curso->approved_users}</td>";
                    echo "<td>{$ideal_cobertura}</td>";
                    echo "<td>{$curso->percentage}</td>";
                    echo "</tr>";
                }
                $filters_header = false;
                $filters = local_dominosdashboard_get_selected_params($_POST);
                foreach($filters as $filter => $values){
                    if(!$filters_header){
                        echo "
                        <tr></tr><tr></tr>
                        <tr>
                            <td>Filtros aplicados:</td>
                            <td colspan='4'></td>
                        </tr>";
                        $filters_header = true;
                    }
                    echo "
                    <tr>
                        <td>{$filter}:</td>
                        <td colspan='4'>{$values}</td>
                    </tr>";
                }
                echo "";
                echo "
                </tbody></table>";
                return;
            }else{
                $error = "Listado de cursos vacío";
            }
            break;
    }
}
header("Content-Disposition: attachment; filename=error_$fecha.xlsx");
echo $error;
?>
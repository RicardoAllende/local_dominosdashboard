<?php

#Componentes necesarios para el proceso.
ini_set('memory_limit', '-1');
require_once('../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/authlib.php');
require_once($CFG->dirroot.'/user/filters/lib.php');
require_once($CFG->dirroot.'/user/lib.php');
require_once($CFG->libdir.'/PHPExcel.php');
require_once($CFG->libdir.'/PHPExcel/IOFactory.php');
require_once($CFG->libdir.'/bancoppel_api/api.php');
require_once($CFG->libdir.'/enrollib.php');

global $DB;


$file = $CFG->dirroot."/local/dominosdashboard/reportes/objetiva_porcentajes.csv";
$objPHPExcel = PHPExcel_IOFactory::load($file);

$worksheet = $objPHPExcel->getActiveSheet();

$fstart = 9;
$result = $DB->get_records($table = 'user');
foreach($result as $fila){
	$cstart = 0;
	foreach($fila as $elemento){ 
		$worksheet -> setCellValueByColumnAndRow($cstart, $fstart, $elemento);
		$cstart++;
	}
	$fstart++;
}
//header('Content-Type: application/vnd.ms-excel');
//header('Content-Disposition: attachment;filename="objetiva_porcentajes.xls"');
//header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//$objWriter->save($CFG -> dirroot.'/admin/api_reportes/tmp/objetiva_porcentajes.xls');
$objWriter->save($CFG->dirroot.'/local/dominosdashboard/reportes/objetiva_porcentajes.csv');

//header("Location: /admin/api_reportes/tmp/objetiva_porcentajes.xls");//////////////desarrollo
//header("Location: /moodle/admin/api_reportes/tmp/objetiva_porcentajes.xls");//////////////Produccion

header("Location: /local/dominosdashboard/reportes/objetiva_porcentajes.csv");//////////////Produccion


?>
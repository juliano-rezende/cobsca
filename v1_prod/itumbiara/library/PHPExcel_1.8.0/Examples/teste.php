<?php
/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Sao_Paulo');

require_once dirname(__FILE__) . '/../Classes/PHPExcel/IOFactory.php';//carrega a biblioteca

$fileType = 'Excel5';// versão do excel
$fileName = 'clientes.xls';//planilha principal
$fileName1 = 'clientes1.xls';//novo nome

$objReader = PHPExcel_IOFactory::createReader($fileType);// classe do excel
$objPHPExcel = $objReader->load($fileName);// carrega o arquivo

//escreve na planilha
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', 'jose');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4', 'jose@gmail.com');

//fecha o arquivo
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
//salva o arquivo
$objWriter->save($fileName1);

?>
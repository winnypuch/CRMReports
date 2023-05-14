<?php require_once 'PHPExcel.php';
require_once 'PHPExcel/IOFactory.php';
// Create new PHPExcelobject
$objPHPExcel = new PHPExcel();
// Create a first sheet, representing sales data
$objPHPExcel->setActiveSheetIndex(0); $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Something');
// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Name of Sheet 1');
// Create a new worksheet, after the default sheet
$objPHPExcel->createSheet();
// Add some data to the second sheet, resembling some different data types
$objPHPExcel->setActiveSheetIndex(1); $objPHPExcel->getActiveSheet()->setCellValue('A1', 'More data');
// Rename 2nd sheet
$objPHPExcel->getActiveSheet()->setTitle('Second sheet'); // Redirect output to a client's web browser (Excel5)
header('Content-Type: application/vnd.ms-excel'); header('Content-Disposition: attachment;filename="name_of_file.xls"'); header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); $objWriter->save('php://output');


$filename='file-name' . '.xls'; //save our workbook as this file name header('Content-Type: application/vnd.ms-excel'); //mime type header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name header('Cache-Control: max-age=0'); //no cache

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
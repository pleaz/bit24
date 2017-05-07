<?php
/**
 * Created by PhpStorm.
 * User: pleaz
 * Date: 07/05/2017
 * Time: 17:13
 */

require_once ('init.php');

/**  Identify the type of $inputFileName  **/
$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
/**  Create a new Reader of the type that has been identified  **/
$objReader = PHPExcel_IOFactory::createReader($inputFileType);
/**  Load $inputFileName to a PHPExcel Object  **/
$objPHPExcel = $objReader->load($inputFileName);
$objWorksheet = $objPHPExcel->getActiveSheet();
$i=1;
foreach ($objWorksheet->getRowIterator() as $row) {

    $column_A_Value = $objPHPExcel->getActiveSheet()->getCell("A$i")->getValue();
    $column_B_Value = $objPHPExcel->getActiveSheet()->getCell("B$i")->getValue();
    if($column_A_Value!==null){
        $insertArray = [
            'name'   => $column_A_Value,
            'phone'  => $column_B_Value
        ];
        $newUserId = $db->insert('users', $insertArray);
    }

    $i++;
}
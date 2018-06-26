<?php
define('PHPEXCEL_ROOT', dirname(__FILE__) . '/');
class ExcelToArrary
{
    public function __construct() {
        require(PHPEXCEL_ROOT . 'PHPExcel.php');	
        require(PHPEXCEL_ROOT . 'PHPExcel/Writer/IWriter.php');	
        require(PHPEXCEL_ROOT . 'PHPExcel/Writer/Abstract.php');	
        require(PHPEXCEL_ROOT . 'PHPExcel/Writer/Excel5.php');	
        require(PHPEXCEL_ROOT . 'PHPExcel/Writer/Excel2007.php');	
        require(PHPEXCEL_ROOT . 'PHPExcel/IOFactory.php');	
    }

    public function read($filename,$encode,$file_type){
            if(strtolower ( $file_type )=='xls')//判断excel表类型为2003还是2007
            {
            	require(PHPEXCEL_ROOT.'PHPExcel/Reader/Excel5.php');	
                
                $objReader = PHPExcel_IOFactory::createReader('Excel5');
                $objReader = PHPExcel_IOFactory::createReader('Excel5');

            }elseif(strtolower ( $file_type )=='xlsx')
            {
            	require(PHPEXCEL_ROOT . 'PHPExcel/Reader/Excel2007.php');	
               
                $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            }
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load($filename);
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $highestRow = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $excelData = array();
            for ($row = 1; $row <= $highestRow; $row++) {
                for ($col = 0; $col < $highestColumnIndex; $col++) {
                    $excelData[$row][] =(string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                    }
            }
            return $excelData;
    }

}
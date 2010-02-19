<?php
$target = "uploaded_spreadsheets/";
$target = $target . basename( $_FILES['uploaded']['name']);
$ok=1;

$uploaded_type = $_FILES['uploaded']['type'];

if (!($uploaded_type=="application/vnd.ms-excel"))
{
	echo "You may only upload XLS files.<br>";
	$ok=0;
}

if ($ok==0)
{
	echo "Sorry your file was not uploaded";
}


else{
	if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target))
	{
		echo "The file ". basename( $_FILES['uploaded']['name']). " has been uploaded <a href='".$target."'>view</a><br>";
                echo "Cuttoff-grade = ".$_REQUEST['cutoff_grade']."<br>";
                echo "Cuttoff-probability = ".$_REQUEST['cutoff_prob']."<br>";
	}
	else
	{
		echo "Sorry, there was a problem uploading your file";
	}
}

require_once 'Classes/PHPExcel/IOFactory.php';

$objReader = PHPExcel_IOFactory::createReader('Excel5');
$objPHPExcel = $objReader->load($target);
$val = ($objPHPExcel->getActiveSheet()->getCell('A1'));
$temp1 = $val->getvalue();

$highestRow = $objPHPExcel->getActiveSheet()->getHighestRow();
$highestCol = $objPHPExcel->getActiveSheet()->getHighestColumn();
$highestCol = PHPExcel_Cell::columnIndexFromString($highestCol);

$startColumn = 'H';
$startColumn = PHPExcel_Cell::columnIndexFromString($startColumn);

$startRow = 5;

for($row = $startRow; $row < $highestRow; $row++)
{
	for($col = $startColumn; $col < $highestCol; $col++)
	{
		 $temp = ($objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col,$row)->getValue());
		echo $temp;
	}
	echo "<br>";
}



?>

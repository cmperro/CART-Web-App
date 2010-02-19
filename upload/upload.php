<?php
$target = "uploaded_spreadsheets/";
$target = $target . basename( $_FILES['uploaded']['name']);
$COG = $_REQUEST['cutoff_grade'];
$COP = $_REQUEST['cutoff_prob'];

$uploaded_type = $_FILES['uploaded']['type'];

if (!($uploaded_type=="application/vnd.ms-excel"))
{
	echo "You may only upload XLS files.<br>";
	echo "Sorry your file was not uploaded.<br>";
	echo "<a href='file_selection.php'>Please try again.</a>";
	exit(1);
}

if ($COG < 0 || $COG > 100)
{
	echo "Please enter a valid cutoff grade, between 0 and 100.<br>";
	echo "<a href='file_selection.php'>Please try again.</a>";
	exit(1);
}

if ($COP < 0 || $COP > 100)
{
	echo "Please enter a valid cutoff probability, between 0 and 100.<br>";
	echo "<a href='file_selection.php'>Please try again.</a>";
	exit(1);
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


$answerKey = array();

for($col = $startColumn - 1; $col < $highestCol; $col++)
{
	$answerKey[] = ($objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col,4)->getValue());
}

print_r($answerKey);

echo "<br>";


$startRow = 5;

for($row = $startRow; $row < $highestRow + 1; $row++)
{
	for($col = $startColumn - 1; $col < $highestCol; $col++)
	{
		 $temp = ($objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col,$row)->getValue());
		if($temp == NULL)
		{
			$temp = 'X';
		}
		echo $temp;
	}
	echo "<br>";
}



?>
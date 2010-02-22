<html>
<head>
</head>
<body>
<?php
/**
*************************************************************
*This script takes the Uuploaded file and does work.          *
*More specifically, it saves the spreadsheet in a local       *
*directory, checks to make sure that the user didn't enter    *
*absurd cuttoff ranges, and then proceeds to read from the    *
*uploaded excel spreadsheet. The answer key is saved in an    *
*array. The student answers are then parsed and placed into   *
*a two dimensional array.                                     *
**************************************************************/


$target = "uploaded_spreadsheets/";
$target = $target . basename( $_FILES['uploaded']['name']);
$COG = $_REQUEST['cutoff_grade'];
$COP = $_REQUEST['cutoff_prob'];
$uploaded_type = $_FILES['uploaded']['type'];

//Various Error check, i.e. if the document uploaded is indeed
//a 2003 Excel Spreadsheet, and making sure the two cuttoffs
//are in the acceptable range
if (!($uploaded_type=="application/vnd.ms-excel"))
{
	echo "You may only upload XLS files.<br>";
	echo "Sorry your file was not uploaded.<br>";
	echo "<a href='protected.php'>Please try again.</a>";
	exit(1);
}

if ($COG < 0 || $COG > 100 || strlen($COG) < 1)
{
	echo "Please enter a valid cutoff grade (NOT percentage), between 0 and 100.<br>";
	echo "<a href='protected.php'>Please try again.</a>";
	exit(1);
}

if ($COP < 0 || $COP > 1 || strlen($COP) < 1)
{
	echo "Please enter a valid cutoff probability, between 0 and 1.<br>";
	echo "<a href='protected.php'>Please try again.</a>";
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

require_once 'includes/phpExcel/Classes/PHPExcel/IOFactory.php';
include 'includes/minegrades.php';

//Set-up a reader to parse the recently uploaded Excel Spreadsheet
$objReader = PHPExcel_IOFactory::createReader('Excel5');
$objPHPExcel = $objReader->load($target);
//$val = ($objPHPExcel->getActiveSheet()->getCell('A1'));
//$temp1 = $val->getvalue();


//Ascertain the last filled Row and Column. This dynamically figures out how many students took
//the test and how many questions the test was. This way the user doesn't need to be asked these
//particulars
$highestRow = $objPHPExcel->getActiveSheet()->getHighestRow();
$highestCol = $objPHPExcel->getActiveSheet()->getHighestColumn();
//Convert String Column to Int
$highestCol = PHPExcel_Cell::columnIndexFromString($highestCol);
//Hard-coded position of what column student answers start. This is the same for every sheet
$startColumn = 'H';
$startColumn = PHPExcel_Cell::columnIndexFromString($startColumn);




//Set up and populate the Answer Key Array.
$answerKey = array();

for($col = $startColumn - 1; $col < $highestCol; $col++)
{
	$answerKey[] = ($objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col,4)->getValue());
}

//print_r($answerKey);
//echo "<br>";


//Set up and populate the Student Answer Array. Start row is hard-coded. The answers start here for
//every spreadsheet.
$startRow = 5;
$studentAns = array(array());

for($row = $startRow; $row < $highestRow + 1; $row++)
{
	$ithStudent = array();
	for($col = $startColumn - 1; $col < $highestCol; $col++)
	{
		 $temp = ($objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col,$row)->getValue());
		if($temp == NULL)
		{
			$temp = 'X';
		}
		//echo $temp;
		$ithStudent[] = $temp;
	}
	//echo "<br>";
	$studentAns[] = $ithStudent;
}

//Test Routine - To Be Deleted
/*
$arrhigh = $highestRow - $startRow;
for($k = 1; $k < $arrhigh + 2; $k++)
{
	print_r($studentAns[$k]);
	echo "<br>";
}
*/

$stats = mineGrades($COG, $COP, $answerKey, $studentAns);
print($stats->message() . "\n");


//echo "<pre>";
$stats->printHTML(0);
//echo "</pre>";

?>

</body>
</html>

<?php
session_start();
?>
<html>
<head>
<title>Results</title>
</head>
<body>
<?php
$target = "uploaded_spreadsheets/". basename( $_FILES['uploaded']['name']);

//store filename for saving image in graphIt.php
$_SESSION['filename'] = $_FILES['uploaded']['name'];

//if( move_uploaded_file($_FILES['uploaded']['tmp_name'], $target)){
//	$file = basename( $_FILES['uploaded']['name']);
//}
require_once('includes/phpExcel/Classes/PHPExcel/IOFactory.php');
include 'includes/minegrades.php';
/**
*This script takes the uploaded file and does work.<br />
*More specifically, it saves the spreadsheet in a local<br />
*directory, checks to make sure that the user didn't enter<br />
*absurd cuttoff ranges, and then proceeds to read from the<br />
*uploaded excel spreadsheet. The answer key is saved in an<br />
*array. The student answers are then parsed and placed into<br />
*a two dimensional array.<br />
*/
$COG = $_REQUEST['cutoff_grade'];
$COP = $_REQUEST['cutoff_prob'];
$uploaded_type = $_FILES['uploaded']['type'];

//Error check if the document uploaded is indeed
//a 2003 Excel Spreadsheet
if (!($uploaded_type=="application/vnd.ms-excel"))
{
        echo "You may only upload XLS files.<br>";
        echo "Sorry your file was not uploaded.<br>";
        echo "<a href='protected.php'>Please try again.</a>";
        exit(1);
}

else{
        if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target))
        {
                echo "The file ". basename( $_FILES['uploaded']['name']). " has been uploaded <a href='".$target."'>view</a><br>";
                echo "Cutoff-grade = ".$_REQUEST['cutoff_grade']."<br>";
                echo "Cutoff-probability = ".$_REQUEST['cutoff_prob']."<br>";
        }
        else
        {
                echo "Sorry, there was a problem uploading your file";
        }
}
//require_once 'includes/phptreegraph/classes/GDRenderer.php';
//require_once 'includes/phpExcel/Classes/PHPExcel/IOFactory.php';
//include 'includes/minegrades.php';

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
//Hard-coded position of what column student answers start. This is the same for every sheet.
$startColumn = 'H';
$startColumn = PHPExcel_Cell::columnIndexFromString($startColumn);

//numQuestions = (&highestCol - 7);//number of questions
//Error check if the input cutoff values are allowable
if ($COG < 0 || $COG > ($highestCol - 7) || strlen($COG) < 1 || !(is_numeric($COG)))
{
        echo "Please enter a valid cutoff grade (NOT percentage, but the number), between 0 and ".($highestCol - 7).".<br>";
        echo "<a href='protected.php'>Please try again.</a>";
        exit(1);
}

if ($COP < 0 || $COP > 1 || strlen($COP) < 1 || !(is_numeric($COP)))
{
        echo "Please enter a valid cutoff probability, between 0 and 1.<br>";
        echo "<a href='protected.php'>Please try again.</a>";
        exit(1);
}

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
//print($stats->message() . "\n");
//$hope = $stats->printout(0);




$test = $stats->printAllNodes(); //list of all nodes
$final = $stats->printDOT(); //list of node relationships

$DotFile = "saved_pngs/process.dot";
$fh = fopen($DotFile, 'w') or die("can't open file");

$topStatement = "digraph{\n";
fwrite($fh, $topStatement);

for($g = 0; $g < sizeof($test); $g++)
{
	fwrite($fh, $test[$g]);
}

for($c = 0; $c < sizeof($final); $c++)
{
	fwrite($fh, $final[$c]);
}

$bottomStatement = "}";
fwrite($fh, $bottomStatement);
fclose($fh);



$_SESSION['graph']=$hope;
?>
<a href="graphIt.php">Graph It!</a><br>
<a href="protected.php">Input new information?</a>
</body>
</html>

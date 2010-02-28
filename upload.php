<?php
session_start();
/** 
*This script takes the uploaded file and saves a spreadsheet in a local directory. <br> 
*It also checks to make sure the user did not enter absurd cutoff ranges. <br>
*From there, it proceeds to read from the uploaded excel spreadsheet. <br>
*The answer key is saved in an array. The student answers are then parsed into a 2D-array. <br>
*
* @package pagelevel-pagedesc
*/
?>
<html>
<head>
<title>Results</title>
<link rel="styleSheet" type="text/css" href="includes/style.css" />
</head>
<body>


<div id="wrapper" style="width:502px; margin:25px auto">
<div id="top">
  <a href="protected.php">input new information</a> | 
  <a href="signout.php">signout</a></div>
<div id="content" style="width:500px;">
<div id="holder">

<?php

$target = "uploaded_spreadsheets/". basename( $_FILES['uploaded']['name']);

//store filename for saving image in graphIt.php
$_SESSION['filename'] = $_FILES['uploaded']['name'];


/**
* Included Library Information
*
* @link includes/minegrades.php
* @link includes/phpExcel/Classes/PHPExcel/IOFactory.php
* @package includes
*/
require_once('includes/phpExcel/Classes/PHPExcel/IOFactory.php');
include 'includes/minegrades.php';

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
                echo "<h3>Results:</h3>";
                echo "<small>";
                echo "<i>".$_FILES['uploaded']['name']."</i><br>";
                echo "Cutoff-grade = ".$_REQUEST['cutoff_grade']."<br>"; 
                echo "Cutoff-probability = ".$_REQUEST['cutoff_prob']."<br>";
                echo "</small>";
        }
        else
        {
                echo "Sorry, there was a problem uploading your file";
        }
}


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
                $ithStudent[] = $temp;
        }
        $studentAns[] = $ithStudent;
}



$stats = mineGrades($COG, $COP, $answerKey, $studentAns);

$test = $stats->printAllNodes();
$final = $stats->printDOT();

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

//create the image file
$createPng = "dot saved_pngs/process.dot -T png -o saved_pngs/output.png";
system($createPng);

//index 0 is width, 1 is height, in pixels
$imageSize = getimagesize("saved_pngs/output.png");
$scaledWidth = floor($imageSize[0] / 5);
$scaledHeight = floor($imageSize[1] / 5);

?>
<br />
<small>click image for full-size</small>
<br />
<a href="saved_pngs/output.png" target="_blank">
<img src="saved_pngs/output.png" style="width:<?php echo $scaledWidth; ?>px; height:<?php echo $scaledHeight; ?>px; border:2px solid #cccccc" onMouseOver="this.style.border='2px solid #339999'" onMouseOut="this.style.border='2px solid #cccccc'" />
</a><br>

</div><!--close holder-->
</div><!--close content-->
</div><!--close wrapper-->

</body>
</html>

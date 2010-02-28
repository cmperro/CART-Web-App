<?php
session_start();
/**
* <p>
* Signs out user at the end of their session
* </p>
*/
error_reporting(E_ALL);
//clear session variable
unset($_SESSION['loggedInAs']);

$hmm = $_SESSION['filename'];

$mySpreadsheet = "uploaded_spreadsheets/$hmm";
$myDOT = "saved_pngs/process.dot";
$myPNG = "saved_pngs/output.png";


unlink($mySpreadsheet);
unlink($myDOT);
unlink($myPNG);

unset($_SESSION['loggedInAs']);
unset($_SESSION['filename']);


/**
* Redirects user to login page
* @link index.php
*/
echo "<script type='text/javaScript'>window.location='index.php';</script>";
?>



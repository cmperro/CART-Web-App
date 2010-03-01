<?php
session_start();
/**
* Signs out user at the end of their session
*
* @package pagelevel-desc
*/
error_reporting(E_ALL);

@$hmm = $_SESSION['filename'];
$mySpreadsheet = "uploaded_spreadsheets/$hmm";
$myDOT = "saved_pngs/process.dot";
$myPNG = "saved_pngs/output.png";

//delete files
@unlink($mySpreadsheet);
@unlink($myDOT);
@unlink($myPNG);

//clear session variable
unset($_SESSION['loggedInAs']);
unset($_SESSION['filename']);


/**
* Redirects user to login page
*
* @link index.php
*/
echo "<script type='text/javaScript'>window.location='index.php';</script>";
?>

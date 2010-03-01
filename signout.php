<?php
session_start();
/**
* Signs out user at the end of their session
*
* @package pagelevel-desc
*/
error_reporting(E_ALL);

//clear session variable
unset($_SESSION['loggedInAs']);

session_destroy();

/**
* Redirects user to login page
*
* @link index.php
*/
echo "<script type='text/javaScript'>window.location='index.php';</script>";
?>

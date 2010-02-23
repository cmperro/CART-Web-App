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
/**
* Redirects user to login page
* @link index.php
*/
echo "<script type='text/javaScript'>window.location='index.php';</script>";
?>



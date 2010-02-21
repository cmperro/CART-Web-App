<?php
session_start();
error_reporting(E_ALL);
//clear session variable
unset($_SESSION['loggedInAs']);
//redirect user to login page
echo "<script type='text/javaScript'>window.location='index.php';</script>";
?>



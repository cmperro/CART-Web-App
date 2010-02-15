<?php

session_start();
error_reporting(E_ALL);

unset($_SESSION['loggedInAs']);

echo "<script type='text/javaScript'>window.location='index.php';</script>";

?>



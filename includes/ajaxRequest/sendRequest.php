<?php
$email = $_REQUEST['email'];

if( mail("creid.student@manhattan.edu","CART access requested","User at $email has requested access the CART generator.", "FROM:request@CARTgenerator.com") ) 
{
   echo "Your request has been sent";
}

else echo "Your request could not be processed.";
?>


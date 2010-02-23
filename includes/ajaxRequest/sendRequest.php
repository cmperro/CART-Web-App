<?php
$email = $_REQUEST['email'];

$recipients = "creid.student@manhattan.edu,".
              "cperro.student@manhattan.edu,".
              "dulema.student@manhattan.edu,".
              "acurtis.student@manhattan.edu";

$subject = "CART access requested";
$message = "User at $email has requested access to the CART generator";
$from = "FROM: request@CARTgenerator.com";

if( mail($recipients,$subject,$message,$from) ) 
{
   echo "Your request has been sent";
}

else echo "Your request could not be processed.";
?>


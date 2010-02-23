<?php
session_start();
/**
* <p>
* Creates a Login for a user to be stored as an administrator
* </p>
*/
?>
<html>
<head>
<style>
</style>
<link rel="styleSheet" type="text/css" href="includes/style.css" />
</head>
<body>

<div id="wrapper" style="width:327px;">
  <div id="top">
    <a href="protected.php">back</a>
  </div>
 
  <div id="content" style="width:325px;">
  <div id="holder">
<h3>Create Account</h3>

<?php
error_reporting(E_ALL);
//retrieve current user name
@$loggedInAs = $_SESSION['loggedInAs'];

//allow only admin access
if( $loggedInAs == "admin" ) {

//collect input username 
@$user = $_POST['user'];

//make sure new username is at least one char long 
//and that the passwords match
if( (strlen($user) > 0) && (strlen($_POST['pass']) > 0) && 
          ($_POST['pass'] == $_POST['passcopy']) )
{
   $pass = sha1($_POST['pass']);
   $uniqueUser = true;
   $validUser = true;

   //test that username doesn't contain ::
   //password can contain all characters because its encrypted
   if( substr_count($user, "::") > 0 ) $validUser = false;

   //test that user is unique
   $lines = file('accounts.txt');
   foreach ($lines as $line_num => $line) {
      $userAccount = (explode('::', $line));
       if($userAccount[0] == $user) $uniqueUser = false;
   }

   //if the username is valid and not taken already
   //write a user account to the text file 'accounts.txt'
   if( $uniqueUser && $validUser ) { 
     //user :: as delimiter (\n is necessary)
     $account = $user."::".$pass."\n";
     $fp = fopen("accounts.txt",'a') or die("cannot open file");
     if( (fwrite($fp,$account)) ) 
       echo "Account created successfully, <a href='createLogin.php'>again</a>";
     else "Cannot create account:cannot write to file";
   }
   //username contains '::'
   if( !$validUser ) {
      echo "The username cannot contain '::' ".
           "<small><a href='createLogin.php'>again</a></small>";
      exit();
   }
   //username is already taken
   if( !$uniqueUser ) {
      echo "The username is taken. ".
           "<small><a href='createLogin.php'>again</a></small>";
   }
}

//if username is not at least one char long
//or if passwords dont match
else 
{ ?>

<form method="POST" action="createLogin.php">
<table>
<tr><td>username:</td><td><input type="text" name="user" /></td></tr>
<tr><td>password:</td><td><input type="password" name="pass" /></td></tr>
<tr><td>password:</td><td><input type="password" name="passcopy" /></td></tr>
<tr><td></td><td><input type="submit" value="submit" class="go"/></td></tr>
</table>
</form>

<?php } }
//if user is not 'admin'
else echo "You must be the administrator to view this page";
?>

</div><!--close holder-->
</div><!--close content-->
</div><!--close wrapper-->

</body>
</html>

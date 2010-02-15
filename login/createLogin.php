<?php
session_start();
?>
<html>
<head>
<style>
body {
   text-align:center;}
#box {
   margin: 100px auto;
   background: #eeeeee;
   text-align:left;
   border: 1px solid #cccccc;
   width:300px;
   padding: 10px;
   font-family: verdana;
   font-size:12pt;}
h3 {
   font-size:14pt; 
   font-weight:800;
   color:#333333;}

#link {
   float:right;
   margin:5px;
   font-family:verdana;
   font-size:8pt;}

#go {
   border:1px solid #999999;}
</style>
</head>
<body>

<div id="box">

  <div id="link">
    <a href="protected.php">back</a>
  </div>

<h3>Create Account</h3>

<?php
error_reporting(E_ALL);
@$loggedInAs = $_SESSION['loggedInAs'];
if( $loggedInAs == "admin" ) {

@$user = $_POST['user'];

if( (strlen($user) > 0) && (strlen($_POST['pass']) > 0) && 
          ($_POST['pass'] == $_POST['passcopy']) )
{
   $pass = sha1($_POST['pass']);
   $uniqueUser = true;
   $validUser = true;

   //test that username doesnt contain ::
   //password can contain anything because its encrypted
   if( substr_count($user, "::") > 0 ) $validUser = false;

   //test that user is unique
   $lines = file('accounts.txt');
   foreach ($lines as $line_num => $line) {
      $userAccount = (explode('::', $line));
       if($userAccount[0] == $user) $uniqueUser = false;
   }

   if( $uniqueUser && $validUser ) { 
     $account = $user."::".$pass."\n";
     $fp = fopen("accounts.txt",'a') or die("cannot open file");
     if( (fwrite($fp,$account)) ) 
       echo "Account created successfully, <a href='createLogin.php'>again</a>";
     else "Cannot create account:cannot write to file";
   }
   if( !$validUser ) {
      echo "The username cannot contain '::' ".
           "<small><a href='createLogin.php'>again</a></small>";
      exit();}
   if( !$uniqueUser ) {
                        echo "The username is taken. ".
                        "<small><a href='createLogin.php'>again</a></small>";}
}

else 
{ ?>

<form method="POST" action="createLogin.php">
<table>
<tr><td>username:</td><td><input type="text" name="user" /></td></tr>
<tr><td>password:</td><td><input type="password" name="pass" /></td></tr>
<tr><td>password:</td><td><input type="password" name="passcopy" /></td></tr>
<tr><td></td><td><input type="submit" value="submit" id="go"/></td></tr>
</table>
</form>

<?php } }

else echo "You must be the administrator to view this page";

?>

</div>

</body>
</html>

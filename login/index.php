<?php
session_start();
?>
<html>
<head>
<style>
body {
   text-align:center;}
h3 {
   font-family:verdana;
   font-size:14pt;
   font-weight:800;
   color: #333333;}
#loginBox {
   margin: 100px auto;
   text-align:left;
   padding:10px;
   width: 310px;
   border: 1px solid #cccccc;
   background: #eeeeee;
   font-family: verdana;
   font-size:12pt;}
#go {
   border:1px solid #999999;}
</style>
</head>
<body>

<div id="loginBox">
  <h3>User Authentication</h3>
  <form action="index.php" method="POST">
  <table>
  <tr><td>username:</td><td><input type="text" name="user" /></td></tr>
  <tr><td>password</td><td><input type="password" name="pass" /></td></tr>
  <tr><td></td><td><input type="submit" value="submit" id="go"/></td></tr>
  </table>
  </form>

<?php
error_reporting(E_ALL);

if( isset($_POST['user']) && isset($_POST['pass']) )
{
   $username = $_POST['user'];
   $password = sha1($_POST['pass'])."\n";
   $loginSuccess = false;
   $lines = file('accounts.txt');
   foreach ($lines as $line_num => $line) {
      $userAccount = (explode('::', $line));
      if(($userAccount[0] == $username) && ($userAccount[1] == $password))
      {
         $loginSuccess = true;
         break;
      }
   }
   if($loginSuccess) {
      echo "Success!";
      $_SESSION['loggedInAs'] = $username;
      echo "<script type='text/javaScript'>window.location=".
           "'protected.php';</script>";
      }
   else echo "invalid combination";
}
?>


</div>

</body>
</html>

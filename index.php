<?php
session_start();
/**
* <p>
* Checks for successful login and redirects accordingly
* </p>
*/
?>
<html>
<head>
<title>Login</title>
<link rel="styleSheet" type="text/css" href="includes/style.css" />
<script type="text/javaScript" src="includes/ajaxRequest/request.js">
</script>
<script type="text/javaScript">
function requestAccess() {
   var email = prompt("Please enter your email address");
   if (email!=null && email!="") {
      sendRequest(email); 
   }
}
</script>
</head>
<body>

<div id="wrapper" style="width:342px;">

<div id="top"><a href="javascript:void(0);" onClick="requestAccess()">request access</a></div>

<div id="content" style="width:340px;">
  <div id="holder">
  <h3>User Authentication</h3>
  <form action="index.php" method="POST">
  <table>
  <tr><td>username:</td><td><input type="text" name="user" /></td></tr>
  <tr><td>password</td><td><input type="password" name="pass" /></td></tr>
  <tr><td></td><td><input type="submit" value="submit" class="go"/></td></tr>
  </table>
  </form>

<?php
error_reporting(E_ALL);

/**
* Redirect a signed in user to the protected page
* @link protected.php
*/
if( isset($_SESSION['loggedInAs']) ) 
{
  ?>
  <script type="text/javaScript">
  window.location="protected.php";
  </script>
  <?php
}

//check if user attempted a login
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
    /**
    * If login attempt is successful, begin session and redirect
    * @link protected.php 
    */ 
   if($loginSuccess) {
      echo "Success!";
      $_SESSION['loggedInAs'] = $username;
      echo "<script type='text/javaScript'>window.location=".
           "'protected.php';</script>";
      }
   else echo "invalid combination";
}
?>

</div><!--close holder-->
</div><!--close content-->
</div><!-- close wrapper -->

<small><i>See the <span class="url-link"><a href="helpfile.php" target="_blank">README
</a></span> for an explanation of how to use this application.</i></small>

</body>
</html>

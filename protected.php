<?php
session_start();
/**
* Protected page that makes sure a user is signed in. <br> Allows the process to upload a file to be parsed.
*
* @package pagelevel-desc
*/
?>
<html>
<head>
<title>Protected Area</title>
<link rel="styleSheet" type="text/css" href="includes/style.css" />
</head>
<body>

<div id="wrapper" style="width:602px;">

<?php
error_reporting(E_ALL);

//if user is signed in
if(isset($_SESSION['loggedInAs'])) 
{
  ?>
  <div id="top">
  <!--help page-->
  <a href="helpfile.html" target="_blank">help</a> | 

  <!--AddUser Link for Admin--> 
  <?php if($_SESSION['loggedInAs'] == "admin") echo "<a href='createLogin.php'>add users</a> | "; ?>

  <!--Links to Signout-->
  <a href="signout.php">signout</a>
  </div>

<div id="content" style="width:600px;">
  <div id="holder">
 <h3>Welcome to protected area, <i><?php echo $_SESSION['loggedInAs']; ?></i></h3>
<!-- This is the page the user sees when uploading a file. -->
<form enctype="multipart/form-data" action="upload.php" method="POST">
<table>
<tr>
<td>Please choose a file:</td><td><input name="uploaded" type="file" /></td>
</tr>
<tr><td>cutoff-grade:</td><td><input type="text" size="10" name="cutoff_grade"/></td></tr>
<tr><td>cutoff-probability:</td><td><input type="text" size="10" name="cutoff_prob"/></td></tr>
<tr><td></td><td><input type="submit" value="upload" class="go" /></td></tr>
</table>
</form>


  <?php
}

//require a user to sign in
else {
   ?>
   <div id="content">
   <div id="holder">
   You must <a href="index.php">sign in</a> to view this content.
   
  <?php
}

?>
</div><!--close holder-->
</div><!--close content-->
</div><!--close wrapper-->

</body>
</html>

<?php
session_start();
?>
<html>
<head>
<style>
</style>
<link rel="styleSheet" type="text/css" href="includes/style.css" />
</head>
<body>

<div id="wrapper" style="width:600px;">
<?php
error_reporting(E_ALL);

//if user is signed in
if(isset($_SESSION['loggedInAs'])) 
{
  ?>
  Welcome to protected area, <i><?php echo $_SESSION['loggedInAs']; ?></i><br>
  
  <!--addUser link for admin-->
  <?php if($_SESSION['loggedInAs'] == "admin") echo "<a href='createLogin.php'>add users</a>"; ?>

  <!--link to signout-->
  <a href="signout.php">signout</a><br><br>

<!-- This is the page the user sees when uploading a file. -->
<form enctype="multipart/form-data" action="upload.php" method="POST">
<table>
<tr>
<td>Please choose a file:</td><td><input name="uploaded" type="file" /></td>
</tr>
<tr><td>cuttoff-grade:</td><td><input type="text" size="10" name="cutoff_grade"/></td></tr>
<tr><td>cutt-probability:</td><td><input type="text" size="10" name="cutoff_prob"/></td></tr>
<tr><td></td><td><input type="submit" value="upload" class="go" /></td></tr>
</table>
</form>


  <?php
}

//require a user to sign in
else
   echo "You must <a href='index.php'>sign in</a> to view this content.";

?>
</div>

</body>
</html>

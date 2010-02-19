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
   width:600px;
   padding: 10px;
   font-family: verdana;
   font-size:12pt;}
h3 {
   font-size:14pt; 
   font-weight:800;
   color:#333333;}
#go {
   border:1px solid #999999;}
</style>
</head>
<body>

<div id="box">
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

  <!--upload interface-->
  <form enctype="multipart/form-data" action="upload.php" method="POST">
  <table>
  <tr>
  <td>Please choose a file:</td><td><input name="uploaded" type="file" /></td>
  </tr>
  <tr><td>cuttoff-grade:</td><td><input type="text" size="10" name="cutoff_grade"/></td></tr>
  <tr><td>cutt-probability:</td><td><input type="text" size="10" name="cutoff_prob"/></td></tr>
  <tr><td></td><td><input type="submit" value="upload" /></td></tr>
  </table>
  </form>
  <!--end of upload interface-->
  <?php
}

//require a user to sign in
else
   echo "You must <a href='index.php'>sign in</a> to view this content.";

?>
</div>

</body>
</html>

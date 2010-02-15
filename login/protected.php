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

if(isset($_SESSION['loggedInAs'])) 
{
  ?>
  Welcome to protected area, <i><?php echo $_SESSION['loggedInAs']; ?></i><br>
  <?php if($_SESSION['loggedInAs'] == "admin") echo "<a href='createLogin.php'>add users</a>"; ?>
  <a href="signout.php">signout</a>
  <?php
}

else
   echo "You must <a href='index.php'>sign in</a> to view this content.";

?>
</div>

</body>
</html>

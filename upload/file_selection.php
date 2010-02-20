<html>
<body>


<!-- This is the page the user sees when uploading a file. -->
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

</body>
</html>

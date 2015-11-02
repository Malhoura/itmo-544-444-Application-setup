<?php session_start(); ?>
<html>
<head>
<title>Hello PHP</title>
</head>
<body>

<form enctype="multipart/form-data" name="htmlform" method="POST" action="result.php">
<table width="450px">
</tr>
 
<tr>
 <td valign="top"">
  <label for="username">User Name *</label>
 </td>
 <td valign="top">
  <input  type="text" name="username" maxlength="50" size="30">
 </td>
</tr>
<tr>
 <td valign="top">
  <label for="email">Email Address *</label>
 </td>
 <td valign="top">
  <input  type="text" name="useremail" maxlength="80" size="30">
 </td>
 
</tr>
<tr>
 <td valign="top">
  <label for="telephone">Telephone Number</label>
 </td>
 <td valign="top">
  <input  type="text" name="telephone" maxlength="30" size="30">
 </td>
</tr>
<tr>
 
</tr>
<tr>
 <td colspan="2" style="text-align:center">
  <input type="submit" value="Submit">   
 </td>
</tr>
</table>

</form>

<form "multipart/form-data" name="htmlform" method="POST" action="gallary.php">
Input Email Of User for Gallary to browse 
<tr>
 <td valign="top">
  <label for="email">Email Address *</label>
 </td>
 <td valign="top">
  <input  type="text" name="email" maxlength="80" size="30">
 </td>

</tr> 
<tr>
 <td colspan="2" style="text-align:center">
  <input type="submit" value="Load Gallary">
 </td>
</tr>

</body>
</html>

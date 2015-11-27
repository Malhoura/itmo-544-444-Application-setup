<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link class="cssdeck" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.1/css/bootstrap-responsive.min.css" class="cssdeck">
        <title>Form</title>

</head>
<body>
 <div id="form" align ='center' >
                <p align ='center'><span class="error">* required field.</span></p>
                <form enctype="multipart/form-data" action="result.php" method="POST">


                <!-- MAX_FILE_SIZE must precede the file input field -->
                  <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
                 <!-- Name of input element determines name in $_FILES array -->
                <label>Send this file:</label>
                 <input name="userfile" class="input-xlarge" type="file"/><br />


                    <label>User Name</label>
             <input type="text" class="input-xlarge" name="username" value="name">
                    <br>
                <label>Email Address</label>
           <input type="text" class="input-xlarge" name="useremail" value="foo@foo.com">
                    <br>
                <label>Telephone Number</label>
         <input type="phone" class="input-xlarge" name="telephone" value="03128885475">
                    <br>

                        <input type="submit" name="submit" value="Submit">
                </form>
            </div>


<hr>
                <form enctype="multipart/form-data" action="gallery.php" method="GET">

                Enter Email of user for gallery to browse: <input type="email" name="useremail" value="foo@foo.com">
                <input type="submit" value="Load Gallery" />
                </form>
                <hr>

</body>
</html>

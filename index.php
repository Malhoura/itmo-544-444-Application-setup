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
             <input type="text" class="input-xlarge" name="username" value="<?php echo $username; ?>">
                    <br>
                <label>Email Address</label>
           <input type="text" class="input-xlarge" name="useremail" value="<?php echo $useremail; ?>">
                    <br>
                <label>Telephone Number</label>
         <input type="number" class="input-xlarge" name="telephone" value="<?php echo $telephone; ?>">
                    <br>

                        <input type="submit" name="submit" value="Submit">
                </form>
            </div>


<hr>
                <form enctype="multipart/form-data" action="gallery.php" method="POST">

                Enter Email of user for gallery to browse: <input type="email" name="useremail">
                <input type="submit" value="Load Gallery" />
                </form>
                <hr>

</body>
</html>

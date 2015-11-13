<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <link class="cssdeck" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.1/css/bootstrap-responsive.min.css" class="cssdeck">
        <title>Form</title>
        <style>
            .error {color: #FF0000;}

        </style>
    </head>
    <body>
        <div class="btn-group" role="group" aria-label="...">
            <button type="button" class="btn btn-default"><a href="index.php">Home</a></button>
        </div>

        <?php
        // defining variables 
        $wrongusername = "";
        $wronguseremail = "";
	$wrongtelephone = "";
        $username = "";
        $useremail = "";
	$telephone = "";

            
	//form validation
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            //if users don't enter their firstname
            if (empty($_POST["username"])) {
                $wrongusername = "User Name is required";
                //validate the user input
            } else {
                $username = $_POST["username"];
                if (!preg_match("/^[a-zA-Z ]*$/", $username)) {
                    $wrongusername = "Only letters and white space allowed";
                }
            }


            //if users don't enter their emailAddress
            if (empty($_POST["useremail"])) {

                $wronguseremail = "Email is required";
            }
           //validate the user input
            else {
                $useremail = $_POST["emailAddress"];
                }
	
if (empty($_POST["telephone"])) {

                $wrongtelephone = "Telephone is required";
            }
            else {
                $telephone = $_POST["telephone"];
                }
       
   }

	
      
?>
        
	<?php
        if (($_SERVER["REQUEST_METHOD"] == "GET") || ($_SERVER["REQUEST_METHOD"] == "POST" && (empty($username) || empty($useremail) || empty($telephone)))) {
            ?>
            <div id="form" align ='center' >
                <p align ='center'><span class="error">* required field.</span></p>
                <form  method="post" action="result.php">
                    <label>User Name</label>
                    <input type="text" class="input-xlarge" name="username" value="<?php echo $username; ?>">
                    <span class="error"><br> <?php echo $wrongusername; ?></span>
                    <br>
                    <label>Email Address</label>
                    <input type="text" class="input-xlarge" name="useremail" value="<?php echo $useremail; ?>">
                    <span class="error"><br> <?php echo $wronguseremail; ?></span>
                    <br>
		<label>Telephone Number</label>
                    <input type="number" class="input-xlarge" name="telephone" value="<?php echo $telephone; ?>">
                    <span class="error"><br> <?php echo $wrongtelephone; ?></span>
                    <br>

                        <input type="submit" name="submit" value="Submit">
                </form>
            </div>

            <hr>
            <div id='output' align='center'>
                <?php
            }
                    //if there's no errors
            elseif (!$wrongusername && !$wronguseremail && !$wrongtelephone) {
                //if the required fields are not empty
                if (!empty($_POST["username"]) && !empty($_POST["useremail"]) && !empty($_POST["telephone"])) {
                    echo "<h1 style=\"font-style: italic;\">Your Information:</h1>";
                    echo"<b><p>User Name: </P></b>";
                    echo $username;
                    echo "<br>";
                    echo"<b><p>Email Address: </P></b>";
                    echo $useremail;
                    echo "<br>";

                    print "</p></div>";

                    echo"<br>";
                    echo"<br>";
                    echo"<br>";
                    echo"Submitted: ";
                    echo '<br>';
                    echo '<br>';
                }
            }
            ?>
        </div>


    </body>
</html>

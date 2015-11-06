<!DOCTYPE HTML> 
<html>
    <head>
        <link class="cssdeck" rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.1/css/bootstrap-responsive.min.css" class="cssdeck">
        <title>Form</title>
        <style>
            body {
                background-image: url("images/img.jpg");
            }
            .error {color: #FF0000;}

        </style>
    </head>
    <body> 
        <div class="btn-group" role="group" aria-label="...">
            <button type="button" class="btn btn-default"><a href="index.php">Home</a></button>
            <button type="button" class="btn btn-default"><a href="form.php">Form</a></button>
        </div>

        <?php
        include_once './functions.php';
        
        // defining variables 
        $wrongFirstName = "";
        $wrongLastName = "";
        $wrongEmailAddress = "";
        $firstName = "";
        $lastName = "";
        $emailAddress = "";
        $gender = "";
        $comment = "";
        

            //form validation
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            //if users don't enter their firstname
            if (empty($_POST["firstName"])) {
                $wrongFirstName = "First Name is required";
                //validate the user input
            } else {
                $firstName = validate($_POST["firstName"]);
                // check if name only contains letters and whitespace
                // [a-zA-Z ] characters in the range a-z
                // ^ start of line
                // $ end of string
                if (!preg_match("/^[a-zA-Z ]*$/", $firstName)) {
                    $wrongFirstName = "Only letters and white space allowed";
                }
            }

            //if users don't enter their lasstname
            if (empty($_POST["lastName"])) {
                $wrongLastName = "Last Name is required";
            }
            //validate the user input
            else {
                $lastName = validate($_POST["lastName"]);
                // check if name only contains letters and whitespace
                // [a-zA-Z ] characters in the range a-z
                // ^ start of line
                // $ end of string
                if (!preg_match("/^[a-zA-Z ]*$/", $lastName)) {
                    $wrongLastName = "Only letters and white space allowed";
                }
            }

            //if users don't enter their emailAddress
            if (empty($_POST["emailAddress"])) {

                $wrongEmailAddress = "Email is required";
            }
            //validate the user input
            else {
                $emailAddress = validate($_POST["emailAddress"]);
                // check if the emailaddress is in valid format
                if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
                    $wrongEmailAddress = "Invalid email format";
                }
            }


            if (!empty($_POST["comment"])) {
                $comment = validate($_POST["comment"]);
            }

            if (!empty($_POST["gender"])) {
                $gender = $_POST["gender"];
            }
        }
        ?>
        <?php
        if (($_SERVER["REQUEST_METHOD"] == "GET") || ($_SERVER["REQUEST_METHOD"] == "POST" && (empty($firstName) || empty($lastName) || empty($emailAddress)))) {
            ?>
            <div id="form" align ='center' >
                <h1><i>ITMD 462</i></h1>
                <p align ='center'><span class="error">* required field.</span></p>
                <form  method="post" action="result.php">
                    <label>First Name</label>
                    <input type="text" class="input-xlarge" name="firstName" value="<?php echo $firstName; ?>">
                    <span class="error"><br> <?php echo $wrongFirstName; ?></span>
                    <br>
                    <label>Last Name</label>
                    <input type="text"  class="input-xlarge" name="lastName" value="<?php echo $lastName; ?>">
                    <span class="error"><br> <?php echo $wrongLastName; ?></span>
                    <br>
                    <label>Email</label>
                    <input type="text" class="input-xlarge" name="emailAddress" value="<?php echo $emailAddress; ?>">
                    <span class="error"><br> <?php echo $wrongEmailAddress; ?></span>
                    <br>
                    <label>Comments</label>
                    <textarea name="comment" rows="4" cols="30" class="input-xlarge"><?php echo $comment; ?></textarea>
                    <br>
                    Gender:<br>
                    <label><input type="radio" name="gender" <?php if (isset($gender) && $gender == "female") echo "checked"; ?> value="female">Female</label>
                    <label><input type="radio" name="gender" <?php if (isset($gender) && $gender == "male") echo "checked"; ?> value="male">male</label>

                    <br>

                    <p>What's your favorite browser:<br>
                        <Input type = "Checkbox" Name ="checkboxes[]" value="Google Chrome">Google Chrome
                        <Input type = "Checkbox" Name ="checkboxes[]" value="Firefox">Firefox
                        <Input type = "Checkbox" Name ="checkboxes[]" value="Safari">Safari
                        <br>

                        <input type="submit" name="submit" value="Submit">
                </form>
            </div>

            <hr>
            <div id='output' align='center'>
                <?php
            }
                    //if there's no errors
            elseif (!$wrongFirstName && !$wrongLastName && !$wrongEmailAddress) {
                //if the required fields are not empty
                if (!empty($_POST["firstName"]) && !empty($_POST["lastName"]) && !empty($_POST["emailAddress"])) {
                    echo "<h1 style=\"font-style: italic;\">Your Information:</h1>";
                    echo"<b><p>First Name: </P></b>";
                    echo $firstName;
                    echo "<br>";
                    echo"<b><p>Last Name: </P></b>";
                    echo $lastName;
                    echo "<br>";
                    echo"<b><p>Email Address: </P></b>";
                    echo $emailAddress;
                    echo "<br>";

                    if (!empty($_POST["comment"])) {
                        echo"<b><p>Comments: </P></b>";
                        echo $comment;
                    }

                    if (!empty($_POST["gender"])) {
                        echo"<b><p>Gender:</P></b>";
                        echo $gender;
                    }
                    echo "<br>";

                    if (isset($_POST['checkboxes']) && is_array($_POST['checkboxes'])) {
                        print "<div>";
                        print "<b><p>Your Favorite Browser:</p></b>";
                        print "<p>";
                        foreach ($_POST['checkboxes'] as $value) {
                            print "$value<br>";
                        }
                    }
                    print "</p></div>";

                    echo"<br>";
                    echo"<br>";
                    echo"<br>";
                    echo"Submitted: ";
                    echo '<br>';
                    echo '<br>';
                    echo timeStampSubmit();
                }
            }
            ?>
        </div>


    </body>
</html>

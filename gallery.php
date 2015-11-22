<html>
<head><title>Gallery</title>
</head>
<body>
<div>
<?php
session_start();

$useremail = $_POST["useremail"];
echo $useremail;

require 'vendor/autoload.php';

$rds = new Aws\Rds\RdsClient([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);

$result = $rds->describeDBInstances(array(
    'DBInstanceIdentifier' => 'malhorua-mp1'
   
));

$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
    echo "============\n". $endpoint . "================";
$link = mysqli_connect($endpoint,"malhoura","malhoura","malhouradb");
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
else {
echo "Success";
}
$link->real_query("SELECT * FROM User WHERE useremail = '$useremail'");
echo "Result set order...\n";

if ($result = $link->use_result()) {
            while ($row = $result->fetch_assoc()) {
                echo "<img src =\" " . $row['raws3url'] . "\" height='200' width='200' />";
            }
            $result->close();
        }
session_destroy();

?>

</div>
</body>

</html>

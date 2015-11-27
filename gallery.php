<html>
<head><title>Gallery</title>
</head>
<body>
<div>
<?php
session_start();
require 'vendor/autoload.php';
use Aws\Rds\RdsClient;
$useremail = $_GET["useremail"];
echo $useremail;


$client = RdsClient::factory(array(
    'version' => 'latest',
    'region'  => 'us-east-1'
));

$result = $client->describeDBInstances(array(
    'DBInstanceIdentifier' => 'malhoura-mp1'
   
));

$endpoint= "";
foreach ($result["DBInstances"] as $dbinstances) {
$dbinstanceidentifier = $dbinstance["DBInstanceIdentifier"];
if ($dbinstanceidentifier == "malhoura-mp1"){
$endpoint = $dbinstance["Endpoint"]["Address"];
}
}

$link = mysqli_connect($endpoint,"malhoura","malhoura") or die("Error " . mysqli_error($link));
mysqli_select_db($link, "malhouradb");
$sql = "SELECT * FROM User WHERE useremail='$useremail'";
$result = $link->query($sql);


            while ($row = $result->fetch_assoc()) {
                echo "<img src =\" " . $row['raws3url'] . "\" height='200' width='200' />";
            }
            $link->close();

?>

</div>
</body>

</html>

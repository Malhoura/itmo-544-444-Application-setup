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
$link = mysqli_connect($endpoint,"malhoura","malhoura","users");
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
else {
echo "Success";
}
$link->real_query("SELECT * FROM User WHERE useremail = '$useremail'");
$res = $link->use_result();
echo "Result set order...\n";
while ($row = $res->fetch_assoc()) {
    echo "<img src =\" " . $row['raws3url'] . "\" /><img src =\"" .$row['finisheds3url'] . "\"/>";
echo $row['id'] . "Email: " . $row['useremail'];
}
$link->close();
?>

</div>
</body>

</html>

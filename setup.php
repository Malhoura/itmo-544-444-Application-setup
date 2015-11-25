<?php
// Start the session
require 'vendor/autoload.php';
$rds = new Aws\Rds\RdsClient([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);

// Create a table 
$result = $rds->describeDBInstances(array(
    'DBInstanceIdentifier' => 'malhoura-mp1'
));

$rds->waitUntil('DBInstanceAvailable',['DBInstanceIdentifier' => 'malhoura-mp1',]);

$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
print "============\n". $endpoint . "================\n";
$link = mysqli_connect($endpoint,"malhoura","malhoura","malhouradb") or die("Error " . mysqli_error($link)); 


$sql = "CREATE TABLE User IF NOT EXISTS(
ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(20),
useremail VARCHAR(20),
telephone VARCHAR(20),
raws3url VARCHAR(256),
finisheds3url VARCHAR(256),
filename VARCHAR(256),
state TINYINT(3),
datetime VARCHAR(256) 
)";
$link->query($sql);


?>

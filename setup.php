<?php
include 'vendor/autoload.php';

use Aws\Rds\RdsClient;
$client = RdsClient::factory(array(
    'version' => 'latest',
    'region'  => 'us-east-1'
));

// Create a table 
$result = $client->describeDBInstances(array(
    'DBInstanceIdentifier' => 'malhoura-mp1'
));

$client->waitUntil('DBInstanceAvailable',['DBInstanceIdentifier' => 'malhoura-mp1',]);

$endpoint= "";
foreach ($result["DBInstances"] as $dbinstances) {
$dbinstanceidentifier = $dbinstance["DBInstanceIdentifier"];
if ($dbinstanceidentifier == "malhoura-mp1"){
$endpoint = $dbinstance["Endpoint"]["Address"];
}
}

$link = mysqli_connect($endpoint,"malhoura","malhoura") or die("Error " . mysqli_error($link)); 
$db = "CREATE SCHEMA `malhouradb`;";
$link->query($db);

mysqli_select_db($link, "malhouradb");

$sql = "CREATE TABLE User IF NOT EXISTS(
ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(20),
useremail VARCHAR(20),
sns VARCHAR(20),
telephone VARCHAR(20),
raws3url VARCHAR(256),
finisheds3url VARCHAR(256),
filename VARCHAR(256),
state TINYINT(3),
datetime DATETIME 
)";

$link->query($sql);

$link->close();
?>

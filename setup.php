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

ini-set('error_reporting', E_ALL);
echo "Here is the result: " . mysql_error($link);    

$sql = "DROP TABLE IF EXISTS User";
if(!mysqli_query($link, $sql)) {
   echo "Error : " . mysqli_error($link);
} 

$link->query("CREATE TABLE User  
(
ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(20),
useremail VARCHAR(20),
telephone VARCHAR(20),
raws3url VARCHAR(256),
finisheds3url VARCHAR(256),
filename VARCHAR(256),
state TINYINT(3),
datetime TIMESTAMP 
)");

shell-exec("chmod 600 setup.php");

?>

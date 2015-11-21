<?php
// Start the session
require '/var/www/html/vendor/autoload.php';
$rds = new Aws\Rds\RdsClient([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);

// Create a table 
$result = $rds->describeDBInstances([
    'DBInstanceIdentifier' => 'malhoura-mp1',
]);
$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
print "============\n". $endpoint . "================\n";
$link = mysqli_connect($endpoint,"malhoura","malhoura","users") or die("Error " . mysqli_error($link)); 
echo "Here is the result: " . $link;



$sql = 'CREATE TABLE IF NOT EXISTS Data  
(
ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(20),
useremail VARCHAR(20),
telephone VARCHAR(20),
raws3url VARCHAR(256),
finisheds3url VARCHAR(256),
filename VARCHAR(256),
state TINYINT(3),
datetime VARCHAR 

)';
$link->query($sql);
shell-exec("chmod 600 setup.php");

?>

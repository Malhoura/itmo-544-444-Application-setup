<?php
require '/vendor/autoload.php';

$rds = new Aws\Rds\RdsClient([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);
$result = $rds->describeDBInstances([
    'DBInstanceIdentifier' => 'malhoura-mp1'
]);
$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
//echo "begin database";
$link = mysqli_connect($endpoint,"malhoura","malhoura","malhouradb",3306) or die("Error " . mysqli_error($link));
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
/* Prepared statement, stage 1: prepare */
if (!($stmt = $link->prepare("INSERT INTO User(username,useremail,telephone,filename,raws3url,finisheds3url,state,datetime) VALUES (?,?,?,?,?,?,?,?)"))){
 echo "Prepare failed: (" . $link->errno . ") " . $link->error;
}
$uname = "mazen"; 
$email = "malhoura@hawk.iit.edu";
$phone = "telephone";
$s3url = "url";
$filename = "filename";
$fs3url = "fs3url";
$state =1;
$date = "11-11-2012";

$sns = new Aws\Sns\SnsClient([
'version' => 'latest',
'region' => 'us-east-1'
]);



$stmt->bind_param("ssssssis",$uname,$email,$phone,$filename,$s3url,$fs3url,$status,$date,$subs);
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
?>

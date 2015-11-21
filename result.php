<?php
echo "Hello World1";
session_start();

$username = $_POST['username'];
$useremail= $_POST['useremail'];
$telephone = $_POST['telephone'];
$allowed = array('gif', 'png', 'jpg');
$filename = $_FILES['userfile']['name'];

date_default_timezone_set('America/Chicago');

$uploaddir = '/tmp/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
print '<pre>';

if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
  echo "File is valid, and was successfully uploaded.\n";
}
else {
    echo "Possible file upload attack!\n";
}

echo 'Here is some more debugging info:';
print_r($_FILES);
print "</pre>";
  
require 'vendor/autoload.php';

#use Aws\S3\S3Client;
$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);

$bucket = uniqid("malhoura-php",false);

# AWS PHP SDK version 3 create bucket
$result = $s3->createBucket([
    'ACL' => 'public-read',
    'Bucket' => $bucket
]);

$s3->waitUntil('BucketExists',[
	'Bucket' => $bucket
]);

#print_r($result);

$result = $s3->putObject([
    'ACL' => 'public-read',
    'Bucket' => $bucket,
   'Key' => $bucket,
   'SourceFile' => $uploadfile 
]);

$url = $result['ObjectURL'];
echo $url;


$rds = new Aws\Rds\RdsClient([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);

$result = $rds->describeDBInstances(array(
    'DBInstanceIdentifier' => 'malhoura-mp1'
   
));

$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
    echo "============\n". $endpoint . "================";
$link = mysqli_connect($endpoint,"malhoura","malhoura","malhouradb",3306) or die("Error" . mysql_error($link));

if (mysqli_connect_errno()) { 
    printf("Connect failed: %s\n", mysqli_connect_error()); 
    exit(); 
} 
else { 
echo "Success"; 
} 

if (!($stmt = $link->prepare("INSERT INTO User (username,useremail,telephone,raws3url,finisheds3url,filename,state,datetime) VALUES (?,?,?,?,?,?,?,?)"))) {
    echo "Prepare failed: (" . $link->errno . ") " . $link->error;
}

$username=$_POST['username'];
$useremail = $_POST['useremail'];
$_SESSION["useremail"]=$useremail;
$telephone = $_POST['telephone'];
$raws3url = $url; 
$filename = basename($_FILES['userfile']['name']);
$finisheds3url = "none";
$state=0;
$datetime = date("d M Y - h:i:s A");

$stmt->bind_param("ssssssis",$username,$useremail,$telephone,$raws3url,$finisheds3url,$filename,$state,$datetime);
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}

printf("%d Row inserted.\n", $stmt->affected_rows);
$stmt->close();

$link->real_query("SELECT * FROM User");
$res = $link->use_result();

echo "Result set order...\n";

while ($row = $res->fetch_assoc()) {
    echo $row['id'] . " " . $row['username'] . " " . $row['useremail']. " " . $row['telephone'];
}
$link->close();
header("Location: gallery.php");
?> 

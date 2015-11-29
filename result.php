<?php
session_start();
require 'vendor/autoload.php';
use Aws\S3\S3Client;
$useremail = $_POST["useremail"]; 
$telephone = $_POST["telephone"];
$userfile = $_FILES["userfile"];
$username = $_POST["username"];


$uploaddir = "/var/www/html/uploads/".$userfile["name"];
$uploadthumb = "/var/www/html/uploads/thumb_".$userfile["name"];

move_uploaded_file($userfile["tmp_name"],$uploaddir);

var_dump($userfile);
$imagick = new \Imagick(realpath($uploaddir));
$imagick -> thumbnailImage(100, 100, true, true);
$imagick -> writeImage($uploadthumb);


  
$client = S3Client::factory(array(
	'version' => 'latest',
	'region' => 'us-east-1',
));

$bucket = uniqid("malhoura-php",true);

# AWS PHP SDK version 3 create bucket
$result = $client->createBucket(array(
    'ACL' => 'public-read',
    'Bucket' => $bucket
));

$client->waitUntil('BucketExists',[
	'Bucket' => $bucket
]);

$result = $client->putObject(array(
    'ACL' => 'public-read',
    'Bucket' => $bucket,
   'Key' => $userfile["name"],
   'SourceFile' => $uploaddir 
));

$url = $result["ObjectURL"];
echo $url;

$result = $client->putObject(array(
    'ACL' => 'public-read',
    'Bucket' => $bucket,
   'Key' => $userfile["name"],
   'SourceFile' => $uploadthumb
));

$url_thumb = $result["ObjectURL"];
echo $url_thumb;

use Aws\Rds\RdsClient;
$client = RdsClient::factory(array(
    'version' => 'latest',
    'region'  => 'us-east-1'
));

$result = $client->describeDBInstances(array(
    'DBInstanceIdentifier' => 'malhoura-mp1'
   
));

$endpoint= "";
foreach ($result["DBInstances"] as $dbinstances) {
$dbinstanceidentifier = $dbinstances["DBInstanceIdentifier"];
if ($dbinstanceidentifier == "malhoura-mp1"){
$endpoint = $dbinstances["Endpoint"]["Address"];
}
}

$link = mysqli_connect($endpoint,"malhoura","malhoura") or die("Error " . mysqli_error($link));

$sql = "INSERT INTO User (username,useremail,telephone,raws3url,finisheds3url,filename,state,datetime) VALUES ('".$username."','".$useremail."','".$telephone>"','".$url."','".$url_thumb."','".$userfile["name"]."','2','NOW()')";

$link->query($sql);
$link->close();


use Aws\Sns\SnsClient;
$sns = SnsClient::factory(array(
	'version' => 'latest',
	'region' => 'us-east-1',
));

$result = $sns->createTopic([
	'Name' => 'mp2',
]);

$snsarn = $result['TopicArn'];

$result = $sns->subscribe([
	'Endpoint' => $telephone,
	'Protocol' => 'sms',
	'TopicArn' => $snsarn,
]); 

$result = $sns->publish([
	'Message' => 'Image Uploaded',
	'Subject' => 'Image Upload',
	'TopicArn' => $snsarn
]);

	
$_SESSION["uploader"] = true;

header("Location:gallery.php");
exit;
?> 

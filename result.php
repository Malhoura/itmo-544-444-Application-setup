<?php
session_start();
require 'vendor/autoload.php';
$useremail = $_POST["useremail"]; 
$telephone = $_POST["telephone"];
$userfile = $_FILES["userfile"];
$username = $_POST["username"];


$uploaddir = '/tmp/';
$uploadthumb = '/tmp/thump/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
$uploadthumb = $uploadthumb . basename($FILES['userfile']['name'];
move_uploaded_file($userfile["tmp_name"],$uploadfile);

var_dump($userfile);
$imagick = new \Imagick(realpath($uploadfile));
$imagick -> thumbnailImage(100, 100, true, true);
$imagick -> writeImage($uploadthumb);


if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
  echo "File is valid, and was successfully uploaded.\n";
}
else {
    echo "Possible file upload attack!\n";
}

echo 'Here is some more debugging info:';
print_r($_FILES);
print "</pre>";
  
use Aws\S3\S3Client;
$client = S3Client::factory(array(
	'version' => 'latest',
	'region' => 'us-east-1',
));

$bucket = uniqid("malhoura-php",false);

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
   'Key' => $userfile["name'],
   'SourceFile' => $uploadfile 
));

$url = $result['ObjectURL'];
echo $url;

$result = $client->putObject(array(
    'ACL' => 'public-read',
    'Bucket' => $bucket,
   'Key' => $userfile["name'],
   'SourceFile' => $uploadthumb
));

$url_thumb = $result['ObjectURL'];
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
$dbinstanceidentifier = $dbinstance["DBInstanceIdentifier"];
if ($dbinstanceidentifier == "malhoura-mp1"){
$endpoint = $dbinstance["Endpoint"]["Address"];
}
}

$link = mysqli_connect($endpoint,"malhoura","malhoura") or die("Error " . mysqli_error($link));
$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
    echo "============\n". $endpoint . "================";

$sql = "INSERT INTO User (username,useremail,telephone,raws3url,finisheds3url,filename,state,datetime) VALUES ($username,$useremail,?,?,?,?,?,?)";



if (!($stmt = $link->prepare("INSERT INTO User (username,useremail,telephone,raws3url,finisheds3url,filename,state,datetime) VALUES (?,?,?,?,?,?,?,?)"))) {
    echo "Prepare failed: (" . $link->errno . ") " . $link->error;
}

$username=$_POST['username'];
$useremail = $_POST['useremail'];
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

use Aws\SnsClient;
$sns = SnsClient::factory(array(
	'version' => 'latest',
	'region' => 'us-east-1',
]);

$result = $sns->createTopic([
	'Name => 'mp2',
]);

$snsarn = $result['TopicArn'];

$result = $sns->subscribe([
	'Endpoint' => $telephone,
	'Protocol' => 'sms',
	'TopicArn' => $snsarn,
]); 

$result = $sns->publish([
	'TopicArn' => $snsarn,
	'Message' => 'Image Uploaded',
	'Subject' => 'Image Upload',
]);

	
$_SESSION["uploader"] = true;

header("Location:gallery.php");
exit;
?> 

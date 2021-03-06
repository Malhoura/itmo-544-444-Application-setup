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

$image = @file_get_contents($uploaddir);
if($image) {
    $im = new Imagick();
    echo $im;
    $im->readImageBlob($image);
    $im->setImageFormat("png24");
    $geo=$im->getImageGeometry();
    $width=$geo['width'];
    $height=$geo['height'];
        echo $height. $width;
    if($width > $height)
    {
        $scale = ($width > 200) ? 200/$width : 1;
    }
    else
    {
        $scale = ($height > 200) ? 200/$height : 1;
    }
    $newWidth = $scale*$width;
    $newHeight = $scale*$height;
 echo $newWidth.$newHeight;
    $im->setImageCompressionQuality(85);
    $im->resizeImage($newWidth,$newHeight,Imagick::FILTER_LANCZOS,1.1);
    $im->writeImage($uploadthumb);
}
  
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

$result = $client->putBucketLifecycleConfiguration(array(
        'Bucket' => $bucket,
        'LifecycleConfiguration' => array(
        'Rules' => array(
                        array(
                                'Expiration' => array('Days' => 1),
                                'Prefix' => "",
                                'Status' => 'Enabled'
                        )
        ))
));

$result = $client->putObject(array(
    'ACL' => 'public-read',
    'Bucket' => $bucket,
   'Key' => $bucket,
   'SourceFile' => $uploaddir 
));

$url = $result["ObjectURL"];
echo $url;

$bucket2 = uniqid("malhoura-thumb",true);
//creating a bucket
$result2 = $client->createBucket([
    'ACL' => 'public-read',
    'Bucket' => $bucket2
]);

//wait until bucket exists
$client->waitUntil('BucketExists',[
        'Bucket' => $bucket2
]);

$result2 = $client->putBucketLifecycleConfiguration(array(
        'Bucket' => $bucket2,
        'LifecycleConfiguration' => array(
        'Rules' => array(
                        array(
                                'Expiration' => array('Days' => 1),
                                'Prefix' => "",
                                'Status' => 'Enabled'
                        )
        ))
));

$result2 = $client->putObject(array(
    'ACL' => 'public-read',
    'Bucket' => $bucket2,
   'Key' => $bucket2,
   'SourceFile' => $uploadthumb
));

$url_thumb = $result2["ObjectURL"];
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

$sql = "INSERT INTO User (username, useremail, telephone, raws3url, finisheds3url, filename, state, datetime) VALUES('".$username."','".$useremail."','".$telephone."','".$url."','".$url_thumb."','".$userfile["name"]."','2','NOW()')";

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

$topicAttributes = $sns->setTopicAttributes([
	'AttributeName' => 'DisplayName',
	'AttributeValue' => 'mp2-display',
	'TopicArn' => $result['TopicArn']
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

header("Location:gallery.php?useremail=".$useremail);
exit;
?> 

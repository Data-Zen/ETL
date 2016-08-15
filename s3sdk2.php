<?php
error_reporting(E_ERROR | E_PARSE);
echo "\n\n*******Running s3sdk.php*************\n\n";

include 'credentials/PBBCredentials.php';


$S3sourceDir = 'files';

require '../aws/aws-autoloader.php';

$s3 = new Aws\S3\S3Client([
    'version'     => 'latest',
    'region'      => 'us-west-2',
    'credentials' => [
        'key'    => $AWS_ACCESS_KEY_ID,
        'secret' => $AWS_SECRET_ACCESS_KEY,
    ],
]);

$bucket = $S3bucketName;


//$s3->uploadDirectory($S3sourceDir, $S3bucketName);

$key=$mysqltbl.'.csv';
$result = $s3->putObject(array(
    'Bucket'     => $S3bucketName,
    'Key'        => $key,
    'SourceFile' => $OutputFilePath,
));

$s3->waitUntil('ObjectExists', array(
    'Bucket' => $S3bucketName,
    'Key'    => $key
));
echo "Uploaded! $key\n";                    
// Use the high-level iterators (returns ALL of your objects).
//$objects = $s3->getIterator('ListObjects', array('Bucket' => $bucket));

//echo "Objects!\n";
//foreach ($objects as $object) {
 //   echo $object['Key'] . "\n";
//}

/*
// Use the plain API (returns ONLY up to 1000 of your objects).
$result = $s3->listObjects(array('Bucket' => $bucket));

echo "Keys retrieved!\n";
foreach ($result['Contents'] as $object) {
    echo $object['Key'] . "\n";
}
*/
/*
echo "Inside BCS3 \n";
//date_default_timezone_set('America/Los_Angeles');//or change to whatever timezone you want
$date = filemtime(".");
$dateFormat = "Y/m/d h:i:s";
$rundate = date($dateFormat, time());
//$keyTimeStamp = "mirrorconfig.timestamp";
$accessKey = $S3accessKey;
$secretKey = $S3secretKey;
$bucketName = $S3bucketName;
$sourceDir = $S3sourceDir;
echo "\n\nbucketName: $bucketName \n\n";
//$cacheDuration = 3600 * 24 * 30;
$fileAcl = S3::ACL_PUBLIC_READ;
$s3 = new S3($accessKey, $secretKey);
//$http_headers = array('Cache-Control' => 'max-age=' . $cacheDuration, 'Expires' => date('D, j M Y H:i:s \G\M\T', time() + $cacheDuration)); $meta_headers = array();
if (($lastrunObject = $s3::GetObject($bucketName, $keyTimeStamp)) !== false) {
    $lastrun = $lastrunObject->body;
} else {
    if ($result = $s3::PutObject($date, $bucketName, $keyTimeStamp, $fileAcl) !== false) {
        $lastrun = $date;
    }
}
$dir=$sourceDir;  // need this so that we have $souceDir when we recurse.
$comparedate=$lastrun; // set the date to start the comparison from to the last time it was run (or the directories last modified date).
$max_modified=$lastrun;
$filecount = 0; // pass a counter that tracks the total number of files updated.
// recurse the directory tree and upload new files.
directory_tree($dir,$comparedate,$max_modified,$sourceDir,$bucketName, $s3,$fileAcl,$meta_headers,$http_headers,$filecount);
// if there were files uploaded, update the timestamp of the newest file updated, for the next run to start with.
// also log that the run completed.
if ($filecount > 0) {
    $result = $s3::PutObject($max_modified, $bucketName, $keyTimeStamp, $fileAcl);
    echo "Processed: ".$filecount." file(s). Updated Max Modified Time to: ".$max_modified." at ".$rundate." UTC.\n"; }
// ref: http://php.net/manual/en/function.filemtime.php
function directory_tree($address,$comparedate,&$max_modified,$sourceDir,$bucketName, $s3,$fileAcl=S3::ACL_PUBLIC_READ,$meta_headers=null,$http_headers=null,&$filecount=0) { 
    @$dir = opendir($address); 
    if(!$dir){ return 0; } 
    while($entry = readdir($dir)){ 
            if(is_dir("$address/$entry") && ($entry != ".." && $entry != ".")){                              
                    directory_tree("$address/$entry",$comparedate,$max_modified,$sourceDir,$bucketName, $s3,$fileAcl,$meta_headers,$http_headers,$filecount); 
                }  else   { 
            if($entry != ".." && $entry != ".") { 
                $fulldir=$address.'/'.$entry; 
                            $last_modified = filemtime($fulldir); 
                            if($comparedate < $last_modified)  { 
                    if ($last_modified > $max_modified) {
                        $max_modified = $last_modified;
                    }
                    $file = preg_replace('!'.preg_quote($sourceDir."/").'!','',$fulldir,1); 
                    echo "Source directory: ".$sourceDir." Source file: ".$fulldir." Destination: ".$file." Modified: ".$last_modified." Last Run: ".$comparedate."... ";   
                            if ($s3->putObject($s3->inputFile($fulldir), $bucketName, $file, $fileAcl, $meta_headers, $http_headers)) {
                                    echo "OK\n";
                        $filecount++;
                            } else {
                                    echo "ERROR\n";
                            }

                } 
                    }
                } 
        }
}
*/
?>
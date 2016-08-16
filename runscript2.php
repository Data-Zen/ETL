<?php


include 'credentials/PBBCredentials.php';
if (isset($argv[1])) {
    $offset = $argv[1];
} else {
    echo "\n\n Offset Needs to passed in.\n\n";
    die;
}
if (isset($argv[2])) {
    $ChunkSize = $argv[2];
} else {
    echo "\n\n ChunkSize Needs to passed in.\n\n";
    die;
}

if (isset($argv[3])) {
    $mysqltbl = $argv[3];
} else {
    echo "\n\n TableName Needs to passed in.\n\n";
    die;
}

$start_timer_11 = microtime(true);
echo "\nmysqltbl:$mysqltbl\n";

include('GetMySQLData2.php');
echo "\n\nINSIDE RUNSCRIPT2: $mysqlaffectedrows MYSQL ROWS AFFECTED\n\n";
if ($mysqlaffectedrows >=200) {

$affectedrows=222;    
}
else
{
    $affectedrows=$mysqlaffectedrows;    
}

$end11 = round((microtime(true) - $start_timer_11), 2);
echo "\n=====================elapsed time for Mysql: $end11 seconds \n";
$start_timer_11 = microtime(true);
//   $mysqltbl_filename=str_ireplace ('.', '_', $mysqltbl_filename);
include('s3sdk2.php');
$end11 = round((microtime(true) - $start_timer_11), 2);
echo "\n=====================elapsed time for S3: $end11 seconds \n";
$start_timer_11 = microtime(true);

include('RedshiftQueries2.php');
$end11 = round((microtime(true) - $start_timer_11), 2);
echo "\n=====================elapsed time for Redshift push: $end11 seconds \n";

echo "\n\nINSIDE RUNSCRIPT2: $mysqlaffectedrows MYSQL ROWS AFFECTED\n\n";
unlink($OutputFilePath);

        $end11 = round((microtime(true) - $start_timer_11),2);
        echo "\n=====================elapsed time for Loop: $end11 seconds \n";

exit($affectedrows);
?>
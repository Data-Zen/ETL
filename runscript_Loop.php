<?php

include 'credentials/PBBCredentials.php';


/* Get the biggest id from Redshift  */



$connect = pg_connect($PBBModifyCredentials);


$maxRSdate = '1/1/1900';
while  ( strtotime("$maxRSdate") < strtotime("2012-01-01"))
{
    $output = shell_exec('php runscript.php');
    echo "\n\n$output\n\n";

    $sql="select nvl(max(edit_date),'2010-01-01') from contact;";
    $result2 = pg_query($connect, $sql);

       while ($row = pg_fetch_array($result2)) {
         $maxRSdate= $row[0];
       }
    $sql="select nvl(max(id),0) from contact where edit_date = '$maxRSdate';";
    $result2 = pg_query($connect, $sql);

       while ($row = pg_fetch_array($result2)) {
         $minRSid= $row[0];
       }

    echo "\n\nCurrent MaxRSDate:$maxRSdate\n\n";
    echo "\n\nCurrent minRSid:$minRSid\n\n";
    sleep(1);

}

?>
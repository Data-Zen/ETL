<?php
$start_total_timer = microtime(true); 
echo "\n\n*******Running GetMySQLData.php*************\n\n";

include 'credentials/PBBCredentials.php';

$ChunkSize=50000;

/* Get the biggest id from Redshift  */



$connect = pg_connect($PBBModifyCredentials);
eval("\$rs_qry_to_know_progress_date = \"$rs_qry_to_know_progress_date\";");
    $sql=$rs_qry_to_know_progress_date;
$result2 = pg_query($connect, $sql);

   while ($row = pg_fetch_array($result2)) {
     $maxRSdate= $row[0];
   }
eval("\$rs_qry_to_know_progress_id = \"$rs_qry_to_know_progress_id\";");
$sql=$rs_qry_to_know_progress_id;
$result2 = pg_query($connect, $sql);

   while ($row = pg_fetch_array($result2)) {
     $minRSid= $row[0];
   }


// Create connection

$link = mysqli_connect($servername, $username, $password, $dbname);
$link->set_charset("utf8");
#$link = mysqli_connect("localhost", "root", "", "car_rental");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
                                                $start_timer_1 = microtime(true); 
eval("\$mysql_qry = \"$mysql_qry\";");
$query = $mysql_qry;
echo "\n*******StartQuery\n".$query."\n*******EndQuery\n";
if ($result = mysqli_query($link, $query)) {

    $newArr = array();
    /* fetch associative array */
    while ($db_field = mysqli_fetch_assoc($result)) {
       // echo $db_field["edit_date"] . "\n\n";
        $newArr[] = $db_field;
    }
	
                                                $end1 = round((microtime(true) - $start_timer_1),2);
                                            	echo "\n======elapsed time for MysqlQuery: $end1 seconds \n";
	                                           $start_timer_1 = microtime(true); 
    $jsonresults = json_encode($newArr);
    //echo var_dump($jsonresults);
    //die;
    /* Needed to Match Redshift JSON Format */
    $jsonresults=substr($jsonresults,1,-1);
    #$jsonresults=str_replace("'", "\'", $jsonresults);
    $jsonresults=str_replace('},{', "}{", $jsonresults);
    $jsonresults=str_replace('1st_', "first_", $jsonresults);
    $jsonresults=str_replace('2nd_', "second_", $jsonresults);
    $jsonresults=str_replace('3rd_', "third_", $jsonresults);
    $jsonresults=str_replace('4th_', "forth_", $jsonresults);
    $jsonresults=str_replace('5th_', "fifth_", $jsonresults);
    /* Needed to Match Redshift JSON Format */
    //$fp = fopen('files/results.json', 'w');
	//fwrite($fp, $jsonresults);
	//fclose($fp);
    //$OutputFilePath='files/results.json';
    $OutputFilePath='files/'.$processname.'.json';
    file_put_contents($OutputFilePath, $jsonresults);
                                                $end1 = round((microtime(true) - $start_timer_1),2);
                                            	echo "\n\n======elapsed time for JsonEncoding and Writing: $end1 seconds \n";


}
?>
<?php
$start_total_timer = microtime(true); 
echo "\n\n*******Running testjson.php*************\n\n";

include 'credentials/PBBCredentials.php';


// Create connection
$link = mysqli_connect($servername, $username, $password, $dbname);
#$link = mysqli_connect("localhost", "root", "", "car_rental");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
$start_timer_1 = microtime(true); 
$query = "SELECT 
    *
    FROM buypuppy_manager.contact order by id desc limit 500;";

if ($result = mysqli_query($link, $query)) {

    $newArr = array();
    /* fetch associative array */
    while ($db_field = mysqli_fetch_assoc($result)) {
        $newArr[] = $db_field;
    }
	
    $end1 = round((microtime(true) - $start_timer_1),2);
	echo "\nelapsed time for Mysql: $end1 seconds \n";
	$start_timer_1 = microtime(true); 
    $jsonresults = json_encode($newArr);
    /* Needed to Match Redshift JSON Format */
    $jsonresults=substr($jsonresults,1,-1);
    $jsonresults=str_replace('},{', "}{", $jsonresults);
    $jsonresults=str_replace('1st', "first", $jsonresults);
    $jsonresults=str_replace('2nd', "second", $jsonresults);
    $jsonresults=str_replace('3rd', "third", $jsonresults);
    $jsonresults=str_replace('4th', "forth", $jsonresults);
    $jsonresults=str_replace('5th', "fifth", $jsonresults);
    /* Needed to Match Redshift JSON Format */
    $fp = fopen('files/results.json', 'w');
	fwrite($fp, $jsonresults);
	fclose($fp);
	
    $end1 = round((microtime(true) - $start_timer_1),2);
	echo "\nelapsed time for JsonEncoding and Writing: $end1 seconds \n";
    echo "\nCompleted";
    #echo json_encode($newArr); // get all products in json format.    
    echo "\n";
	$end1 = round((microtime(true) - $start_total_timer),2);
	echo "\nelapsed time Total: $end1 seconds \n";


}
    $end1 = round((microtime(true) - $start_total_timer),2);
    echo "\nelapsed time Total: $end1 seconds \n";
?>
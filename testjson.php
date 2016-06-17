<?php
$start1 = microtime(true); 

$servername = "158.85.128.197";
$username = "pbpaul";
$password = "3gPJfsaP";
$dbname = "buypuppy_manager";

// Create connection
$link = mysqli_connect($servername, $username, $password, $dbname);
#$link = mysqli_connect("localhost", "root", "", "car_rental");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$query = "SELECT * FROM buypuppy_manager.contact;";

if ($result = mysqli_query($link, $query)) {

    $newArr = array();
    /* fetch associative array */
    while ($db_field = mysqli_fetch_assoc($result)) {
        $newArr[] = $db_field;
    }
    $fp = fopen('files/results.json', 'w');
	fwrite($fp, json_encode($newArr));
	fclose($fp);

    echo "\nCompleted";
    #echo json_encode($newArr); // get all products in json format.    
    echo "\n";
	$end1 = round((microtime(true) - $start1),2);
	echo "\nelapsed time: $end1 seconds \n";


}

?>
<?php

include 'credentials/PBBCredentials.php';
//$connect = pg_connect($PBBModifyCredentials);


$db = new PDO('mysql:host='.$servername, $username, $password);



$sql = 'show slave status';

$query = $db->query($sql);
$res = $query->fetchall();
$Seconds_Behind_Master=61;
while ($Seconds_Behind_Master > 600 ) {
  $Seconds_Behind_Master=0;
    foreach($res as $item){
        $Seconds_Behind_Master=$item["Seconds_Behind_Master"];
        if ($Seconds_Behind_Master > 5) {
        echo "\nSleeping for 50 seconds because replication is behind $Seconds_Behind_Master seconds\n";
        echo date('l jS \of F Y h:i:s A');

        sleep(50);
              }
              else
              {
                echo "Replication all caught up!";

              }

            }
    }

?>


<?php

include 'credentials/PBBCredentials.php';
//$connect = pg_connect($PBBModifyCredentials);


$db = new PDO('mysql:host='.$servername, $username, $password);



$sql = 'show slave status';

$query = $db->query($sql);
$res = $query->fetchall();

foreach($res as $item){
    $Seconds_Behind_Master=$item["Seconds_Behind_Master"];
    if ($Seconds_Behind_Master > 0) {
    echo "Sleeping for 5 seconds because replication is behind $Seconds_Behind_Master seconds\n";

    sleep(5);
          }
          else
          {
            echo "Replication all caught up!";

          }

        }

?>


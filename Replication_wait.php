<?php
$SecondsAllowed=300;
include 'credentials/PBBCredentials.php';
//$connect = pg_connect($PBBModifyCredentials);


$db = new PDO('mysql:host='.$servername, $username, $password);
echo "\nStarting Replication Delay check...\n";


$sql = 'show slave status';

$query = $db->query($sql);
$res = $query->fetchall();
$Seconds_Behind_Master=$SecondsAllowed;
while ($Seconds_Behind_Master >= $SecondsAllowed ) {
  $Seconds_Behind_Master=0;
    foreach($res as $item){
        $Seconds_Behind_Master=$item["Seconds_Behind_Master"];
        if ($Seconds_Behind_Master >= $SecondsAllowed) {
        echo "\nSleeping for 50 seconds because replication is behind $Seconds_Behind_Master seconds\n";
        echo date('l jS \of F Y h:i:s A');

        sleep(50);
              }
              else
              {
                echo "Replication all caught up! $Seconds_Behind_Master seconds behind master.";

              }

            }
    }

?>


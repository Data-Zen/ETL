<?php
date_default_timezone_set('UTC');//or change to whatever timezone you want


include 'credentials/PBBCredentials.php';
$connect = pg_connect($PBBModifyCredentials);
$sql="

select * from users
";

$result = pg_query($connect, $sql);
   while ($row = pg_fetch_array($result)) {
     $userid= $row[0];
     $username= $row[1];  //One More Day
echo "UserID:$userid\n";
echo "UserName:$username\n";

   }
?>

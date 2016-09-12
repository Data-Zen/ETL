<?php
$start_total_timer = microtime(true);



include 'credentials/PBBCredentials.php';

print strftime('%c');
$connect = pg_connect($PBBModifyCredentials);
$processname="breaders";
$sql = "select lastmodified_dt from buypuppy_test.processhistory where processname=$processname;"
echo "\n*******StartQuery\n" . $sql . "\n*******EndQuery\n";
$resulttotal = pg_query($connect, $sql);
while ($row = pg_fetch_array($resulttotal)) {
    $lastmodified_dt= $row["lastmodified_dt"];
    };

$sql="select * from buypuppy_test.breaders where lastmodified_dt>$lastmodified_dt order by lastmodified_dt asc limit 100"

$resulttotal = pg_query($connect, $sql);
while ($row = pg_fetch_array($resulttotal)) {
    $id= $row["id"];
    $company_name= $row["company_name"];
    $breader_status_id= $row["breader_status_id"];
    $lastmodified_dt= $row["lastmodified_dt"];

        $sql="insert into buypuppy_test.geodesic_userdata (id,company_name) values ($id,$company_name)"
        $result = pg_query($connect, $sql);
        $sql="insert into buypuppy_test.geodesic_logins (id,status) values ($id,$status)"
        $result = pg_query($connect, $sql);

    };






print strftime('%c');
     $end11 = round((microtime(true) - $start_total_timer),2);
        echo "\n=============================== Elapsed Total Time " . round($end11 /60,1) ." Minutes =============================== \n";


?>


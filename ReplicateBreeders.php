<?php
$start_total_timer = microtime(true);



include 'credentials/PBBCredentials.php';

print strftime('%c');
$connect = pg_connect($PBBModifyCredentials);
$processname="breaders";
$sql = "select nvl(lastmodified_dt,'1/1/1900') lastmodified_dt from buypuppy_test.processhistory where processname='$processname';";
echo "\n*******StartQuery\n" . $sql . "\n*******EndQuery\n";
$resulttotal = pg_query($connect, $sql);
if ($row = pg_fetch_array($resulttotal)) {
        $lastmodified_dt= $row["lastmodified_dt"];
    }
    else
    {
        $lastmodified_dt="1/1/1900";
    }
    ;



$sql="select * from buypuppy_test.breaders where lastmodified_dt>'$lastmodified_dt' order by lastmodified_dt asc limit 100";
echo "\n*******StartQuery\n" . $sql . "\n*******EndQuery\n";
$resulttotal = pg_query($connect, $sql);
while ($row = pg_fetch_array($resulttotal)) {
    $id= $row["id"];
    $company_name= $row["company_name"];
    $breader_status_id= $row["breader_status_id"];
    $lastmodified_dt= $row["lastmodified_dt"];

        $sql="insert into buypuppy_test.geodesic_userdata (id,company_name) values ($id,'".addslashes($company_name)."')";
        echo "\n*******StartQuery\n" . $sql . "\n*******EndQuery\n";
        $result = pg_query($connect, $sql);
        $sql="insert into buypuppy_test.geodesic_logins (id,status) values ($id,$breader_status_id)";
        echo "\n*******StartQuery\n" . $sql . "\n*******EndQuery\n";
        $result = pg_query($connect, $sql);
        $rowsaffected = pg_affected_rows($result);

    };

if ($rowsaffected > 0) {
        $sql = "update buypuppy_test.processhistory set lastmodified_dt='$lastmodified_dt'  where processname='$processname';";

        echo "\n*******StartQuery\n" . $sql . "\n*******EndQuery\n";
        $result = pg_query($connect, $sql);
        $rowsaffected = pg_affected_rows($result);
        
            if ($rowsaffected == 0) { 

                $sql = "insert into buypuppy_test.processhistory (processname,lastmodified_dt) values ('$processname','$lastmodified_dt');";
                echo "\n*******StartQuery\n" . $sql . "\n*******EndQuery\n";
                $result = pg_query($connect, $sql);

              }
}









print strftime('%c');
     $end11 = round((microtime(true) - $start_total_timer),2);
        echo "\n=============================== Elapsed Total Time " . round($end11 /60,1) ." Minutes =============================== \n";


?>


<?php
$start_total_timer = microtime(true);



include 'credentials/PBBCredentials.php';

print strftime('%c');
$connect     = pg_connect($PBBModifyCredentials);
$processname = "breeders";
$sql         = "select nvl(lastmodified_dt,'1/1/1900') lastmodified_dt from buypuppy_test.processhistory where processname='$processname';";
echo "\n*******StartQuery To Get ProcessHistory\n" . $sql . "\n*******EndQuery\n";
$resulttotal = pg_query($connect, $sql);
if ($row = pg_fetch_array($resulttotal)) {
    $lastmodified_dt = $row["lastmodified_dt"];
} else {
    $lastmodified_dt = "1/1/1900";
}
;



$sql = "select * from buypuppy_test.$processname where lastmodified_dt>'$lastmodified_dt' order by lastmodified_dt asc limit 100";
echo "\n*******StartQuery To Get New Data From SourceTable\n" . $sql . "\n*******EndQuery\n";
$resulttotal = pg_query($connect, $sql);
while ($row = pg_fetch_array($resulttotal)) {
    $id                = $row["id"];
    $company_name      = $row["company_name"];
    $breeder_status_id = $row["breeder_status_id"];
    $lastmodified_dt   = $row["lastmodified_dt"];
    
    $sql = "select 1 as resultid from buypuppy_test.geodesic_userdata where id=$id";
    echo "\n*******StartQuery To Check If Row Already Exists\n" . $sql . "\n*******EndQuery\n";
    $result = pg_query($connect, $sql);

    if (pg_num_rows($result) > 0) {
        
        $sql = "update buypuppy_test.geodesic_userdata set id=$id,company_name='" . addslashes($company_name) . "' where id=$id";
        $operation_type="Update";
        
    } else {
        $sql = "insert into buypuppy_test.geodesic_userdata (id,company_name) values ($id,'" . addslashes($company_name) . "')";
        $operation_type="Insert";
    }
    
    
    echo "\n*******StartQuery To Upsert The Record \n" . $sql . "\n*******EndQuery\n";
    $result = pg_query($connect, $sql);

    $numrows = 0;
    $numrows = pg_affected_rows($result);
    $sql="INSERT INTO dev.buypuppy_test.processhistory_extended 
        select '$processname'
       , '$processname'
       , 'geodesic_userdata'
       , $numrows
       , $id
       , '$operation_type'
       , getdate();";
    echo "\n*******StartQuery To Log Extended Details \n" . $sql . "\n*******EndQuery\n";
    $result = pg_query($connect, $sql);
    
    $sql = "select 1 as resultid from buypuppy_test.geodesic_logins where id=$id";
    echo "\n*******StartQuery  To Check If Row Already Exists\n" . $sql . "\n*******EndQuery\n";
    $result = pg_query($connect, $sql);
    if (pg_num_rows($result) > 0) {
        
        $sql = "update buypuppy_test.geodesic_logins set id=$id,status=$breeder_status_id where id=$id";
        $operation_type="Update";
        
    } else {
        $sql = "insert into buypuppy_test.geodesic_logins (id,status) values ($id,$breeder_status_id)";
        $operation_type="Insert";
    }
    
    
    echo "\n*******StartQuery To Upsert The Record\n" . $sql . "\n*******EndQuery\n";
    $result       = pg_query($connect, $sql);

    $numrows = 0;
    $numrows = pg_affected_rows($result);
    $sql="INSERT INTO dev.buypuppy_test.processhistory_extended 
        select '$processname'
       , '$processname'
       , 'geodesic_logins'
       , $numrows
       , $id
       , '$operation_type'
       , getdate();";
    echo "\n*******StartQuery To Log Extended Details\n" . $sql . "\n*******EndQuery\n";
    $result = pg_query($connect, $sql);
    
    
    
    if ($numrows > 0) {
        $sql = "update buypuppy_test.processhistory set lastmodified_dt='$lastmodified_dt'  where processname='$processname';";
        
        echo "\n*******StartQuery To Update ProcessHistory On Latest LastModifed Date\n" . $sql . "\n*******EndQuery\n";
        $result       = pg_query($connect, $sql);
        $rowsaffected = pg_affected_rows($result);
        
        if ($rowsaffected == 0) {
            
            $sql = "insert into buypuppy_test.processhistory (processname,lastmodified_dt) values ('$processname','$lastmodified_dt');";
            echo "\n*******StartQuery Insert Into ProcessHistory if First Time\n" . $sql . "\n*******EndQuery\n";
            $result = pg_query($connect, $sql);
            
        }
    }
    
    
}
;



print strftime('%c');
$end11 = round((microtime(true) - $start_total_timer), 2);
echo "\n=============================== Elapsed Total Time " . round($end11 / 60, 1) . " Minutes =============================== \n";


?>


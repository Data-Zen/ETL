<?php
if (isset($argv[1])) {
    $SourceTable = $argv[1];
} else {
    echo '\n\n SourceTable Needs to passed in.\n\n';
    die;
}

$start_total_timer = microtime(true);

include 'credentials/PBBCredentials.php';

print strftime('%c');
$connect = pg_connect($PBBModifyCredentials);
//$SourceTable = "breeders";
$sql     = "select nvl(lastmodified_dt,'1/1/1900') lastmodified_dt,columns from buypuppy_test.DataMigrationSettings where SourceTable='$SourceTable';";
echo "\n*******StartQuery To Get DataMigrationSettings\n" . $sql . "\n*******EndQuery\n";
$resulttotal = pg_query($connect, $sql);
if ($row = pg_fetch_array($resulttotal)) {
    $lastmodified_dt = $row["lastmodified_dt"];
    $columns         = $row["columns"];
}
;
if (empty($lastmodified_dt)) {
    echo "Problem getting Data for $SourceTable from DataMigrationSettings\n\nQuitting. \n\n";
    die;
}

$sql = "select * from buypuppy_test.$SourceTable where lastmodified_dt>'$lastmodified_dt' order by lastmodified_dt asc limit 100";
echo "\n*******StartQuery To Get New Data From SourceTable\n" . $sql . "\n*******EndQuery\n";
$resulttotal = pg_query($connect, $sql);
while ($row = pg_fetch_array($resulttotal)) {
    
    eval($columns);
    $sql = "select * from buypuppy_test.datamigrationsettings_extended where SourceTable='$SourceTable'";
    echo "\n*******StartQuery To Get datamigrationsettings_extended\n" . $sql . "\n*******EndQuery\n";
    $results = pg_query($connect, $sql);
    while ($destinations = pg_fetch_array($results)) {
      
        $destinationtable = $destinations["destinationtable"];
        $insertstatement  = $destinations["insertstatement"];
        $updatestatement  = $destinations["updatestatement"];
        
        $sql = "select 1 as resultid from $destinationtable where id=$id";
        echo "\n*******StartQuery To Check If Row Already Exists\n" . $sql . "\n*******EndQuery\n";
        $result = pg_query($connect, $sql);
        
        if (pg_num_rows($result) > 0) {
            $sql            = "update $destinationtable $updatestatement";
            $operation_type = "Update";
        } else {
            $sql            = "insert into $destinationtable " . $insertstatement;
            $operation_type = "Insert";
        }
        
        eval("\$sql = \"$sql\";");
        echo "\n*******StartQuery To Upsert The Record \n" . $sql . "\n*******EndQuery\n";
        $result = pg_query($connect, $sql);
        
        $numrows = "null";
        if (pg_affected_rows($result) > 0) {
            $numrows = pg_affected_rows($result);
        }
        $sql = "INSERT INTO dev.buypuppy_test.DataMigrationhistory_extended 
        select '$SourceTable'
       , '$destinationtable'
       , $numrows
       , $id
       , '$operation_type'
       , getdate();";
        echo "\n*******StartQuery To Log Extended Details \n" . $sql . "\n*******EndQuery\n";
        $result = pg_query($connect, $sql);
        
    }
    
    if ($numrows > 0) {
        $sql = "update buypuppy_test.DataMigrationSettings set lastmodified_dt='$lastmodified_dt'  where SourceTable='$SourceTable';";
        
        echo "\n*******StartQuery To Update DataMigrationSettings On Latest LastModifed Date\n" . $sql . "\n*******EndQuery\n";
        $result       = pg_query($connect, $sql);
        $rowsaffected = pg_affected_rows($result);
        
    }
    
}
;

print strftime('%c');
$end11 = round((microtime(true) - $start_total_timer), 2);
echo "\n=============================== Elapsed Total Time " . round($end11 / 60, 1) . " Minutes =============================== \n";

?>


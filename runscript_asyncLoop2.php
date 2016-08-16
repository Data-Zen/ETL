<?php
$start_total_timer = microtime(true);
$HowManyMySQLQueriesRunningThreshold = 6;
$ChunkSize = 4000000;


include 'credentials/PBBCredentials.php';

print strftime('%c');
$connect = pg_connect($PBBModifyCredentials);

$sql = "select 
trim(datname)   db_name
,trim(nspname)   schema_name
,trim(relname)   table_name
,max(rows) rws
--,stv_tbl_perm.*
from stv_tbl_perm
join pg_class on pg_class.oid = stv_tbl_perm.id
join pg_namespace on pg_namespace.oid = relnamespace
join pg_database on pg_database.oid = stv_tbl_perm.db_id
where trim(nspname) ilike '%pupp%'
--and relname not ilike '%contact%'
group by 1,2,3
order by trim(nspname) ,trim(relname),max(rows) ;";

echo "\n*******StartQuery\n" . $sql . "\n*******EndQuery\n";
$resulttotal = pg_query($connect, $sql);
$i           = 1;
while ($row = pg_fetch_array($resulttotal)) {
    include 'Replication_wait.php';
    sleep (1);
    $start_timer_12 = microtime(true);
    $schema_name    = $row["schema_name"];
    $table_name     = $row["table_name"];
    $rows           = $row["rws"];
    
    $mysqltbl = $schema_name . "." . $table_name;
    // $mysqltbl          = $schema_name . "_" . $table_name ;    
    
  
    # $output_file_name=$row["output_file_name"];
    # $stage_table_name=$row["stage_table_name"];
    # $rs_delete_qry=$row["rs_delete_qry"];
    # $rs_insert_from_stage_qry=$row["rs_insert_from_stage_qry"];
    
    $link = mysqli_connect($servername, $username, $password, $dbname);
    
    $query = "select count(distinct id) ct from information_schema.processlist where user='pbpaul' and time > 0;";
    

    $HowManyMySQLQueriesRunning          = $HowManyMySQLQueriesRunningThreshold + 1;
    while ($HowManyMySQLQueriesRunning > $HowManyMySQLQueriesRunningThreshold) {
       // echo "\n*******StartQuery mysqli_query\n" . $query . "\n*******EndQuery\n";
        if ($result = mysqli_query($link, $query)) {
            
            while ($row = mysqli_fetch_assoc($result)) {
                //var_dump($row);
                $HowManyMySQLQueriesRunning = $row["ct"];
            }
        }
        
        
        echo "\nHowManyMySQLQueriesRunning: $HowManyMySQLQueriesRunning";
        if ($HowManyMySQLQueriesRunning > $HowManyMySQLQueriesRunningThreshold) {
            echo "\nSleeping 10 Seconds...";
            sleep(10);
        }
    }
    
    
    
    $execstring = "php runscript_asyncLoop_guts.php $ChunkSize $mysqltbl > /dev/null 2>/dev/null &";
    echo "\n$execstring\n";
    $output       = "";
    $return_value = "";
    exec($execstring, $output, $return_value);
    /*
    if ($i % 20 == 0) {
    $execstring = "ps aux | grep php";
    
    $output       = "";
    $return_value = "";
    exec($execstring, $output, $return_value);
    //var_dump($output) ;
    //echo count($output);
    $howmanystillrunning=count($output);
    $files = scandir("files/");
    
    // Count number of files and store them to variable..
    $num_files = count($files)-3;
    $howmanystillrunning=$howmanystillrunning+$num_files;
    
    
    $sleeplength=$howmanystillrunning*5;
    echo "\n\n Sleeping for $sleeplength seconds \n\n";
    sleep($sleeplength);
    */
    
    $i = $i + 1;
}




print strftime('%c');
     $end11 = round((microtime(true) - $start_total_timer),2);
        echo "\n=============================== Elapsed Total Time " . round($end11 /60,1) ." Minutes =============================== \n";


?>


<?php

include 'credentials/PBBCredentials.php';


$connect = pg_connect($PBBModifyCredentials);

$sql = "select distinct(id) table_id
,trim(datname)   db_name
,trim(nspname)   schema_name
,trim(relname)   table_name
,rows
--,stv_tbl_perm.*
from stv_tbl_perm
join pg_class on pg_class.oid = stv_tbl_perm.id
join pg_namespace on pg_namespace.oid = relnamespace
join pg_database on pg_database.oid = stv_tbl_perm.db_id
where trim(nspname) ilike '%pupp%'
and relname not ilike '%contact%'
order by trim(nspname) ,trim(relname),rows 
;";

echo "\n*******StartQuery\n" . $sql . "\n*******EndQuery\n";
$resulttotal = pg_query($connect, $sql);

while ($row = pg_fetch_array($resulttotal)) {
    
$start_timer_12= microtime(true);
    $schema_name = $row["schema_name"];
    $table_name  = $row["table_name"];
    $rows        = $row["rows"];
    
    $mysqltbl          = $schema_name . "." . $table_name ;
   // $mysqltbl          = $schema_name . "_" . $table_name ;    
  
    $ChunkSize         = 1000000;
    
    # $output_file_name=$row["output_file_name"];
    # $stage_table_name=$row["stage_table_name"];
    # $rs_delete_qry=$row["rs_delete_qry"];
    # $rs_insert_from_stage_qry=$row["rs_insert_from_stage_qry"];
    
    
    
    $uid = uniqid();
    
    $sql = "INSERT INTO dw_processes_history 
  select '$uid','$mysqltbl','start',getdate();";
    echo "\n*******StartQuery\n" . $sql . "\n*******EndQuery\n";
    $result2234 = pg_query($connect, $sql);
    
    
    $i           = 0;
    $offset      = 0;
    $recordcount = 1;
    while ($recordcount > 0 and $i < 1000) // Actually used to end the loop
        {
        $start_timer_13= microtime(true);
        $execstring = "php runscript2.php " . $i * $ChunkSize . " $ChunkSize $mysqltbl ";
        echo "\n$execstring\n";
        $output       = "";
        $return_value = "";
        exec($execstring, $output, $return_value);
        //passthru ($execstring);
        //echo "\n\nOutput:" . $output[0] . "\n\n";
        //var_dump($output) ;
        echo "\nReturnValue:$return_value\n";
        $recordcount = $return_value;
        $i           = $i + 1;
        echo "\nCompleted loop iteration $i: $mysqltbl ";
        //usleep(500000);
         $end13 = round((microtime(true) - $start_timer_13),2);
        echo "***** Elapsed Time for Loop: $end13 s " . round($end13 /60,1) ." m \n";
        
    }
    /* Rename realtables to old tables and Dev Tables to real tables */
    $sql = "INSERT INTO dw_processes_history 
  select '$uid','$mysqltbl','end',getdate();";
echo "\n*******StartQuery\n" . $sql . "\n*******EndQuery\n";

        $end11 = round((microtime(true) - $start_timer_12),2);
        echo "\n=============================== Elapsed Total Time for $mysqltbl: $end11 Seconds   " . round($end11 /60,1) ." Minutes =============================== \n";

$result221 = pg_query($connect, $sql);
}







?>


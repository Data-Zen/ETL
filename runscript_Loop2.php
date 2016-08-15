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
order by trim(nspname) ,rows ,trim(relname)
;";

echo "\n*******StartQuery\n" . $sql . "\n*******EndQuery\n";
$resulttotal = pg_query($connect, $sql);

while ($row = pg_fetch_array($resulttotal)) {
    
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
        echo "\nCompleted loop iteration $i: $mysqltbl \n";
        //usleep(500000);
        
    }
    /* Rename realtables to old tables and Dev Tables to real tables */
    $sql = "INSERT INTO dw_processes_history 
  select '$uid','$mysqltbl','end',getdate();";
echo "\n*******StartQuery\n" . $sql . "\n*******EndQuery\n";
$result221 = pg_query($connect, $sql);
}







?>


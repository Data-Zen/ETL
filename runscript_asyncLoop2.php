<?php

include 'credentials/PBBCredentials.php';


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
and relname not ilike '%contact%'
group by 1,2,3
order by trim(nspname) ,trim(relname),max(rows) ;";

echo "\n*******StartQuery\n" . $sql . "\n*******EndQuery\n";
$resulttotal = pg_query($connect, $sql);
$i=1;
while ($row = pg_fetch_array($resulttotal)) {
    
$start_timer_12= microtime(true);
    $schema_name = $row["schema_name"];
    $table_name  = $row["table_name"];
    $rows        = $row["rws"];
    
    $mysqltbl          = $schema_name . "." . $table_name ;
   // $mysqltbl          = $schema_name . "_" . $table_name ;    
  
    $ChunkSize         = 1000000;
    
    # $output_file_name=$row["output_file_name"];
    # $stage_table_name=$row["stage_table_name"];
    # $rs_delete_qry=$row["rs_delete_qry"];
    # $rs_insert_from_stage_qry=$row["rs_insert_from_stage_qry"];
    
        $execstring = "php runscript_asyncLoop_guts.php $ChunkSize $mysqltbl > /dev/null 2>/dev/null &";
        echo "\n$execstring\n";
        $output       = "";
        $return_value = "";
        exec($execstring, $output, $return_value);

        if ($i % 10 == 0) {
            $execstring = "ps aux | grep php";
        
             $output       = "";
            $return_value = "";
            exec($execstring, $output, $return_value);
        //var_dump($output) ;
        //echo count($output);
            $howmanystillrunning=count($output);
            $sleeplength=$howmanystillrunning*10;
          echo "\n\n Sleeping for $sleeplength seconds \n\n";
          sleep($sleeplength);
        }
    $i=$i+1;
}







?>


<?php
if (isset($argv[1])) {
    $inProcessName=$argv[1];
} else {
 echo '\n\n ProcessName Needs to passed in.\n\n';
 die;
}


include 'credentials/PBBCredentials.php';


$connect = pg_connect($PBBModifyCredentials);

$sql="select *
       from
        dw_processes
        where processname='$inProcessName';";
    $result2 = pg_query($connect, $sql);

       while ($row = pg_fetch_array($result2)) {
         
       $processname=$row["processname"];
       $rs_qry_to_know_progress_date=$row["rs_qry_to_know_progress_date"];
       $rs_qry_to_know_progress_id=$row["rs_qry_to_know_progress_id"];
       $mysql_qry=$row["mysql_qry"];
       # $output_file_name=$row["output_file_name"];
       # $stage_table_name=$row["stage_table_name"];
       $rs_delete_qry=$row["rs_delete_qry"];
       $rs_insert_from_stage_qry=$row["rs_insert_from_stage_qry"];
       $chunksize=$row["chunksize"];
       }

echo "\n\n\n\n\n\n\n\n ProcessName: $processname \n";
/*
echo "\n\n\n\n\n\n\n\n rs_qry_to_know_progress_date: $rs_qry_to_know_progress_date \n";
echo "\n\n\n\n\n\n\n\n rs_qry_to_know_progress_id: $rs_qry_to_know_progress_id \n";
echo "\n\n\n\n\n\n\n\n mysql_qry: $mysql_qry \n";
echo "\n\n\n\n\n\n\n\n rs_delete_qry: $rs_delete_qry \n";
echo "\n\n\n\n\n\n\n\n rs_insert_from_stage_qry: $rs_insert_from_stage_qry \n";
echo "\n\n\n\n\n\n\n\n chunksize: $chunksize \n";
*/
				$start_timer_11 = microtime(true); 
include('GetMySQLData.php');
			    $end11 = round((microtime(true) - $start_timer_11),2);
				echo "\n=====================elapsed time for Mysql: $end11 seconds \n";
				$start_timer_11 = microtime(true); 
include('s3sdk.php');
			    $end11 = round((microtime(true) - $start_timer_11),2);
				echo "\n=====================elapsed time for S3: $end11 seconds \n";
				$start_timer_11 = microtime(true); 
include('RedshiftQueries.php');
			    $end11 = round((microtime(true) - $start_timer_11),2);
				echo "\n=====================elapsed time for Redshift push: $end11 seconds \n";

?>
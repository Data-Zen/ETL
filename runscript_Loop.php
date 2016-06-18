<?php

if (isset($argv[1])) {
    $inProcessName=$argv[1];
    echo "\nPassed in: '$inProcessName'\n\n";
   
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
        echo "\n*******StartQuery\n".$sql."\n*******EndQuery\n";
    $result2 = pg_query($connect, $sql);

       while ($row = pg_fetch_array($result2)) {
         
       $processname=$row["processname"];
       $rs_qry_to_know_progress_date=$row["rs_qry_to_know_progress_date"];
       $rs_qry_to_know_progress_id=$row["rs_qry_to_know_progress_id"];

       $my_sql_checkmaxdate=$row["my_sql_checkmaxdate"];
      # $output_file_name=$row["output_file_name"];
      # $stage_table_name=$row["stage_table_name"];
      # $rs_delete_qry=$row["rs_delete_qry"];
      # $rs_insert_from_stage_qry=$row["rs_insert_from_stage_qry"];
       }

if (!isset($processname)) {
  echo "\nNo Such ProcessName '$inProcessName' found in dw_processes\n Quitting";
  die;

}

$OutputFilePath='files/'.$processname.'.json';
/* Get max date from mysql*/
mysql_connect($servername ,$username, $password) OR DIE ('Unable to connect to database! Please try again later.');
$sql=$my_sql_checkmaxdate;
echo "\n*******StartQuery\n".$sql."\n*******EndQuery\n";
$result2 = mysql_query($sql);
$resultsrow = mysql_fetch_assoc($result2);   
//var_dump($resultsrow); // see what type of variable mysql_fetch_array() gave you
$mysqlEndDate = $resultsrow['dt'];  
 


$maxRSdate = '1/1/1900';
$enddate =date($mysqlEndDate);   
echo "MySQL EndDate: $enddate";
while  ( strtotime("$maxRSdate") < strtotime($enddate))
{
    $execstring = "php runscript.php $processname 2>&1";
    echo "\n$execstring\n";
     passthru ($execstring);
    //echo "\n\n$output\n\n";

     echo "\n\n\n\n\n\n\n\n Completed: $processname \n\n\n\n\n\n\n\n\n\n\n";
    eval("\$rs_qry_to_know_progress_date = \"$rs_qry_to_know_progress_date\";");
    $sql=$rs_qry_to_know_progress_date;
    $result2 = pg_query($connect, $sql);

       while ($row = pg_fetch_array($result2)) {
         $maxRSdate= $row[0];
       }
    eval("\$rs_qry_to_know_progress_id = \"$rs_qry_to_know_progress_id\";");
    $sql=$rs_qry_to_know_progress_id;
    $result2 = pg_query($connect, $sql);

       while ($row = pg_fetch_array($result2)) {
         $minRSid= $row[0];
       }

    echo "\n\nCurrent MaxRSDate:$maxRSdate\n\n";
    echo "\n\nCurrent minRSid:$minRSid\n\n";
    //usleep(500000);

}

?>
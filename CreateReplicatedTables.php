<?php


if (isset($argv[1])) {
    $db=$argv[1];
} else {
 echo '\n\n Database Needs to passed in.\n\n';
 die;
}


if (isset($argv[2])) {
    $table=$argv[2];
} else {
 echo '\n\n Database Needs to passed in.\n\n';
 die;
}




echo "\nDatabase: $db";
echo "\nTable: $table";



if(empty($table)){
 echo "\nTable Name is needed. Please pass in two arguments. script.php DBName, TableName\n\n";
 exit;
}

$debug=1;
$start_total_timer = microtime(true); 
#echo "\n\n*******Running GetMySQLData.php*************\n\n";

include 'credentials/PBBCredentials.php';

#$ChunkSize=50000;

/* Get the biggest id from Redshift  */
/*



$connect = pg_connect($PBBModifyCredentials);
eval("\$rs_qry_to_know_progress_date = \"$rs_qry_to_know_progress_date\";");

    $sql=$rs_qry_to_know_progress_date;
    echo "\n*******StartQuery rs_qry_to_know_progress_date\n".$sql."\n*******EndQuery\n";
$result2 = pg_query($connect, $sql);

   while ($row = pg_fetch_array($result2)) {
     $maxRSdate= $row[0];
   }
//eval("\$rs_qry_to_know_progress_id = \"$rs_qry_to_know_progress_id\";");
$sql=$rs_qry_to_know_progress_id;
echo "\n*******StartQuery rs_qry_to_know_progress_id\n".$sql."\n*******EndQuery\n";
$result2 = pg_query($connect, $sql);

   while ($row = pg_fetch_array($result2)) {
     $minRSid= $row[0];
   }
*/

// Create connection

$link = mysqli_connect($servername, $username, $password, $dbname);
$link->set_charset("utf8");
#$link = mysqli_connect("localhost", "root", "", "car_rental");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
$start_timer_1 = microtime(true); 

//eval("\$mysql_qry = \"$mysql_qry\";");
$query = "SHOW CREATE TABLE ".$db.".".$table;
echo "\n*******StartQuery mysqli_query\n".$query."\n*******EndQuery\n";
if ($result = mysqli_query($link, $query)) {
	printf("Affected rows (SELECT): %d\n", mysqli_affected_rows($link));
    $newArr = array();
    /* fetch associative array */
    while ($db_field = mysqli_fetch_assoc($result)) {
       $createtable= $db_field["Create Table"] . "\n\n";
       echo $createtable . "\n\n";
             //  $db_field = mb_convert_encoding("UTF-8","UTF-8//IGNORE",$db_field);
        //$db_field = mb_convert_encoding($db_field , 'UTF-8', 'UTF-8');
       // $db_field = preg_replace(/[^\x0A\x20-\x7E]/,'',$db_field);
       
    }
	
                                                $end1 = round((microtime(true) - $start_timer_1),2);
                                            	echo "\n======elapsed time for MysqlQuery: $end1 seconds \n";
	                                           $start_timer_1 = microtime(true); 


}



$connect = pg_connect($PBBModifyCredentials);

    $sql=$createtable;

    $sql=str_ireplace ('`', '"', $sql);
    $sql=str_ireplace ('AUTO_INCREMENT', "", $sql);
    $sql=str_ireplace ('UNSIGNED', "", $sql);
    $sql=str_ireplace ('CREATE TABLE ', "CREATE TABLE $db.", $sql);
    
    
	$sql=str_ireplace (' user ', ' "user" ', $sql);
	$sql=str_ireplace (' longtext', ' text', $sql);
	$sql=str_ireplace ('tinytext', 'text', $sql);
	$sql=str_ireplace ('mediumtext', 'text', $sql);
	$sql=str_ireplace (' tinyint', ' int', $sql);
	$sql=str_ireplace (' smallint', ' int', $sql);
	$sql=str_ireplace (' varbinary', ' varchar', $sql);
    $sql= preg_replace("/ DEFAULT([^,]+)/"," ",$sql); // 'ABC '
   $sql= preg_replace("/(?<=\ int)[^)]+\)/"," ",$sql); // 'ABC '
   $sql= preg_replace("/(?<=\"int\()[^)]+\)/","",$sql); // 'ABC '   
   	$sql=str_ireplace ('int(', 'int', $sql);
   $sql= preg_replace("/ COLLATE([^,]+)/"," ",$sql); // 'ABC '
	$sql= preg_replace("/ COMMENT([^,]+)/"," ",$sql); // 'ABC '
	$sql= preg_replace("/ decimal([^ ]+)/"," float",$sql); // 'ABC '
	$sql= preg_replace("/ double([^ ]+)/"," float",$sql); // 'ABC '	
	$sql= preg_replace("/ float([^ ]+)/"," float",$sql); // 'ABC '	
   $sql= preg_replace("/UNIQUE KEY(.*)/","",$sql); // 'ABC '
   $sql= preg_replace("/ enum(.*)\,/","varchar(max) ,",$sql); // 'ABC '

   $sql= preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $sql);
   $sql= preg_replace("/ENGINE.*$/", "", $sql);
   $sql=trim($sql);


$lines = explode("\n", $sql);
$exclude = array();
foreach ($lines as $line) {
	//echo "line: $line\n";
	//echo strpos($line, 'KEY') . "\n";
	//echo strpos($line, 'PRIMARY') . "\n";

    if ((strpos($line, 'KEY')?:0 >0 and strpos($line, 'PRIMARY') ==FALSE) ) {

        // echo "skipping\n\n\n";
         continue;
    }
    $exclude[] = $line;
}
$sql= implode("\n", $exclude);
	if(substr($sql, -3)==",
)")
	{
		$sql=substr($sql,0,-3) . "
)";
	}  
	



$sql=" drop table if exists \"" .  $table . "\";drop table if exists $db.\"" .  $table . "\";
".$sql;

if ($debug==1)
{
echo "\n*******StartQuery\n".$sql."\n*******EndQuery\n";
}
$rec = pg_query($connect,$sql);
$rowsaffected=pg_affected_rows($rec);
echo "Rows affected $rowsaffected \n\n";
//eval("\$rs_qry_to_know_progress_id = \"$rs_qry_to_know_progress_id\";");


?>



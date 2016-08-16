<?php


include 'credentials/PBBCredentials.php';


$link = mysqli_connect($servername, $username, $password, $dbname);
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

//eval("\$mysql_qry = \"$mysql_qry\";");
$query = "select table_schema,table_name,table_rows from information_schema.TABLES 
where TABLE_SCHEMA  like '%puppy%' order by 1,2";
echo "\n*******StartQuery mysqli_query\n".$query."\n*******EndQuery\n";
if ($result = mysqli_query($link, $query)) {
	printf("Affected rows (SELECT): %d\n", mysqli_affected_rows($link));
    $newArr = array();
    /* fetch associative array */
    while ($db_field = mysqli_fetch_assoc($result)) {
       $table_schema= $db_field["table_schema"] ;
       $table_name= $db_field["table_name"] ;
       $table_rows= $db_field["table_rows"] ;
      
  		$execstring = "php CreateReplicatedTables.php $table_schema $table_name > /dev/null 2>/dev/null &";
        echo "\n$execstring\n";
        $output       = "";
        $return_value = "";
        exec($execstring, $output, $return_value);
        usleep(200000);  // two tenth a second
      }
}
?>



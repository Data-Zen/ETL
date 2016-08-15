<?php
$start_total_timer = microtime(true);
echo "\n\n*******Running GetMySQLData.php*************\n\n";

//include 'credentials/PBBCredentials.php';

#$ChunkSize=50000;

/* Get the biggest id from Redshift  */



                $start_timer_1 = microtime(true); 

$link = mysqli_connect($servername, $username, $password, $dbname);
$link->set_charset("utf8");

    $query         = "select * from $mysqltbl order by 1,2 LIMIT $ChunkSize OFFSET $offset";
    echo "\n*******StartQuery mysqli_query\n" . $query . "\n*******EndQuery\n";
    if ($result = mysqli_query($link, $query)) {
        $mysqlaffectedrows = mysqli_affected_rows($link);
        printf("Affected rows (SELECT): %d\n", $mysqlaffectedrows);
        //$newArr = array();
        /* fetch associative array */
 $OutputFilePath='files/'.$mysqltbl.'.csv';
$fp = fopen($OutputFilePath, 'w');
echo "Starting Write to CSV \n";while($row = mysqli_fetch_assoc($result)) {
   // if ($i == 1) {
        // this is the header
     //   fputcsv($fp, array_keys($row));
      //  $i++;
   // }

    // this is the customer/order data
    fputcsv($fp, $row);
}
echo "Export Completed \n";
fclose($fp);

}



?>
<?php
$start_total_timer = microtime(true);
echo "\n\n*******Running GetMySQLData.php*************\n\n";
                $start_timer_1 = microtime(true); 

$link = mysqli_connect($servername, $username, $password, $dbname);
$link->set_charset("utf8");

    $query         = "select * from $mysqltbl order by 1 LIMIT $ChunkSize OFFSET $offset";
    echo "\n*******StartQuery mysqli_query\n" . $query . "\n*******EndQuery\n";
    if ($result = mysqli_query($link, $query)) {
        $mysqlaffectedrows = mysqli_affected_rows($link);
        printf("Affected rows (SELECT): %d\n", $mysqlaffectedrows);
        //$newArr = array();
        /* fetch associative array */
 $OutputFilePath='files/'.$mysqltbl.'.csv';
$fp = fopen("compress.zlib://$OutputFilePath", 'w');
echo "Starting Write to CSV \n";while($row = mysqli_fetch_assoc($result)) {
   // if ($i == 1) {
        // this is the header
     //   fputcsv($fp, array_keys($row));
      //  $i++;
   // }

    // this is the customer/order data
  //$row=str_replace('"', '""', $row);
  //$row = preg_replace($regex, '$1', $row);
  $row =preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $row);
    fputcsv($fp, $row,",","%");
}
echo "Export Completed \n";
fclose($fp);

}



?>
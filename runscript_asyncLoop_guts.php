<?php
include 'credentials/PBBCredentials.php';
if (isset($argv[1])) {
    $ChunkSize = $argv[1];
} else {
    echo "\n\n ChunkSize Needs to passed in.\n\n";
    die;
}

if (isset($argv[2])) {
    $mysqltbl = $argv[2];
} else {
    echo "\n\n TableName Needs to passed in.\n\n";
    die;
}

$connect = pg_connect($PBBModifyCredentials);

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

        
    }
    /* Rename realtables to old tables and Dev Tables to real tables */
    $sql = "INSERT INTO dw_processes_history 
  select '$uid','$mysqltbl','end',getdate();";
echo "\n*******StartQuery\n" . $sql . "\n*******EndQuery\n";







?>


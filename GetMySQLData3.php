<?php
$start_total_timer = microtime(true);
echo "\n\n*******Running GetMySQLData.php*************\n\n";

//include 'credentials/PBBCredentials.php';

#$ChunkSize=50000;

/* Get the biggest id from Redshift  */



                $start_timer_1 = microtime(true); 

$link = mysqli_connect($servername, $username, $password, $dbname);
$link->set_charset("utf8");

    $query         = "select * from $mysqltbl LIMIT $ChunkSize OFFSET $offset";
    echo "\n*******StartQuery mysqli_query\n" . $query . "\n*******EndQuery\n";
    if ($result = mysqli_query($link, $query)) {
        $mysqlaffectedrows = mysqli_affected_rows($link);
        printf("Affected rows (SELECT): %d\n", $mysqlaffectedrows);
        $newArr = array();
        /* fetch associative array */
        while ($db_field = mysqli_fetch_assoc($result)) {
            // echo $db_field["edit_date"] . "\n\n";
            //  $db_field = mb_convert_encoding("UTF-8","UTF-8//IGNORE",$db_field);
            //$db_field = mb_convert_encoding($db_field , 'UTF-8', 'UTF-8');
            // $db_field = preg_replace(/[^\x0A\x20-\x7E]/,'',$db_field);
            $db_field = preg_replace('/[\x00-\x1F\x80-\x9F]/u', '', $db_field);
            $newArr[] = $db_field;
        }
        
        $end1 = round((microtime(true) - $start_timer_1), 2);
        echo "\n======elapsed time for MysqlQuery: $end1 seconds \n";
        $start_timer_1 = microtime(true);
        
        
        
        
        
        
        $jsonresults = json_encode($newArr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
        
        //$jsonresults = json_encode($newArr);
        
        /*
        echo var_dump($newArr);
        echo "\n\n\n";
        echo var_dump($jsonresults);
        sleep(60);
        
        */
        //die;
        /* Needed to Match Redshift JSON Format */
        $jsonresults    = substr($jsonresults, 1, -1);
        #$jsonresults=str_replace("'", "\'", $jsonresults);
        /*$jsonresults    = str_replace('},{', "}{", $jsonresults);
        $jsonresults    = str_replace('1st_', "first_", $jsonresults);
        $jsonresults    = str_replace('2nd_', "second_", $jsonresults);
        $jsonresults    = str_replace('3rd_', "third_", $jsonresults);
        $jsonresults    = str_replace('4th_', "forth_", $jsonresults);
        $jsonresults    = str_replace('5th_', "fifth_", $jsonresults);*/


        $jsonresults    = str_replace('0000-00-00 00:00:00','',$jsonresults);
        $jsonresults    = str_replace('0000-00-00','',$jsonresults);
        /* Needed to Match Redshift JSON Format */
        //$fp = fopen('files/results.json', 'w');
        //fwrite($fp, $jsonresults);
        //fclose($fp);
        //$OutputFilePath='files/results.json';
        $OutputFilePath='files/'.$mysqltbl.'.json';
        //$OutputFilePath = 'files/results.json';
        if (file_exists($OutputFilePath)) {
            unlink($OutputFilePath);
        }
        
        file_put_contents($OutputFilePath, $jsonresults);
        $end1 = round((microtime(true) - $start_timer_1), 2);
        echo "\n\n======elapsed time for JsonEncoding and Writing: $end1 seconds \n";
        
        
    }

?>
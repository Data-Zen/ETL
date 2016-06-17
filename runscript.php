<?php

				$start_timer_1 = microtime(true); 
include('GetMySQLData.php');
			    $end1 = round((microtime(true) - $start_timer_1),2);
				echo "\n=====================elapsed time for Mysql: $end1 seconds \n";
				$start_timer_1 = microtime(true); 
include('s3sdk.php');
			    $end1 = round((microtime(true) - $start_timer_1),2);
				echo "\n=====================elapsed time for S3: $end1 seconds \n";
				$start_timer_1 = microtime(true); 
include('RedshiftQueries.php');
			    $end1 = round((microtime(true) - $start_timer_1),2);
				echo "\n=====================elapsed time for Redshift push: $end1 seconds \n";

?>
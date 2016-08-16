<?php


        $execstring = "ps aux | grep php";
        
        $output       = "";
        $return_value = "";
        exec($execstring, $output, $return_value);
        var_dump($output) ;
        echo count($output);

?>



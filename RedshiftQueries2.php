<?php
$debug = 1;
date_default_timezone_set('UTC'); //or change to whatever timezone you want
echo "\n\n*******Running RedshiftQueries2.php*************\n\n";

include 'credentials/PBBCredentials.php';
$connect = pg_connect($PBBModifyCredentials);

if ($offset == 0) {
    $sql = "truncate table " . $mysqltbl;
    
    if ($debug == 1) {
        echo "\n*******StartQuery\n" . $sql . "\n*******EndQuery\n";
    }
    $rec          = pg_query($connect, $sql);
    $rowsaffected = pg_affected_rows($rec);
    echo "Rows affected $rowsaffected \n\n";
}

$sql = "copy $mysqltbl" . " from 's3://pbb-redshift/$mysqltbl.csv' CREDENTIALS 'aws_access_key_id=$AWS_ACCESS_KEY_ID;aws_secret_access_key=$AWS_SECRET_ACCESS_KEY' csv ;";

if ($debug == 1) {
    echo "\n*******StartQuery\n" . $sql . "\n*******EndQuery\n";
}


$rec = pg_query($connect,$sql);
$rowsaffected=pg_affected_rows($rec);
$rs_rowsaffected = $mysqlaffectedrows;

echo "Rows affected $rowsaffected \n\n";
/*
exit;



$sql=$rs_delete_qry;

if ($debug==1)
{
echo "\n*******StartQuery\n".$sql."\n*******EndQuery\n";
}
$rec = pg_query($connect,$sql);
$rowsaffected=pg_affected_rows($rec);
echo "Rows affected $rowsaffected \n\n";





$sql=$rs_insert_from_stage_qry;

if ($debug==1)
{
echo "\n*******StartQuery\n".$sql."\n*******EndQuery\n";
}
//$rowsaffected=1;
//while ($rowsaffected > 0) {
$rec = pg_query($connect,$sql);
$rowsaffected=pg_affected_rows($rec);
echo "Rows affected $rowsaffected \n\n";
//}





*/

?>

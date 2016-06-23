<?php
$debug=1;
date_default_timezone_set('UTC');//or change to whatever timezone you want
echo "\n\n*******Running RedshiftTruncateQueries.php*************\n\n";

include 'credentials/PBBCredentials.php';
$connect = pg_connect($PBBModifyCredentials);

$sql="truncate table ".$processname."_dev;";

if ($debug==1)
{
echo "\n*******StartQuery\n".$sql."\n*******EndQuery\n";
}

$rec = pg_query($connect,$sql);
$rowsaffected=pg_affected_rows($rec);
echo "Rows affected $rowsaffected \n\n";









?>

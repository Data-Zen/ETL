<?php
$debug=1;
date_default_timezone_set('UTC');//or change to whatever timezone you want
echo "\n\n*******Running RedshiftQueries.php*************\n\n";

include 'credentials/PBBCredentials.php';
$connect = pg_connect($PBBModifyCredentials);

$sql="truncate table ".$processname."_staging;";

if ($debug==1)
{
echo "\n*******StartQuery\n".$sql."\n*******EndQuery\n";
}
$rec = pg_query($connect,$sql);
$rowsaffected=pg_affected_rows($rec);
echo "Rows affected $rowsaffected \n\n";


$sql="copy $processname"."_staging from 's3://pbb-redshift/$processname.json' CREDENTIALS 'aws_access_key_id=$AWS_ACCESS_KEY_ID;aws_secret_access_key=$AWS_SECRET_ACCESS_KEY' json 'auto' ;";

if ($debug==1)
{
echo "\n*******StartQuery\n".$sql."\n*******EndQuery\n";
}
$rec = pg_query($connect,$sql);
$rowsaffected=pg_affected_rows($rec);
echo "Rows affected $rowsaffected \n\n";



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







?>

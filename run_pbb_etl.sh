
#! /bin/bash
SHELL=/bin/bash
date

proc_name=$1
     if [ -z "$proc_name" ];
      then
      	
      	echo -e "\n\n\n\nQuitting!  No Procedure Name Passed.\n\n\n\n"
      	rm -rf $lockpath
        exit 100
      fi  
lockpath="/tmp/pbb_CurrenltyProcessing_etl_$proc_name"
if mkdir $lockpath; then
  echo "Starting Script" >&2
else
	echo -e "\n\n\n\nScript Already running. Lock Creation Failed failed - exit
  If you think this is an error try running: ' rm -rf  $lockpath '\n\n\n\n"
  exit 1
fi

START_TIME=$SECONDS
if [[ $(hostname -s) = *paul* ]]; then
    MyPath="/home/pkats/scripts/pbb_etl"
  else
    MyPath="/home/pkats/scripts/pbb_etl"
fi

cd $MyPath
  
php ./runscript_Loop.php $proc_name

rm -rf $lockpath

ELAPSED_TIME=$(($SECONDS - $START_TIME))

let ELAPSED_TIME_Minutes=$ELAPSED_TIME/60

echo "ELAPSED_TIME: $ELAPSED_TIME Seconds" ;
echo "ELAPSED_TIME: $ELAPSED_TIME_Minutes Minutes" ;
date
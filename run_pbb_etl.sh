
#! /bin/bash
SHELL=/bin/bash
date
if mkdir /tmp/pbb_etl; then
  echo "Starting Script" >&2
else
	echo -e "\n\n\n\nScript Already running. Lock Creation Failed failed - exit
  If you think this is an error try running: ' rm -rf /tmp/pbb_etl '\n\n\n\n"
  exit -1
fi

START_TIME=$SECONDS
if [[ $(hostname -s) = *paul* ]]; then
    MyPath="/home/pkats/scripts/pbb_etl"
  else
    MyPath="/home/pkats/scripts/pbb_etl"
fi

cd $MyPath
  
proc_name=$1
     if [ -z "$proc_name" ];
      then
      	
      	echo -e "\n\n\n\nQuitting!  No Procedure Name Passed.\n\n\n\n"
      	rm -rf /tmp/pbb_etl
        exit -100
      fi  
php ./runscript_Loop.php $proc_name

rm -rf /tmp/pbb_etl

ELAPSED_TIME=$(($SECONDS - $START_TIME))

let ELAPSED_TIME_Minutes=$ELAPSED_TIME/60

echo "ELAPSED_TIME: $ELAPSED_TIME Seconds" ;
echo "ELAPSED_TIME: $ELAPSED_TIME_Minutes Minutes" ;
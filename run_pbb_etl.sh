
#! /bin/bash
SHELL=/bin/bash
date
if mkdir /tmp/pbb_etl; then
  echo "Running Script" >&2
else
  echo "Script Already running. Lock Creation Failed failed - exit
  If you think this is an error try running: ' rm -rf /tmp/pbb_etl '" >&2
  exit 1
fi

START_TIME=$SECONDS
if [[ $(hostname -s) = *paul* ]]; then
    MyPath="/home/pkats/scripts/pbb_etl"
  else
    MyPath="/home/pkats/scripts/pbb_etl"
fi

cd $MyPath
  
php ./runscript_Loop.php 

rm -rf /tmp/pbb_etl

ELAPSED_TIME=$(($SECONDS - $START_TIME))

let ELAPSED_TIME_Minutes=$ELAPSED_TIME/60
echo "\n\nELAPSED_TIME in SECONDS for BC:" $ELAPSED_TIME;
echo "\n\nELAPSED_TIME in SECONDS for BC:" $ELAPSED_TIME_Minutes;

#! /bin/bash
SHELL=/bin/bash
while true
do

  date

  START_TIME=$SECONDS
  if [[ $(hostname -s) = *paul* ]]; then
      MyPath="/home/pkats/scripts/pbb_etl"
    else
      MyPath="/home/pkats/scripts/pbb_etl"
  fi

  cd $MyPath

  START_TIME2=$SECONDS
  echo "Now Running: ReplicationWait" ;
  php ./Replicatiom_wait.php 
  ELAPSED_TIME2=$(($SECONDS - $START_TIME2))
  echo "ELAPSED_TIME: $ELAPSED_TIME2 Seconds" ;
  START_TIME2=$SECONDS




  START_TIME2=$SECONDS
  echo "Now Running: geodesic_classifieds_categories_languages" ;
  /home/pkats/scripts/pbb_etl/run_pbb_etl.sh geodesic_classifieds_categories_languages  >/tmp/geodesic_classifieds_categories_languages.log 2>&1
  ELAPSED_TIME2=$(($SECONDS - $START_TIME2))
  echo "ELAPSED_TIME: $ELAPSED_TIME2 Seconds" ;
  START_TIME2=$SECONDS
  date
  echo "Now Running: geodesic_userdata" ;

  /home/pkats/scripts/pbb_etl/run_pbb_etl.sh geodesic_userdata  >/tmp/geodesic_userdata.log 2>&1
  ELAPSED_TIME2=$(($SECONDS - $START_TIME2))
  echo "ELAPSED_TIME: $ELAPSED_TIME2 Seconds" ;
  START_TIME2=$SECONDS
  date
  echo "Now Running: breed_inventory" ;

  /home/pkats/scripts/pbb_etl/run_pbb_etl.sh breed_inventory  >/tmp/breed_inventory.log 2>&1
  ELAPSED_TIME2=$(($SECONDS - $START_TIME2))
  echo "ELAPSED_TIME: $ELAPSED_TIME2 Seconds" ;
  START_TIME2=$SECONDS
  date
  echo "Now Running: geodesic_classifieds" ;
  /home/pkats/scripts/pbb_etl/run_pbb_etl.sh geodesic_classifieds  >/tmp/geodesic_classifieds.log 2>&1

  ELAPSED_TIME2=$(($SECONDS - $START_TIME2))
  echo "ELAPSED_TIME: $ELAPSED_TIME2 Seconds" ;
  START_TIME2=$SECONDS
  date
  echo "Now Running: geodesic_classifieds_archive" ;

  /home/pkats/scripts/pbb_etl/run_pbb_etl.sh geodesic_classifieds_archive  >/tmp/geodesic_classifieds_archive.log 2>&1

  ELAPSED_TIME2=$(($SECONDS - $START_TIME2))
  echo "ELAPSED_TIME: $ELAPSED_TIME2 Seconds" ;
  START_TIME2=$SECONDS
  date
  echo "Now Running: leads" ;

  /home/pkats/scripts/pbb_etl/run_pbb_etl.sh leads  >/tmp/leads.log 2>&1

  ELAPSED_TIME2=$(($SECONDS - $START_TIME2))
  echo "ELAPSED_TIME: $ELAPSED_TIME2 Seconds" ;

  START_TIME2=$SECONDS
  date
  echo "Now Running: form_sources" ;

  /home/pkats/scripts/pbb_etl/run_pbb_etl.sh form_sources  >/tmp/form_sources.log 2>&1

  ELAPSED_TIME2=$(($SECONDS - $START_TIME2))
  echo "ELAPSED_TIME: $ELAPSED_TIME2 Seconds" ;
  START_TIME2=$SECONDS
  date
  echo "Now Running: contact" ;

  /home/pkats/scripts/pbb_etl/run_pbb_etl.sh contact  >/tmp/contact.log 2>&1


  ELAPSED_TIME2=$(($SECONDS - $START_TIME2))
  echo "ELAPSED_TIME: $ELAPSED_TIME2 Seconds" ;
   

  ELAPSED_TIME=$(($SECONDS - $START_TIME))

  let ELAPSED_TIME_Minutes=$ELAPSED_TIME/60

  echo "Total ELAPSED_TIME: $ELAPSED_TIME Seconds" ;
  echo "Total ELAPSED_TIME: $ELAPSED_TIME_Minutes Minutes" ;
  date

done
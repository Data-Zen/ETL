#!/bin/bash

CHARSET="utf-8" #your current database charset
DATADIR="/files"
DBNAME="buypuppy_manager"

for file in `ls -1 $DATADIR/*.txt`; do
  TMP=${file%.*}
  TABLE=${TMP##*/}
  echo "preparing $TABLE"

  #replace carriage return
  sed 's/\r/\\r/g' $file > /tmp/$TABLE.export.tmp

  #cleanup non-printable and wrong sequences for current charset
  iconv -t $CHARSET -f $CHARSET -c < /tmp/$TABLE.export.tmp > /tmp/$TABLE.export.tmp.out

  echo "loading $TABLE"
  /usr/bin/psql $DBNAME -c "copy $TABLE from '/tmp/$TABLE.export.tmp.out'"

  #clean up
  rm /tmp/$TABLE.export.tmp /tmp/$TABLE.export.tmp.out
done
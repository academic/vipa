#!/bin/bash
IDS=dergipark.csv

contents=()
i=0
while read id
do
    #echo "Adding $id"
    #php ../app/console ojs:import:journal $id
    i=$((i+5))
    $contents[$i]=$id
done < $IDS
echo $id[*]
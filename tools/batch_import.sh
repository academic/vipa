#!/bin/bash
IDS=dergipark.csv

contents=()
i=0
while read id
do
    echo "Adding $id"
    php ../app/console ojs:import:journal $id
    i=$((i+1))
    contents[$i]=$id
    res=$[$i%10]
    if [ $res  -eq "0" ]; then
        echo "\nwaiting...\n"
        sleep 1m
    fi
done < $IDS

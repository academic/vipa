#!/bin/bash
IDS=dergipark.csv

contents=()
i=0
while read id
do
    echo "Adding $id stats"
    php dpstastImporter.php $id root:root@10.61.11.29/dergipark root:root@10.61.11.29/dpstats ojs 10.61.11.29
    i=$((i+1))
    contents[$i]=$id
    res=$[$i%10]
    if [ $res  -eq "0" ]; then
        echo "\nwaiting...\n"
        sleep 10
    fi
done < $IDS

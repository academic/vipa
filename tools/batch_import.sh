#!/bin/bash
IDS=dergipark.csv

while read id
do
    echo "Adding $id"
    php ../app/console ojs:import:journal $id
done < $IDS
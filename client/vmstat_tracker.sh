#!/bin/bash
#/##############################################################################
 #
 # vmstat_tracker.sh - Get OS data from vmstat command and generate JSON output.
 #
 # Program: ctracker
 # License: GPL
 #
 # First Written:   2012
 # Copyright (C) 2012-2013 - Author: Matheus SantAna Lima <matheusslima@yahoo.com.br>
 #
 # Description:
 #
 # License:
 #
 #   This program is free software: you can redistribute it and/or modify
 #   it under the terms of the GNU General Public License as published by
 #   the Free Software Foundation, either version 3 of the License, or
 #   (at your option) any later version.

 #   This program is distributed in the hope that it will be useful,
 #   but WITHOUT ANY WARRANTY; without even the implied warranty of
 #   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 #   GNU General Public License for more details.

 #   You should have received a copy of the GNU General Public License
 #   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 #############################################################################/

sentence=`vmstat -SM 1 2 | column -t | grep [0-9] > vmstat_output.txt`
sentence=`tail -n1 vmstat_output.txt`
#sentence=$1
count=0
#count=$(($count + 1))
#count=$(($count + 1))
#echo $sentence
for word in $sentence
do
#	if [ "$count" -eq "0" ]; then


#		   echo $word
		vet[$count]=$word;
#		echo $vet
		count=$(($count + 1))

#	fi



#		echo 'count: '$count
done

date_string=`date -u +"%Y %m %d %T %Z"`
#date_string=`date -u`
#echo $date_string
count_date=0
for date in $date_string
do
	date_vet[$count_date]=$date
#	echo $date
	count_date=$(($count_date + 1))
done
#echo ${date_vet[3]}

echo  '{
     "timestamp":1,
     "InputDataName": "vmstat",
	"date":
	{
	"day":"'${date_vet[2]}'",
	"month":"'${date_vet[1]}'",
	"time":"'${date_vet[3]}'",
	"year":"'${date_vet[0]}'",
	"type":"'${date_vet[4]}'"
	},

    "procs"  :
     {
         "r": "'${vet[0]}'",
         "b": "'${vet[1]}'"
     },
     "memory"  :
     {
         "swpd": "'${vet[2]}'",
         "free": "'${vet[3]}'",
         "buff": "'${vet[4]}'",
         "cache": "'${vet[5]}'"
     },
     "swap"  :
     {
         "si": "'${vet[6]}'",
         "so": "'${vet[7]}'"
     },

     "io"  :
     {
         "bi": "'${vet[8]}'",
         "bo": "'${vet[9]}'"
     },
     "system"  :
     {
         "in": "'${vet[10]}'",
         "cs": "'${vet[11]}'"
     },
     "cpu"  :
     {
         "us": "'${vet[12]}'",
         "sy": "'${vet[13]}'",
         "id": "'${vet[14]}'",
         "wa": "'${vet[15]}'"
     },'
#network

#device list
#sar  -n DEV 1 1 | grep -v Average | tail -n +4 | column -t | awk '{print $(NF-3)}
ethx=`sar -n DEV 1 1 | grep -v Average | tail -n +4 | column -t > /tmp/ethx.tmp`;
ethx=`cat /tmp/ethx.tmp | awk '{print $(NF-7)}'`;
ethx_vet=($ethx);
size=${#ethx_vet[@]};
size=$((size-1));
counter=0;
for eth in $ethx; do
        eth_array="$eth_array \"$eth\"";
        if [[ "$counter" -ne ${size} ]] ; then
               counter=$((counter+1))
               eth_array="$eth_array, ";
        fi
done

#download
ethx=`cat /tmp/ethx.tmp | awk '{print $(NF-4)}'`;
ethx_vet=($ethx);
size=${#ethx_vet[@]};
size=$((size-1));
counter=0;
rx_sum=0.0;
for rx in $ethx; do
	rx=${rx//,/.};
        rx_array="$rx_array \"$rx\"";
	rx_sum=$(python -c "print $rx_sum+$rx")
        if [[ "$counter" -ne ${size} ]] ; then
               counter=$((counter+1))
               rx_array="$rx_array, ";
        fi
done

#upload
ethx=`cat /tmp/ethx.tmp | awk '{print $(NF-3)}'`;
ethx_vet=($ethx);
size=${#ethx_vet[@]};
size=$((size-1));
counter=0;
tx_sum=0.0;
for tx in $ethx; do
	tx=${tx//,/.};
        tx_array="$tx_array \"$tx\"";
	tx_sum=$(python -c "print $tx_sum+$tx")
        if [[ "$counter" -ne ${size} ]] ; then
               counter=$((counter+1))
               tx_array="$tx_array, ";
        fi
done


echo '
     "network":{
	"device":['$eth_array'],
	"rxkBs":['$rx_array'],
	"rxSum":"'$rx_sum'",
	"txkBs":['$tx_array'],
	"txSum":"'$tx_sum'"
	},
';

echo  '"process":{
		"plist":['
	./process_tracker.sh
echo ']},
'
echo  '"hardware":{'
	./hardware_tracker.sh
echo '},
'

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

sentence=`vmstat 1 2 | column -t | grep [0-9] > vmstat_output.txt`
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

date_string=`date -u`
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
     "InputDataName": "vmstat",
     "argSize": "6",
     "argName":["procs","memory","swap", "io", "system", "cpu"],
	"date":
	{
	"day_name":"'${date_vet[0]}'",
	"day":"'${date_vet[2]}'",
	"month":"'${date_vet[1]}'",
	"time":"'${date_vet[3]}'",
	"year":"'${date_vet[5]}'",
	"type":"'${date_vet[4]}'"
	},

    "procs"  :
     {
	"argOpSize":"2",
	"argOp":["r","b"],
         "r": "'${vet[0]}'",
         "b": "'${vet[1]}'"
     },
     "memory"  :
     {
	"argOpSize":"4",
	"argOp":["swp","free", "buff", "cache"],
         "swpd": "'${vet[2]}'",
         "free": "'${vet[3]}'",
         "buff": "'${vet[4]}'",
         "cache": "'${vet[5]}'"
     },
     "swap"  :
     {
	"argOpSize":"2",
	"argOp":["si","so"],
         "si": "'${vet[6]}'",
         "so": "'${vet[7]}'"
     },

     "io"  :
     {
	"argOpSize":"2",
	"argOp":["bi","bo"],
         "bi": "'${vet[8]}'",
         "bo": "'${vet[9]}'"
     },
     "system"  :
     {
	"argOpSize":"2",
	"argOp":["in","cs"],
         "in": "'${vet[10]}'",
         "cs": "'${vet[11]}'"
     },
     "cpu"  :
     {
	"argOpSize":"4",
	"argOp":["us","sy", "id", "wa"],
         "us": "'${vet[12]}'",
         "sy": "'${vet[13]}'",
         "id": "'${vet[14]}'",
         "wa": "'${vet[15]}'"
     },

'

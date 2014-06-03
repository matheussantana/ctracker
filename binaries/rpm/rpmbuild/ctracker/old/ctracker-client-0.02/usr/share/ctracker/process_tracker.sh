#!/bin/bash

PATH=/opt/someApp/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin

CONFIG_FOLDER="ctracker.d/";

#plist=$(top -b -d 0 -n 1 -p $1 | tail -n2;);

#read p;
#plist=$(ps -ef | grep '$p' | head -n -1 |  awk '{print $2}');
#plist=$(pgrep $1);
#for pid in $plist; do
#pid=$1;
#	ps -p $pid -o %cpu,%mem,etime,cmd | tail -n +2

count=0;
function print_output(){

	#check if there is any process with the given $pattern using any CPU rigth now.
	pidstat_list=$(pidstat 1 1 -l | grep -v grep | grep $pattern);
#echo "$pidstat_list"
	if [[ ! -z $pidstat_list ]]
	then


		pid_list=$(echo "$pidstat_list" | awk '{print $(NF-6)}');
		cpu_list=$(echo "$pidstat_list" | awk '{print $(NF-2)}');
#	top -b -d 0 -n 1 -p $pid | grep $pid > /tmp/procinfo;
#	cpu=$(cat /tmp/procinfo | awk '{print $9}');
#	mem=$(cat /tmp/procinfo | awk '{print $10}');
#	etime=$(ps -p $ppid -o etime | grep -v ELAPSED| tr -d ' ');
		pid_array=($pid_list);
		cpu_array=($cpu_list);
	index=0;
	for i in "${pid_array[@]}"
	do
		ppid=${pid_array[$index]};
		pmem=$(pmap -x $ppid | grep total | column -t | awk '{print $3}')
		etime=$(ps -p $ppid -o etime | grep -v ELAPSED| tr -d ' ');

		pcpu=${cpu_array[$index]};
		pcpu=${pcpu//,/.};

		json[$count]="{\"name\": \"$name\", \"status\": \"Running\", \"pid\": \"$ppid\", \"cpu\":\"$pcpu\",  \"mem\": \"$pmem\", \"time\":\"$etime\"}"
		index=$((index+1));
		count=$((count+1));
	done	
	else
		pmem=$(pmap -x $pid | grep total | column -t | awk '{print $3}')
		etime=$(ps -p $pid -o etime | grep -v ELAPSED| tr -d ' ');
		json[$count]="{\"name\": \"$name\", \"status\": \"Running\", \"pid\": \"$pid\", \"cpu\":\"0.0\",  \"mem\": \"$pmem\", \"time\":\"$etime\"}"
	        count=$((count+1));

	fi




}


processconfiglist=$(ls $CONFIG_FOLDER);
for config in $processconfiglist; do

	#reading config file
	. $CONFIG_FOLDER/$config
#	pid=$(cat $path)
#	pid=$(ps aux | grep $pattern | grep -v "grep ${pattern}" |  awk "{print $2}");

#logger "check if pid exists";

	#check if pid exists
	if [ "$enable" == 0 ]; then
		json[$count]="{\"name\": \"$name\", \"status\": \"Disabled\"}"
		count=$((count+1));
	else
		#check if there is any process running under the $pattern value.

		pid=$(ps aux | grep "$pattern" | grep -v "grep ${pattern}" | head -n 1|  awk '{print $2}');
#cho $pid
#logger $pid;
		if [ ! -d "/proc/$pid/" ] || [[ -z "$pid" ]]; then
#logger $action
			#take action
			#echo 'action';
			{ $action | logger; } &
			wait
			pid=$(ps aux | grep "$pattern" | grep -v "grep ${pattern}" |  head -n 1 | awk '{print $2}');
#echo $pid
			if [ ! -d "/proc/${pid}/" ] || [[ -z "${pid}" ]]; then

			#echo $pid > $path

				 json[$count]="{\"name\": \"$name\", \"status\": \"Stopped\"}"
				count=$((count+1));
			else
				print_output
			fi

		else
#			top -b -d 0 -n 1 -p $pid | tail -n2 > /tmp/procinfo;
#			cpu=$(cat /tmp/procinfo | awk '{print $9}');
#			mem=$(cat /tmp/procinfo | awk '{print $10}');
#			etime=$(cat /tmp/procinfo | awk '{print $11}');
#			echo $name $pid CPU:$cpu  MEM: $mem TIME:$etime
			print_output
		fi
	fi
	#dplist="$plist  $tmp";

done

function json_format(){
	i=0;
	size=${#json[@]};

	if [ "$size" -gt 0 ]
	then
		size=$((size-1));
		while [ $i != $size ]
		do

			json[$i]=${json[$i]}","
			(( i++ ))
		json[$i]=${json[$i]}
		done
		echo ${json[*]}
	fi

}





json_format

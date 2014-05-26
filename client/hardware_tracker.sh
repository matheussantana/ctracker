#!/bin/bash

#To find the number of physical CPUs:
NUMBER_PHYSICAL=$(cat /proc/cpuinfo | grep "^physical id" | sort | uniq | wc -l | sed -e 's/^[ \t]*//');
#To find the number of cores per CPU:
NUMBER_CORES=$(cat /proc/cpuinfo | grep "^cpu cores" | uniq | cut -f2 -d: | sed -e 's/^[ \t]*//');
#To find the total number of processors:
NUMBER_PROCESSOR=$(cat /proc/cpuinfo | grep "^processor" | wc -l| sed -e 's/^[ \t]*//');
MODEL=$(cat /proc/cpuinfo  | grep "model name" | uniq | cut -f2 -d:| sed -e 's/^[ \t]*//');
CACHE_SIZE=$(cat /proc/cpuinfo  | grep "cache size" | uniq |  awk '{print $4}'| sed -e 's/^[ \t]*//');
FREQ_CPU=$(cat /proc/cpuinfo  | grep "cpu MHz" | uniq |  cut -f2 -d:| sed -e 's/^[ \t]*//');


echo '"cpu":{';
echo '	"physical_cpus": '$NUMBER_PHYSICAL,;
echo '	"cores_per_cpu": '$NUMBER_CORES,;
echo '	"number_of_processors": '$NUMBER_PROCESSOR,;
echo '	"model_name": '\"$MODEL\",;
echo '	"cache_size_kb": '$CACHE_SIZE,;
echo '	"freq_cpu": '\"$FREQ_CPU\";

echo '},';

TOTAL_MEMORY=$(free -m | sed  -n -e '/^Mem:/s/^[^0-9]*\([0-9]*\) .*/\1/p' | sed -e 's/^[ \t]*//');

echo '"mem":{';
echo '	"size_mb": '$TOTAL_MEMORY;
echo '},'


df -Ph|column -t | grep -v 'Filesystem' > /tmp/fs

FS_LIST=$(cat /tmp/fs | awk '{print $1}');
FS_SIZE=$(cat /tmp/fs | awk '{print $2}');
FS_USED=$(cat /tmp/fs | awk '{print $3}');
FS_AVAIL=$(cat /tmp/fs | awk '{print $4}');
FS_PCT=$(cat /tmp/fs | awk '{print $5}');
FS_MOUNT=$(cat /tmp/fs | awk '{print $6}');

size=0;
for list in $FS_LIST
do
	size=$((size+1))
done
size=$((size-1));
index=0;

tmp_list="";
for list in $FS_LIST
do
	list=${list//,/.};


	if [ "$index" != "$size" ]
	then
		tmp_list=$tmp_list\"$list\"', '
		index=$((index+1))
	else
		tmp_list=$tmp_list\"$list\"
	fi
done
index=0;
tmp_size="";
for list in $FS_SIZE
do

	list=${list//,/.};

	if [ "$index" != "$size" ]
	then
		tmp_size=$tmp_size\"$list\"', '
		index=$((index+1))
	else
		tmp_size=$tmp_size\"$list\"
	fi
done
index=0;
tmp_used="";
for list in $FS_USED
do

        list=${list//,/.};

        if [ "$index" != "$size" ]
        then
                tmp_used=$tmp_used\"$list\"', '
                index=$((index+1))
        else
                tmp_used=$tmp_used\"$list\"
        fi
done
index=0;
tmp_avail="";
for list in $FS_AVAIL
do

        list=${list//,/.};

        if [ "$index" != "$size" ]
        then
                tmp_avail=$tmp_avail\"$list\"', '
                index=$((index+1))
        else
                tmp_avail=$tmp_avail\"$list\"
        fi
done
index=0;
tmp_pct="";
for list in $FS_PCT
do

        list=${list//,/.};

        if [ "$index" != "$size" ]
        then
                tmp_pct=$tmp_pct\"$list\"', '
                index=$((index+1))
        else
                tmp_pct=$tmp_pct\"$list\"
        fi
done
index=0;
tmp_mount="";
for list in $FS_MOUNT
do

        list=${list//,/.};

        if [ "$index" != "$size" ]
        then
                tmp_mount=$tmp_mount\"$list\"', '
                index=$((index+1))
        else
                tmp_mount=$tmp_mount\"$list\"
        fi
done
echo '"fs":{

	"device": ['$tmp_list'],
	"size": ['$tmp_size'],
	"avail": ['$tmp_avail'],
	"pct": ['$tmp_pct'],
	"used": ['$tmp_used'],
	"mount": ['$tmp_mount']

},';

DISTRO=$(lsb_release -i | cut -f2 -d:| sed -e 's/^[ \t]*//');
RELEASE=$(lsb_release -r | cut -f2 -d:| sed -e 's/^[ \t]*//');
CODENAME=$(lsb_release -c | cut -f2 -d:| sed -e 's/^[ \t]*//');


echo '"os":{
	"distro": "'$DISTRO'",
	"release": "'$RELEASE'",
	"codename": "'$CODENAME'"
}';

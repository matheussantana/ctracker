Ctracker 0.01 beta README
=======================

Ctracker is a free and open source web monitoring tool used to keep track of your remote linux machines in real time.
You can get many(today just CPU, RAM memory, Disk and Swap) metrics that allow you to see how much your servers are consuming.
This kind of tool is specially important in a cloud-like environment(for both public or private).


###### Features:


Graphs:

		1. CPU - id: Time spent idle(Percentage);
		2. Memory - free;
		3. Disk - bo: Blocks sent to a block device (blocks/s);
		4. Swap - so: Amount of memory swapped to disk (/s);


Dump:

Currently you can get the same data as you should with vmstat tool:
	
```
   Procs
       r: The number of processes waiting for run time.
       b: The number of processes in uninterruptible sleep.


   Memory
       swpd: the amount of virtual memory used.
       free: the amount of idle memory.
       buff: the amount of memory used as buffers.
       cache: the amount of memory used as cache.
       inact: the amount of inactive memory. (-a option)
       active: the amount of active memory. (-a option)


   Swap
       si: Amount of memory swapped in from disk (/s).
       so: Amount of memory swapped to disk (/s).


   IO
       bi: Blocks received from a block device (blocks/s).
       bo: Blocks sent to a block device (blocks/s).


   System
       in: The number of interrupts per second, including the clock.
       cs: The number of context switches per second.

   CPU
       These are percentages of total CPU time.
       us: Time spent running non-kernel code. (user time, including nice time)
       sy: Time spent running kernel code. (system time)
       id: Time spent idle. Prior to Linux 2.5.41, this includes IO-wait time.
       wa: Time spent waiting for IO. Prior to Linux 2.5.41, shown as zero.
```


###### Architecture:

	1) client - Collect OS data consumption and sends it to the server;
	2) frontend - Display the collected data in a time graphic generated in real time or with a table sorter;
	3) webservice - Retrieves the data(JSON string) from clients;

###### Instalation & Compilation:

	
	Please read the INSTALLATION file.	I don't have binaries packages for the ctracker client just yet - Stay tunned =).


###### evelopment:

	ctracker is developed in several languages depending of the task that need to be done.
	For the frontend I have used PHP with MongoDB to stores the massive amount of JSON massages sent by the clients;
	Also, we use a small MySQL database to manage the users and the instances(phisical or virtual machines) - add/edit/update/remove;

	Regarding the client I have used both C++ and Shell Bash script(Used to execute the vmstat unix tool);


###### Dependencies:

	Client:

		1) cURL: libcurl3.

	Server:

		1) Apache, PHP, MySQL and MongoDB.

###### License

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

###### More info:

You can find some very useful information regarding the data collected with the vmstat man page or simple go to the Wikipedia to some quick explanation:

[Wikipedia - vmstat](http://en.wikipedia.org/wiki/Vmstat)

Visit the ctracker homepage at https://github.com/matheussantana/ctracker for online documentation, new releases, bug reports, information on the mailing lists, etc.

-- Matheus SantAna (matheusslima@yahoo.com.br)

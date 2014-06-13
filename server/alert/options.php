<? ob_start(); ?>
<?php
/******************************************************************************
 *
 * vmstat.php - Show stored OS data in a table form.
 *
 * Program: ctracker
 * License: GPL
 *
 * First Written:   2012
 * Copyright (C) 2012-2013 - Author: Matheus SantAna Lima <matheusslima@yahoo.com.br>
 *
 * Description:
 *
 * License:
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.

 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.

 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *****************************************************************************/
session_start();

require '../jsonwrapper/jsonwrapper.php';
include '../mongoconnection.php';
include '../mongofunctions.php';
include "../mysqlconnection.php";
include_once "../Archive/functions.php";
$itoken = safe($_GET['itoken']);
if (isset($_SESSION['email']) == false || isInstanceOwner($_SESSION['email'],$itoken) == false) {//check if user is login;
    echo "<script>window.location='../../Archive/index.php';</script>";
	die();
}


$query="SELECT * FROM instance_alert as a,instance as b WHERE a.instanceID = b.instanceID and b.email='".$_SESSION["email"]."' AND a.instanceID='$itoken'";
$inst_query = mysql_query($query);
$num_rows = mysql_num_rows($inst_query);
if($num_rows < 1){

	// execute query
	// retrieve all documents
	$criteria = array('InstanceToken' => $itoken);

	$end = getTimestampByIndex(-1, $collection, $criteria);//get the last document inserted;
        //              $cursor = $collection->find(array('InstanceToken' => array('$in' => $inst_stack) ,"timestamp" => $end));
	if($end == '{}'){
		echo 'No data found.';
		exit;}

	$cursor = $collection->find(array('InstanceToken' => $itoken ,"timestamp" => $end));
	$obj=$cursor->getNext();
//var_dump($obj);
//echo $obj['network']['rxSum'];
	//$cursor = $collection->find($criteria);i
	//$cursor->sort(array('time' => 1));
	$proc_r = $obj['procs']['r'];
	$proc_b = $obj['procs']['b'];
	$mem_swpd = $obj['memory']['swpd'];
	$mem_free = $obj['memory']['free'];
	$mem_buff = $obj['memory']['buff'];
	$mem_cache = $obj['memory']['cache'];
	$swap_si = $obj['swap']['si'];
	$swap_so = $obj['swap']['so'];
	$io_bi = $obj['io']['bi'];
	$io_bo = $obj['io']['bo'];
	$system_in = $obj['system']['in'];
	$system_cs = $obj['system']['cs'];
	$cpu_us = $obj['cpu']['us'];
	$cpu_sy = $obj['cpu']['sy'];
	$cpu_id = $obj['cpu']['id'];
	$cpu_wa =  $obj['cpu']['wa'];
	$net_rxSum = $obj['network']['rxSum'];
	$net_txSum = $obj['network']['txSum'];
	$process_status = 'Running';
/*	$process_cpu = $obj['process']['plist'][0]['cpu'];
	$process_mem = $obj['process']['plist'][0]['mem'];*/
	$process_cpu = "0.0";
	$process_mem = "0";
	$fs_used = "90%";
	$op_status = '1';
        $op_proc_r= 'greater';
        $op_proc_b= 'greater';
        $op_mem_swpd= 'greater';
        $op_mem_free= 'greater';
        $op_mem_buff= 'greater';
        $op_mem_cache= 'greater';
        $op_swap_si= 'greater';
        $op_swap_so= 'greater';
        $op_io_bi= 'greater';
        $op_io_bo= 'greater';
        $op_system_in= 'greater';
        $op_system_cs= 'greater';
        $op_cpu_us= 'greater';
        $op_cpu_sy= 'greater';
        $op_cpu_id= 'greater';
        $op_cpu_wa= 'greater';
        $op_net_rxSum= 'greater';
        $op_net_txSum= 'greater';
        $op_process_cpu= 'greater';
        $op_process_mem= 'greater';
        $op_fs_used= 'greater';
	$op_process_status = 'greater';

}else{

	$inst = mysql_fetch_array($inst_query);
	
	$proc_r = $inst['proc-r'];
	$proc_b = $inst['proc-b'];
	$mem_swpd = $inst['mem-swpd'];
	$mem_free = $inst['mem-free'];
	$mem_buff = $inst['mem-buff'];
	$mem_cache = $inst['mem-cache'];
	$swap_si = $inst['swap-si'];
	$swap_so = $inst['swap-so'];
	$io_bi = $inst['io-bi'];
	$io_bo = $inst['io-bo'];
	$system_in = $inst['system-in'];
	$system_cs = $inst['system-cs'];
	$cpu_us = $inst['cpu-us'];
	$cpu_sy = $inst['cpu-sy'];
	$cpu_id = $inst['cpu-id'];
	$cpu_wa = $inst['cpu-wa'];
	$net_rxSum = $inst['net-rxSum'];
	$net_txSum = $inst['net-txSum'];
	$process_status = $inst['process-status'];
	$process_cpu = $inst['process-cpu'];
	$process_mem = $inst['process-mem'];
	$fs_used = $inst['fs-used'];
	$op_status = $inst['option-status'];	
        $op_proc_r= $inst['op-proc-r'];
        $op_proc_b= $inst['op-proc-b'];
        $op_mem_swpd= $inst['op-mem-swpd'];
        $op_mem_free= $inst['op-mem-free'];
        $op_mem_buff= $inst['op-mem-buff'];
        $op_mem_cache= $inst['op-mem-cache'];
        $op_swap_si= $inst['op-swap-si'];
        $op_swap_so= $inst['op-swap-so'];
        $op_io_bi= $inst['op-io-bi'];
        $op_io_bo= $inst['op-io-bo'];
        $op_system_in= $inst['op-system-in'];
        $op_system_cs= $inst['op-system-cs'];
        $op_cpu_us= $inst['op-cpu-us'];
        $op_cpu_sy= $inst['op-cpu-sy'];
        $op_cpu_id= $inst['op-cpu-id'];
        $op_cpu_wa= $inst['op-cpu-wa'];
        $op_net_rxSum= $inst['op-net-rxSum'];
        $op_net_txSum= $inst['op-net-txSum'];
        $op_process_cpu= $inst['op-process-cpu'];
        $op_process_mem= $inst['op-process-mem'];
	$op_process_status=$inst['op-process-status'];
        $op_fs_used= $inst['op-fs-used'];

	
}
?>
	<link rel="stylesheet" href="../../Archive/controlpanel/css/styles.css" type="text/css" media="print, projection, screen" />


	<link rel="stylesheet" href="../../grid_js/themes/blue/style.css" type="text/css" media="print, projection, screen" />

<script type="text/javascript">
function convertTimestamp(timestamp) {
        //https://gist.github.com/kmaida/6045266
        var d = new Date(timestamp * 1000), // Convert to milliseconds
        yyyy = d.getFullYear(),
        mm = ("0" + (d.getMonth() + 1)).slice(-2),  // Months are zero based. Add leading 0.
        dd = ("0" + d.getDate()).slice(-2),         // Add leading 0.
        hh = d.getHours(),
        h = hh,
        min = ("0" + d.getMinutes()).slice(-2),     // Add leading 0.
        ampm = "AM",
        time;
        sec = ("0" + d.getSeconds()).slice(-2);     // Add leading 0. 
    if (hh > 12) {
        h = hh - 12;
        ampm = "PM";
    } else if (hh == 0) {
        h = 12;
    }
     
    // ie: 2013-02-18, 8:35 AM 
    time = yyyy + "-" + mm + "-" + dd + ", " + h + ":" + min +":"+sec+ " " + ampm;
         
    return time;
}
<?
function input($name, $min, $max, $value, $select){

/*	echo '<td>
		<input name="'.$name.'">';

       for ($i = $min; $i <= $max; $i++) {
              echo '<option value="'.$i.'">'.$i.'</option>';
       }

	echo '</input>
		</td>';*/
	echo '<td><input style="width: 30%;" type="text" name="'.$name.'" value='.$value.'>';
	echo '<select name="op-'.$name.'">
		<option value="greater"';
		
	if($select == 'greater')
		echo 'selected';

	echo '	>Greater than</option>';
	echo '	<option value="less"';

	if($select == 'less')
		echo 'selected';

	echo '>Less than</option>';
	echo '	<option value="equal"';

	if($select == 'equal')
		echo 'selected';

	echo '	>Equal to</option>';

	echo '	<option value="disabled"';

	if($select == 'disabled')
		echo 'selected';

	echo '>Disabled</option>
		</select></td>';

}
?>
</script>
<div style="width:700px;">
 <form action="../add.php">
	<input type="hidden" name="tp" value="add-alert"/>
	<input type="hidden" name="instanceID" value="<?echo $itoken;?>"/>
	<h2>Procs</h2>

	<table id="tablesorter-demo" class="tablesorter" border="0" cellpadding="0" cellspacing="1">
		<thead>
			<tr>
				<th>r</th>
				<th>b</th>
			</tr>
		</thead>
		<tbody>
		<tr>
		<?php input("proc-r",0,999,$proc_r,$op_proc_r);?>
		<?php input("proc-b",0,999,$proc_b,$op_proc_b);?>
		</tr>
		</tbody>
	</table>
	<h2>Memory</h2>

	<table id="tablesorter-demo" class="tablesorter" border="0" cellpadding="0" cellspacing="1">
		<thead>
			<tr>
				<th>swpd</th>
				<th>free</th>
				<th>buff</th>
				<th>cache</th>
			</tr>
		</thead>
                <tbody>
                <tr>
		<?php $max=9999;?>
                <?php input("mem-swpd",0,$max,$mem_swpd, $op_mem_swpd);?>
                <?php input("mem-free",0,$max,$mem_free, $op_mem_free);?>
                <?php input("mem-buff",0,$max,$mem_buff, $op_mem_buff);?>
                <?php input("mem-cache",0,$max,$mem_cache, $op_mem_cache);?>
                </tr>
                </tbody>

	</table>

	<h2>Swap</h2>

	<table id="tablesorter-demo" class="tablesorter" border="0" cellpadding="0" cellspacing="1">
		<thead>
			<tr>
				<th>si</th>
				<th>so</th>
			</tr>
		</thead>
                <tbody>
                <tr>
		<?php $max=9999;?>
                <?php input("swap-si",0,$max, $swap_si, $op_swap_si);?>
                <?php input("swap-so",0,$max, $swap_so, $op_swap_so);?>
                </tr>
                </tbody>

	</table>

	<h2>Disk</h2>

	<table id="tablesorter-demo" class="tablesorter" border="0" cellpadding="0" cellspacing="1">
		<thead>
			<tr>
				<th>bi</th>
				<th>bo</th>
			</tr>
		</thead>
                <tbody>
                <tr>
		<?php $max=9999;?>
                <?php input("io-bi",0,$max,$io_bi, $op_io_bi);?>
                <?php input("io-bo",0,$max,$io_bo, $op_io_bo);?>
                </tr>
                </tbody>

	</table>
	<h2>System</h2>

	<table id="tablesorter-demo" class="tablesorter" border="0" cellpadding="0" cellspacing="1">
		<thead>
			<tr>
				<th>in</th>
				<th>cs</th>
			</tr>
		</thead>
                <tbody>
                <tr>
		<?php $max=9999;?>
                <?php input("system-in",0,$max,$system_in, $op_system_in);?>
                <?php input("system-cs",0,$max,$system_cs, $op_system_cs);?>
                </tr>
                </tbody>

	</table>
	<h2>CPU</h2>

	<table id="tablesorter-demo" class="tablesorter" border="0" cellpadding="0" cellspacing="1">
		<thead>
			<tr>
				<th>us</th>
				<th>sy</th>
				<th>id</th>
				<th>wa</th>
			</tr>
		</thead>
                <tbody>
                <tr>
		<?php $max=100;?>
                <?php input("cpu-us",0,$max,$cpu_us, $op_cpu_us);?>
                <?php input("cpu-sy",0,$max,$cpu_sy, $op_cpu_sy);?>
                <?php input("cpu-id",0,$max,$cpu_id, $op_cpu_id);?>
                <?php input("cpu-wa",0,$max,$cpu_wa, $op_cpu_wa);?>
                </tr>
                </tbody>

	</table>

	<h2>Network</h2>

	<table id="tablesorter-demo" class="tablesorter" border="0" cellpadding="0" cellspacing="1">
		<thead>
			<tr>
				<th>rxSum</th>
				<th>txSum</th>
			</tr>
		</thead>
                <tbody>
                <tr>
		<?php $max=9999;?>
                <?php input("net-rxSum",0,$max,$net_rxSum, $op_net_rxSum);?>
                <?php input("net-txSum",0,$max,$net_txSum, $op_net_txSum);?>
                </tr>
                </tbody>

	</table>
	<h2>Process</h2>

	<table id="tablesorter-demo" sytle="width: 100px;" class="tablesorter" border="0" cellpadding="0" cellspacing="1">
		<thead>
			<tr>
				<th style="width: 200px;">Status</th>
				<th>CPU</th>
				<th>Mem</th>
			</tr>
		</thead>
                <tbody>
                <tr>
		<?php $max=100;?>
		<td><select  name="process-status">
			<option value="Stopped"<? if($process_status == "Stopped") echo 'selected';?>>Stopped</option> 
			<option value="Running" <? if($process_status == "Running") echo 'selected';?>>Running</option>
			<option value="Sleeping" <? if($process_status == "Sleeping") echo 'selected';?>>Sleeping</option>
		</select>
		<select name="op-process-status">

		    <option value="equal"> Equal </option>
		    <option value="disabled"> Disabled </option>

		</select>
		</td>
                <?php input("process-cpu",0,$max,$process_cpu,$op_process_cpu);?>
                <?php input("process-mem",0,$max,$process_mem,$op_process_mem);?>

                </tr>
                </tbody>

	</table>

	<h2>Filesystem</h2>

	<table id="tablesorter-demo" class="tablesorter" border="0" cellpadding="0" cellspacing="1">
		<thead>
			<tr>
				<th>Used(%)</th>
			</tr>
		</thead>
                <tbody>
                <tr>
		<?php $max=100;?>
                <?php input("fs-used",0,$max,$fs_used, $op_fs_used);?>
                </tr>
                </tbody>

	</table>
	<table>
	<tr>
	<td><select name="option-status">
		<option value="Actived" <?if ($op_status == 1) echo 'selected';?>>Actived</option> 
		<option value="Inactived" <?if ($op_status == 0) echo 'selected';?>>Inactivated</option>
	</select></td>
		<td> <input type="submit" value="Submit"></td>
	</tr>
	</table>
</form>
</div>
<br><br>


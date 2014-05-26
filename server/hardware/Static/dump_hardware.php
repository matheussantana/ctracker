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

require '../../jsonwrapper/jsonwrapper.php';
include '../../mongoconnection.php';
include '../../mongofunctions.php';
include "../../mysqlconnection.php";
include_once "../../Archive/functions.php";
$itoken = safe($_GET['itoken']);
if (isset($_SESSION['email']) == false || isInstanceOwner($_SESSION['email'],$itoken) == false) {//check if user is login;
    echo "<script>window.location='../../Archive/index.php';</script>";
	die();
}




  // execute query
  // retrieve all documents
$criteria = array(
'InstanceToken' => $itoken);

$end = getTimestampByIndex(-1, $collection, $criteria);//get the last document inserted;
        //              $cursor = $collection->find(array('InstanceToken' => array('$in' => $inst_stack) ,"timestamp" => $end));
if($end == '{}'){
	echo 'No data found.';
	exit;}

$cursor = $collection->find(array('InstanceToken' => $itoken ,"timestamp" => $end));

//$cursor = $collection->find($criteria);i
//$cursor->sort(array('time' => 1));
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

</script>
<div style="width:700px;">
	<h2>OS</h2>

	<table id="tablesorter-demo" class="tablesorter" border="0" cellpadding="0" cellspacing="1">
		<thead>
			<tr>
				<th>Distribution</th>
				<th>Release</th>
				<th>Codename</th>
				<th>Architecture</th>
				<th>Hostname</th>
			</tr>
		</thead>
		<tbody>
<?php
  foreach ($cursor as $obj) {
echo'			<tr>
                                <td>'.$obj['hardware']['os']['distro'].'</td>
				<td>'.$obj['hardware']['os']['release'].'</td>
                                <td>'.$obj['hardware']['os']['codename'].'</td>
                                <td>'.$obj['hardware']['os']['arch'].'</td>
                                <td>'.$obj['hardware']['os']['hostname'].'</td>
			</tr>';
}
?>
		</tbody>
	</table>

	<h2>CPU</h2>

	<table id="tablesorter-demo" class="tablesorter" border="0" cellpadding="0" cellspacing="1">
		<thead>
			<tr>
				<th>Model Name</th>
				<th>Physical CPU's</th>
				<th>Cores per CPU</th>
				<th>Number of Processors</th>
				<th>Cache Size - Kb</th>
				<th>Frequency - MHz</th>
			</tr>
		</thead>
		<tbody>
<?php
  foreach ($cursor as $obj) {
echo'			<tr>
                                <td>'.$obj['hardware']['cpu']['model_name'].'</td>
				<td>'.$obj['hardware']['cpu']['physical_cpus'].'</td>
                                <td>'.$obj['hardware']['cpu']['cores_per_cpu'].'</td>
                                <td>'.$obj['hardware']['cpu']['number_of_processors'].'</td>
                                <td>'.$obj['hardware']['cpu']['cache_size_kb'].'</td>
                                <td>'.$obj['hardware']['cpu']['freq_cpu'].'</td>

			</tr>';
}
?>
		</tbody>
	</table>

	<h2>Filesystem</h2>
	<table id="tablesorter-demo" class="tablesorter" border="0" cellpadding="0" cellspacing="1">
		<thead>
			<tr>
				<th>Device</th>
				<th>Size</th>
				<th>Available</th>
				<th>Percentage</th>
				<th>Used</th>
				<th>Mount Point</th>
			</tr>
		</thead>
		<tbody>
<?php
  foreach ($cursor as $obj) {
	$size = count($obj['hardware']['fs']['device']);
	$i=0;
	while($i!=$size){
echo'			<tr>
                                <td>'.$obj['hardware']['fs']['device'][$i].'</td>
				<td>'.$obj['hardware']['fs']['size'][$i].'</td>
                                <td>'.$obj['hardware']['fs']['avail'][$i].'</td>
                                <td>'.$obj['hardware']['fs']['pct'][$i].'</td>
                                <td>'.$obj['hardware']['fs']['used'][$i].'</td>
                                <td>'.$obj['hardware']['fs']['mount'][$i].'</td>

			</tr>';
		$i++;
	}
}
?>
		</tbody>
	</table>

	<h2>Memory</h2>
	<table id="tablesorter-demo" class="tablesorter" border="0" cellpadding="0" cellspacing="1">
		<thead>
			<tr>
				<th>Size - Mb</th>
			</tr>
		</thead>
		<tbody>
<?php
  foreach ($cursor as $obj) {
echo'			<tr>
                                <td>'.$obj['hardware']['mem']['size_mb'].'</td>
			</tr>';
}
?>
		</tbody>
	</table>
</div>
<br><br>


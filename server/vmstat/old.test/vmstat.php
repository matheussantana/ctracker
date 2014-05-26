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
$itoken = $_GET['itoken'];
require 'jsonwrapper/jsonwrapper.php';
include 'mongoconnection.php';
include "mysqlconnection.php";
include_once "./Archive/functions.php";

if (isset($_SESSION['email']) == false || isInstanceOwner($_SESSION['email'],$itoken) == false) {//check if user is login;
    echo "<script>window.location='Archive/index.php';</script>";
	die();
}




  // execute query
  // retrieve all documents
$criteria = array(
'InstanceToken' => $itoken);



  $cursor = $collection->find($criteria);
//$cursor->sort(array('time' => 1));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-us">
<head>
	<title>Totas as metricas</title>
	<link rel="stylesheet" href="grid_js/css/jq.css" type="text/css" media="print, projection, screen" />
	<link rel="stylesheet" href="grid_js/themes/blue/style.css" type="text/css" media="print, projection, screen" />
	<script type="text/javascript" src="grid_js/jquery-latest.js"></script>
	<script type="text/javascript" src="grid_js/jquery.tablesorter.js"></script>
	<script type="text/javascript" src="grid_js/js/chili/chili-1.8b.js"></script>
	<script type="text/javascript" src="grid_js/js/docs.js"></script>
	<script type="text/javascript">
	$(function() {		
		$("#tablesorter-demo").tablesorter({sortList:[[0,0],[2,1]], widgets: ['zebra']});
		$("#options").tablesorter({sortList: [[0,0]], headers: { 3:{sorter: false}, 4:{sorter: false}}});
	});	
	</script>
</head>
<body>
<div>
	<a name="Demo"></a>
	<h1>Instance: <?echo $itoken?></h1>

	<table id="tablesorter-demo" class="tablesorter" border="0" cellpadding="0" cellspacing="1">
		<thead>
			<tr>
				<th>InputDataName</th>
				<th>Procs:r</th>
				<th>Probs:b</th>
				<th>Memory:swpd</th>
				<th>Memory:buff</th>
				<th>Memory:cache</th>
				<th>Memory:free</th>
                                <th>Swap:si</th>
                                <th>Swap:so</th>
                                <th>IO:bi</th>
                                <th>IO:bo</th>
                                <th>System:in</th>
                                <th>System:cs</th>
                                <th>CPU:us</th>
                                <th>CPU:sy</th>
                                <th>CPU:id</th>
                                <th>CPU:wa</th>
                                <th>Dia</th>
                                <th>Mes</th>
<th>Ano</th>
<th>Hora</th>


			</tr>
		</thead>
		<tbody>
<?php
  foreach ($cursor as $obj) {
echo'			<tr>
				<td>'.$obj['InputDataName'].'</td>
				<td>'.$obj['procs']['r'].'</td>
				<td>'.$obj['procs']['b'].'</td>
				<td>'.$obj['memory']['swpd'].'</td>
				<td>'.$obj['memory']['buff'].'</td>
				<td>'.$obj['memory']['cache'].'</td>
                                <td>'.$obj['memory']['free'].'</td>
                                <td>'.$obj['swap']['si'].'</td>
                                <td>'.$obj['swap']['so'].'</td>

                                <td>'.$obj['io']['bi'].'</td>
                                <td>'.$obj['io']['bo'].'</td>
                                <td>'.$obj['system']['in'].'</td>


                                <td>'.$obj['system']['cs'].'</td>
                                <td>'.$obj['cpu']['us'].'</td>
                                <td>'.$obj['cpu']['sy'].'</td>

                                <td>'.$obj['cpu']['id'].'</td>
                                <td>'.$obj['cpu']['wa'].'</td>
                                <td>'.$obj['date']['day'].'</td>
                                <td>'.$obj['date']['month'].'</td>
                                <td>'.$obj['date']['year'].'</td>

                                <td>'.$obj['date']['time'].'</td>

			</tr>';
}
?>
		</tbody>
	</table>
</div>
<br><br>
<div style="position:fixed;
   left:0px;
   bottom:0px;
   height:30px;
   width:100%;
   background:black;"> <p style="position: fixed; bottom: 1px; left: 10px; color: white">ctracker © Matheus SantAna 2012-2013 - version 0.01 beta</p> </dv>

</body>
</html>


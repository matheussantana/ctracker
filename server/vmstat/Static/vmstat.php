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

//https://gist.github.com/u-a/3804918
$total = $collection->count();

$rg_page = "/^[0-9]+$/";
if(preg_match($rg_page, $_GET['page']) == 0){//sanity
	echo "arg. wrong";
	die();
}

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;




$limit = 25;
$skip = ($page - 1) * $limit;
$next = ($page + 1);
$prev = ($page - 1);
$sort = array('timestamp' => -1);

$cursor = $collection->find($criteria)->skip($skip)->limit($limit)->sort($sort);
//$cursor = $collection->find($criteria);i
//$cursor->sort(array('time' => 1));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-us">
<head>
	<title>History</title>
<?//css original
//	<link rel="stylesheet" href="../../grid_js/css/jq.css" type="text/css" media="print, projection, screen" />
?>
	<link rel="stylesheet" href="../../Archive/controlpanel/css/styles.css" type="text/css" media="print, projection, screen" />


	<link rel="stylesheet" href="../../grid_js/themes/blue/style.css" type="text/css" media="print, projection, screen" />
	<script type="text/javascript" src="../../grid_js/jquery-latest.js"></script>
	<script type="text/javascript" src="../../grid_js/jquery.tablesorter.js"></script>
	<script type="text/javascript" src="../../grid_js/js/chili/chili-1.8b.js"></script>
	<script type="text/javascript" src="../../grid_js/js/docs.js"></script>
	<script type="text/javascript">
	$(function() {		
		$("#tablesorter-demo").tablesorter({sortList:[[0,0]], widgets: ['zebra']});
		$("#options").tablesorter({sortList: [[0,0]], headers: { 3:{sorter: false}, 4:{sorter: false}}});
	});	
</script>

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

</head>
<body class="bg">
<div style="padding: 10px;">
	<a name="Demo"></a>
	<div style="width: 100%;">
		<div style="float: left; width: 90%;">Instance: <?echo getAlias($itoken)?></div>
<?php


if($page > 1){

        if($page * $limit < $total) {
	        echo '<div style="float: left; width: 5%;"> <a href="?page=' . $next . '&itoken='.$itoken.'">Next</a></div>';

	}
        echo '<div style="float: left; width: 5%;"> <a href="?page=' . $prev . '&itoken='.$itoken.'">Previous</a></div>';

} else {
        if($page * $limit < $total) {
        	echo '<div style="float: left; width: 5%;"> <a href="?page=' . $next . '&itoken='.$itoken.'">Next</a></div>';
}
}


?>
		<br style="clear: left;" />
	</div>
	<table id="tablesorter-demo" class="tablesorter" border="0" cellpadding="0" cellspacing="1">
		<thead>
			<tr>
				<th>Date</th>
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
				<th>Net:txSum</th>
				<th>Net:rxSum</th>
			</tr>
		</thead>
		<tbody>
<?php
  foreach ($cursor as $obj) {
echo'			<tr>
				<td>
				<script type="text/javascript">document.write(convertTimestamp('. $obj['timestamp']->sec.'))</script></td>
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
				<td>'.$obj['network']['txSum'].'</td>
				<td>'.$obj['network']['rxSum'].'</td>
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
   background:black;"> <a href="../../index.php"><img title="Home" style="position: fixed; bottom: 3px; left: 10px;" src="../../pic/home.png" alt="Home"></a>
 <a href="../Dyn/vmstat.php?itoken=<?echo $itoken;?>&page=1"><img title="Streaming" style="position: fixed; bottom: 1px; left: 40px;" src="../../pic/Play.png" alt="Streaming"></>
<a href="../../Flot/index.php?itoken=<? echo $itoken;?>&interval=def"><img title="Chart" style="position: fixed; bottom: 2px; left: 73px;" src="../../pic/chart.png" alt="Chart"></a>
<a href="../../top/Dyn/top.php"><img title="Process" style="position: fixed; bottom: 2px; left: 103px;" src="../../pic/process.png" alt="Process">
</div>
</body>
</html>


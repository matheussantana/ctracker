<? ob_start(); ?>
<?php
/******************************************************************************
 *
 * index.php - Display graphics captured.
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
include "../mysqlconnection.php";
include_once "../Archive/functions.php";


//Validate $_GET['field'] and $_GET['mode']
if(isset($_GET['field']) == false){
	$display_field = "all";
	$display_mode = "";
}else{

	$display_field = $_GET['field'];
	if(isset($_GET['mode']))
		$display_mode = $_GET['mode'];

	switch ($display_field) {
		case "cpu":
			switch ($display_mode) {
				case "idle":
					break;
				case "all":
					break;
				default:
					$display_mode="all";
					break;
			}
		        break;
		case "mem":
			switch ($display_mode) {
				case "free":
					break;
				case "all":
					break;
				default:
					$display_mode="all";
					break;
			}
		        break;
		case "disk":
                        switch ($display_mode) {
                                case "bo":
                                        break;
                                case "all":
                                        break;
                                default:
                                        $display_mode="all";
                                        break;
                        }
			break;
		case "net":
                        switch ($display_mode) {
                                case "txkbs":
                                        break;
                                case "rxkbs":
                                        break;
                                case "all":
                                        break;
                                default:
                                        $display_mode="all";
                                        break;
                        }
                        break;

		case "swap":
                        switch ($display_mode) {
                                case "so":
                                        break;
                                case "all":
                                        break;
                                default:
                                        $display_mode="all";
                                        break;
                        }

			break;
		case "all":
			break;

		default:
			$display_field="all";

	}

}
	


$itoken = safe((isset($_POST["itoken"])) ? $_POST["itoken"] : $_GET["itoken"]);
if (isset($_SESSION['email']) == false || isInstanceOwner($_SESSION['email'],$itoken) == false) {//check if user is login;

    echo "<script>window.location='../Archive/index.php';</script>";
	die();
}

$interval = "";
$rg_interval = "/^[a-z]+$/";//sanity

if(isset($_POST['interval']) == false || $_POST['interval'] != "def"){//sanity
	$interval = "def";//use the default interval.
}else{
	if(preg_match($rg_interval, $_POST['interval']) == 0)
		die();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Server Real-time updates</title>
	<link href="flot.css" rel="stylesheet" type="text/css">
<?//remove styles.css to return to the original css.?>
	<link href="../Archive/controlpanel/css/styles.css" rel="stylesheet" type="text/css">

	<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="../../excanvas.min.js"></script><![endif]-->
	<script language="javascript" type="text/javascript" src="jquery.js"></script>
	<script language="javascript" type="text/javascript" src="jquery.flot.js"></script>
	<script language="javascript" type="text/javascript" src="jquery.flot.time.js"></script>

<script>
jQuery(window).load(function(){
//loading box appears
//jQuery('#loading').fadeOut(6000);
jQuery('#loading')
});
</script>
        <link href="loading.css" rel="stylesheet" type="text/css">

<!--reveal model box-->
		<script type="text/javascript" src="./revealmodalbox/jquery.reveal.js"></script>
	  	<link rel="stylesheet" href="./revealmodalbox/reveal.css">	

<?

include "plot.php";
$cpu_opt="cpu_opt";
$mem_opt="mem_opt";
$io_opt="io_opt";
$swap_opt="swap_opt";
$net_txSum="net_txSum";
$net_rxSum="net_rxSum";


$i = 0;
$inst_query = mysql_query("SELECT * FROM instance WHERE email='".filter_var($_SESSION["email"],FILTER_VALIDATE_EMAIL)."'");
while ($inst = mysql_fetch_array($inst_query)) {

	$host_list[$i]= array("instanceID" => $inst['instanceID'], "alias" => $inst['Alias']);
	$i++;
}
if(($display_field=="cpu" && $display_mode=="idle") || $display_field=="all" || ($display_field=="cpu" && $display_mode=="all"))
	getjsplot("cpu", "id", "placeholder", "updateinterval",$itoken, $interval,$cpu_opt, $host_list);
if(($display_field=="mem" && $display_mode=="free") || $display_field=="all" || ($display_field=="mem" && $display_mode=="all"))
	getjsplot("memory", "free", "placeholder2", "updateinterval2",$itoken, $interval, $mem_opt, $host_list);
if(($display_field=="disk" && $display_mode=="bo") || $display_field=="all" || ($display_field=="disk" && $display_mode=="all"))
	getjsplot("io", "bo", "placeholder3", "updateinterval3",$itoken, $interval, $io_opt, $host_list);
if(($display_field=="swap" && $display_mode=="so") || $display_field=="all" || ($display_field=="swap" && $display_mode=="all"))
	getjsplot("swap", "so", "placeholder4", "updateinterval4",$itoken, $interval, $swap_opt, $host_list);
if(($display_field=="net" && $display_mode=="txkbs") || $display_field=="all" || ($display_field=="net" && $display_mode=="all"))
	getjsplot("network", "txSum", "placeholder5", "updateinterval5",$itoken, $interval, $net_txSum, $host_list);
if(($display_field=="net" && $display_mode=="rxkbs") || $display_field=="all" || ($display_field=="net" && $display_mode=="all"))
	getjsplot("network", "rxSum", "placeholder6", "updateinterval6",$itoken, $interval, $net_rxSum, $host_list);
?>

</head>
<?//remove class bg to return to the original css.?>
<body class="bg">

<?/*	<div id="header">
//added custom css inline
		<h2 style="border-bottom: 0px;">Real-time updates</h2>
	</div>*/?>
<div id="loading"></div>
	<div id="content">
		<?php if(($display_field=="cpu" && $display_mode=="idle") || $display_field=="all" || ($display_field=="cpu" && $display_mode=="all")){?>
		<p>CPU - id: Time spent idle(Percentage)</p>
		<?php getFilter($cpu_opt, $host_list, $itoken); ?>
		<div class="demo-container">
			<div id="placeholder" class="demo-placeholder"></div>
		</div>
		<?}?>

<!--		<p>You can update a chart periodically to get a real-time effect by using a timer to insert the new data in the plot and redraw it.</p> -->

<!--		<p>Time between updates: <input id="updateInterval" type="text" value="" style="text-align: right; width:5em"> milliseconds</p> -->
		<?php if(($display_field=="mem" && $display_mode=="free" )|| $display_field=="all" || ($display_field=="mem" && $display_mode=="all")){?>
		<p>Memory - free</p>
		<?php getFilter($mem_opt, $host_list, $itoken); ?>

		<div class="demo-container">
			<div id="placeholder2" class="demo-placeholder"></div>
		</div>
		<?} ?>

		<?php if(($display_field=="disk" && $display_mode=="bo") || $display_field=="all" || ($display_field=="disk" && $display_mode=="all")){?>
		<p>Disk - bo: Blocks sent to a block device (blocks/s)</p>
		<?php getFilter($io_opt, $host_list, $itoken); ?>

		<div class="demo-container">
			<div id="placeholder3" class="demo-placeholder"></div>
		</div>
		<?}?>

		<?php if(($display_field=="net" && $display_mode=="txkbs") || $display_field=="all" || ($display_field=="net" && $display_mode=="all")){?>
		<p>Network - txkB/s: Total number of kilobytes transmitted per second. </p>
		<?php getFilter($net_txSum, $host_list, $itoken); ?>

		<div class="demo-container">
			<div id="placeholder5" class="demo-placeholder"></div>
		</div>
		<?}?>

		<?php if(($display_field=="net" && $display_mode=="rxkbs") || $display_field=="all" || ($display_field=="net" && $display_mode=="all")){?>
		<p>Network - rxkB/s: Total number of kilobytes received per second. </p>
		<?php getFilter($net_rxSum, $host_list, $itoken); ?>

		<div class="demo-container">
			<div id="placeholder6" class="demo-placeholder"></div>
		</div>
		<?}?>

		<?php if(($display_field=="swap" && $display_mode=="so") || $display_field=="all" || ($display_field=="swap" && $display_mode=="all")){?>
		<p>Swap - so: Amount of memory swapped to disk (/s). </p>
		<?php getFilter($swap_opt, $host_list, $itoken); ?>

		<div class="demo-container">
			<div id="placeholder4" class="demo-placeholder"></div>
		</div>
		<?}?>
	</div>

<?/*	<div id="footer">
		ctracker © Matheus SantAna 2012-2013 - version 0.01 beta
		<!--Copyright &copy; 2007 - 2013 IOLA and Ole Laursen-->
	</div>*/?>
<div style="position:fixed;
   left:0px;
   bottom:0px;
   height:30px;
   width:100%;
   background:black;"> <a href="../index.php"><img title="Home" style="position: fixed; bottom: 3px; left: 10px;" src="../pic/home.png" alt="Home"></a> </div>

 <a href="../vmstat/Dyn/vmstat.php?itoken=<?echo $itoken;?>&page=1"><img title="Streaming" style="position: fixed; bottom: 1px; left: 40px;" src="../pic/Play.png" alt="Streaming"/></a>
 <a href="../vmstat/Static/vmstat.php?itoken=<?echo $itoken;?>&page=1"><img title="History" style="position: fixed; bottom: 1px; left: 70px;" src="../pic/list.png" alt="History"/></a>
<a href="../top/Dyn/top.php"><img title="Process" style="position: fixed; bottom: 2px; left: 103px;" src="../pic/process.png" alt="Process"></a>

<link href="button.css" rel="stylesheet" type="text/css">
<a href="#" class="classname" data-reveal-id="myModal_cpu_link_chart">CPU</a>
<div id="myModal_cpu_link_chart" class="reveal-modal">
	<h1>CPU</h1>
	<table>
		<tr><td><a href="?field=cpu&mode=idle&itoken=<?echo $itoken;?>&interval=def">id: Time spent idle</a></td></tr>
                <tr><td><a href="?field=cpu&mode=all&itoken=<?echo $itoken;?>&interval=def">display all cpu charts</a></td></tr>
	</table>
	<a class="close-reveal-modal">&#215;</a>
</div>

<a href="#" class="classname" data-reveal-id="myModal_mem_link_chart" style="position: fixed; bottom: 3px; left: 210px;" >Mem</a>
<div id="myModal_mem_link_chart" class="reveal-modal">
	<h1>Memory</h1>
	<table>
		<tr><td><a href="?field=mem&mode=free&itoken=<?echo $itoken;?>&interval=def">free: the amount of idle memory</a></td></tr>
                <tr><td><a href="?field=mem&mode=all&itoken=<?echo $itoken;?>&interval=def">display all memory charts</a></td></tr>
	</table>
	<a class="close-reveal-modal">&#215;</a>
</div>

<a href="#" class="classname"  data-reveal-id="myModal_disk_link_chart" style="position: fixed; bottom: 3px; left: 270px;" >Disk</a>
<div id="myModal_disk_link_chart" class="reveal-modal">
	<h1>Disk</h1>
	<table>
		<tr><td><a href="?field=disk&mode=bo&itoken=<?echo $itoken;?>&interval=def">bo: Blocks sent to a block device (blocks/s)</a></td></tr>
                <tr><td><a href="?field=disk&mode=all&itoken=<?echo $itoken;?>&interval=def">display all disk charts</a></td></tr>
	</table>
	<a class="close-reveal-modal">&#215;</a>
</div>

<a href="#" class="classname" data-reveal-id="myModal_net_link_chart" style="position: fixed; bottom: 3px; left: 330px;" >Net</a>
<div id="myModal_net_link_chart" class="reveal-modal">
	<h1>Network</h1>
	<table>
		<tr><td><a href="?field=net&mode=txkbs&itoken=<?echo $itoken;?>&interval=def">txkB/s: Total number of kilobytes transmitted per second</a></td></tr>
		<tr><td><a href="?field=net&mode=rxkbs&itoken=<?echo $itoken;?>&interval=def">rxkB/s: Total number of kilobytes received per second</a></td></tr>
		<tr><td><a href="?field=net&mode=all&itoken=<?echo $itoken;?>&interval=def">display all network charts</a></td></tr>
	</table>
	<a class="close-reveal-modal">&#215;</a>
</div>

<a href="#" class="classname"  data-reveal-id="myModal_swap_link_chart" style="position: fixed; bottom: 3px; left: 390px;" >Swap</a>
<div id="myModal_swap_link_chart" class="reveal-modal">
	<h1>Swap</h1>
	<table>
		<tr><td><a href="?field=swap&mode=so&itoken=<?echo $itoken;?>&interval=def">so: Amount of memory swapped to disk (/s)</a></td></tr>
                <tr><td><a href="?field=swap&mode=all&itoken=<?echo $itoken;?>&interval=def">display all swap charts</a></td></tr>
	</table>
	<a class="close-reveal-modal">&#215;</a>
</div>

<a href="?field=all&itoken=<?echo $itoken;?>&interval=def" class="classname" style="position: fixed; bottom: 3px; left: 450px;" >All</a>

</div>
</body>
</html>

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

if (isset($_SESSION['email']) == false) {//check if user is login;
    echo "<script>window.location='../Archive/index.php';</script>";
	die();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Server Real-time updates</title>
	<link href="flot.css" rel="stylesheet" type="text/css">
	<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="../../excanvas.min.js"></script><![endif]-->
	<script language="javascript" type="text/javascript" src="jquery.js"></script>
	<script language="javascript" type="text/javascript" src="jquery.flot.js"></script>
	<script language="javascript" type="text/javascript" src="jquery.flot.time.js"></script>

<?

$itoken = (isset($_POST["itoken"])) ? $_POST["itoken"] : $_GET["itoken"];
include "plot.php";
getjsplot("cpu", "id", "placeholder", "updateinterval",$itoken);
getjsplot("memory", "free", "placeholder2", "updateinterval2",$itoken);
getjsplot("io", "bo", "placeholder3", "updateinterval3",$itoken);
getjsplot("swap", "so", "placeholder4", "updateinterval4",$itoken);
?>

</head>
<body>

	<div id="header">
		<h2>Real-time updates</h2>
	</div>

	<div id="content">
		<p>CPU - id: Time spent idle(Percentage)</p>
		<div class="demo-container">
			<div id="placeholder" class="demo-placeholder"></div>
		</div>

<!--		<p>You can update a chart periodically to get a real-time effect by using a timer to insert the new data in the plot and redraw it.</p> -->

<!--		<p>Time between updates: <input id="updateInterval" type="text" value="" style="text-align: right; width:5em"> milliseconds</p> -->

		<p>Memory - free</p>
		<div class="demo-container">
			<div id="placeholder2" class="demo-placeholder"></div>
		</div>

		<p>Disk - bo: Blocks sent to a block device (blocks/s)</p>
		<div class="demo-container">
			<div id="placeholder3" class="demo-placeholder"></div>
		</div>

		<p>Swap - so: Amount of memory swapped to disk (/s). </p>
		<div class="demo-container">
			<div id="placeholder4" class="demo-placeholder"></div>
		</div>




	</div>

	<div id="footer">
		ctracker © Matheus SantAna 2012-2013 - version 0.01 beta
		<!--Copyright &copy; 2007 - 2013 IOLA and Ole Laursen-->
	</div>

</body>
</html>

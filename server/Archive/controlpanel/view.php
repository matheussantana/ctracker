<?php
/******************************************************************************
 *
 * view.php - Display control panel options like available instances for a logged user.
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
$tp = formatData($_GET['tp']);

if (strcmp($tp, "view-servers") == 0) { ?>

		<link rel="stylesheet" href="table/style.css" />
			<table cellpadding="0" cellspacing="0" border="0" id="table" class="sortable">
				<thead>
					<tr>
						<th class="nosort"><h3>instanceID</h3></th>
						<th><h3>Alias</h3></th>
						<th><h3>Operation</h3></th>

					</tr>
				</thead>
				<tbody>
		<?
		$inst_query = mysql_query("SELECT * FROM instance WHERE email='".$_SESSION["email"]."'"); 
		  while ($inst= mysql_fetch_array($inst_query)) {?>
					<tr>
						<td><?echo $inst['instanceID'];?></td>
						<td><a rel="facebox" href="../../hardware/Static/dump_hardware.php?itoken=<? echo $inst['instanceID']; ?>"><?echo $inst['Alias'];?></a></td>
						<td><a href="../../vmstat/Dyn/vmstat.php?itoken=<? echo $inst['instanceID']."&page=1"; ?>">Streaming</a> | <a href="../../vmstat/Static/vmstat.php?itoken=<? echo $inst['instanceID']."&page=1"; ?>">History</a> | <a href="../../Flot/index.php?itoken=<? echo $inst['instanceID']; ?>&interval=def">Chart</a> |  <a rel="facebox" href="../form.php?tp=add-alert&itoken=<? echo $inst['instanceID']; ?>">Alert</a> |  <a href="index.php?pg=delete&tp=delete-server&sid=<? echo $inst['instanceID']; ?>" onclick="return confirm('Are you sure you want to delete this item?');">Remove</a></td>
	
					</tr>
		<?}?>

				</tbody>
		  </table>
			<div id="controls">
				<div id="perpage">
					<select onchange="sorter.size(this.value)">
					<option value="5">5</option>
						<option value="10" selected="selected">10</option>
						<option value="20">20</option>
						<option value="50">50</option>
						<option value="100">100</option>
					</select>
					<span>Entries Per Page</span>
				</div>
				<div id="navigation">
					<img src="table/images/first.gif" width="16" height="16" alt="First Page" onclick="sorter.move(-1,true)" />
					<img src="table/images/previous.gif" width="16" height="16" alt="First Page" onclick="sorter.move(-1)" />
					<img src="table/images/next.gif" width="16" height="16" alt="First Page" onclick="sorter.move(1)" />
					<img src="table/images/last.gif" width="16" height="16" alt="Last Page" onclick="sorter.move(1,true)" />
				</div>
				<div id="text">Displaying Page <span id="currentpage"></span> of <span id="pagelimit"></span></div>
			</div>
			<script type="text/javascript" src="table/script.js"></script>
			<script type="text/javascript">
		  var sorter = new TINY.table.sorter("sorter");
			sorter.head = "head";
			sorter.asc = "asc";
			sorter.desc = "desc";
			sorter.even = "evenrow";
			sorter.odd = "oddrow";
			sorter.evensel = "evenselected";
			sorter.oddsel = "oddselected";
			sorter.paginate = true;
			sorter.currentid = "currentpage";
			sorter.limitid = "pagelimit";
			sorter.init("table",1);
		  </script>

<?}?>

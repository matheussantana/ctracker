<?
/******************************************************************************
 *
 * delete.php - Handle deletion.
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
$tp = (isset($_POST["tp"])) ? $_POST["tp"] : $_GET["tp"];

if(strcmp($tp, "delete-server") == 0){

	include "../../mongoconnection.php";

	if(isset($_GET['sid']) || empty($_GET['sid']) == 1){
		$sid = safe(formatData($_GET["sid"]));
		$email = $_SESSION['email'];

	        $delete = "DELETE FROM instance WHERE instanceID='$sid' AND email='$email'";
	}else{
		$delete = '';
	}
}

$query = mysql_query($delete);
if($query){
	if(strcmp($tp, "delete-server") == 0){

		$collection->remove(array('InstanceToken' => $sid),  array( 'w' => true ));
	}
}
//  echo "Error creating database: " . mysql_error();
//	die();

if(!$query)
    echo "<script>window.location='" . basename($_SERVER['PHP_SELF']) . "?pg=message&msg=error-delete';</script>";
else
    echo "<script>window.location='" . basename($_SERVER['PHP_SELF']) . "?pg=message&msg=confirm-delete';</script>";

?>

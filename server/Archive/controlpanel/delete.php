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
	$sid = formatData($_GET["sid"]);
	$email = $_SESSION['email'];

        $delete = "DELETE FROM instance WHERE instanceID='$sid' AND email='$email'";

}

$query = mysql_query($delete);
//  echo "Error creating database: " . mysql_error();
//	die();

if(!$query)
    echo "<script>window.location='" . basename($_SERVER['PHP_SELF']) . "?pg=message&msg=error-delete';</script>";
else
    echo "<script>window.location='" . basename($_SERVER['PHP_SELF']) . "?pg=message&msg=confirm-delete';</script>";

?>

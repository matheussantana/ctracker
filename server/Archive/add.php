<?
/******************************************************************************
 *
 * add.php - Class used to handle the insertion/add of new users and instances.
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

if (strcmp($tp, "signup") == 0) {

	$email = $_POST['email'];
	$pass = $_POST['password'];

	$insert = "INSERT INTO user (email, pass, date_register) values ('$email','$pass', Now())";
}
elseif (strcmp($tp, "add-server") == 0) {

	$name = $_POST['name'];
	$email = $_SESSION['email'];
	$token = uniqid('', true);

	$insert = "INSERT INTO instance (instanceID, email, Alias) values ('$token','$email', '$name')";

	if($name=="")//if name is empty, we need to guarantee the constrain and so we will invalidate the $insert query;
		$insert = "";
}



//echo $query;
//exit();
$query = mysql_query($insert);
//  echo "Error creating database: " . mysql_error();
//	die();
if (!$query)
    echo "<script>window.location='" . basename($_SERVER['PHP_SELF']) . "?pg=message&msg=error-add';</script>";

else {
    if (strcmp($tp, "signup") == 0) {
        session_start();
        $_SESSION["email"] = $email;
        header("Location: index.php");
    }else
	echo "<script>window.location='" . basename($_SERVER['PHP_SELF']) . "?pg=message&msg=confirm-add';</script>";
}
?>

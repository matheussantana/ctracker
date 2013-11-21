<?
/******************************************************************************
 *
 * login.php - Handle user's login.
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
include "../mysqlconnection.php";
//include_once "functions.php";
$button = $_GET['submit'];


if($button == "Registrar"){
    echo "<script>window.location='index.php?pg=form&tp=Registrar';</script>";
}
elseif(strcmp($button, "Login") == 0){
	// Data from form
	//$email = formatData($_GET["email"]);
	$email = $_GET['email'];
	//$password = md5(formatData($_POST["password"]));
	$password = $_GET["pass"];

//echo "SELECT * FROM user WHERE email='$email' AND pass='$password'";
//die();
	$query = mysql_query("SELECT * FROM user WHERE email='$email' AND pass='$password'");
//  echo "Error creating database: " . mysql_error();
//die();
	if (mysql_num_rows($query) == 0) {
	    echo "<script>window.location='index.php?pg=message&msg=error-login';</script>";
	} else {
	    $result_array = mysql_fetch_array($query);
	    //$id_user = $result_array["id_user"];
	    //mysql_query($update);
	    session_start();
//	    $_SESSION["id_user"] = $query['id_user'];
	    $_SESSION["email"] = $email;
	    header("Location: index.php");
	}

	mysql_close($connection);



}

?>

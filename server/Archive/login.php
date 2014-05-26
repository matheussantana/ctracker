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
require("../phpass/phpass-0.3/PasswordHash.php");
include "functions.php";

//include_once "functions.php";
$button = $_GET['submit'];


if($button == "Register"){
    echo "<script>window.location='index.php?pg=form&tp=Register';</script>";
}
elseif(strcmp($button, "Login") == 0){
	$hasher = new PasswordHash(8, false);
	// Data from form
	//$email = formatData($_GET["email"]);
	$email = filter_var(safe($_GET['email']), FILTER_VALIDATE_EMAIL );
	if($email == false){
		echo 'arg. wrong';
	        echo "<script>window.location='index.php?pg=message&msg=error-login';</script>";
		die();
	}

	//$password = md5(formatData($_POST["password"]));
	$password = safe($_GET["pass"]);

	if (strlen($password) > 72) { die("Password must be 72 characters or less"); }

	// Just in case the hash isn’t found
	$stored_hash = "*";


//echo "SELECT * FROM user WHERE email='$email' AND pass='$password'";
//die();
	$query = mysql_query("SELECT * FROM user WHERE email='$email'");
//  echo "Error creating database: " . mysql_error();
//die();

	if (mysql_num_rows($query) == 0) {
	    echo "<script>window.location='index.php?pg=message&msg=error-login';</script>";
	} else {

	    $result_array = mysql_fetch_array($query);
	    //$id_user = $result_array["id_user"];
	    //mysql_query($update);

		// Retrieve the hash that you stored earlier
		$stored_hash = $result_array["pass"];

		// Check that the password is correct, returns a boolean
		$check = $hasher->CheckPassword($password, $stored_hash);

		if($check){

		    session_start();
	//	    $_SESSION["id_user"] = $query['id_user'];
		    $_SESSION["email"] = $email;
		    header("Location: index.php");
		}else{

	            echo "<script>window.location='index.php?pg=message&msg=error-login';</script>";

		}
	}

	mysql_close($connection);



}

?>

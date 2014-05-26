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

	$hasher = new PasswordHash(8, false);
	$email = filter_var(safe($_POST['email']) , FILTER_VALIDATE_EMAIL);

	if($email == false){
		$insert = "";
		echo "arg. wrong";
	}else{

		$password = safe($_POST['password']);

		if (strlen($password) > 72) {
			die("Password must be 72 characters or less");
		}

		// The $hash variable will contain the hash of the password
		$hash = $hasher->HashPassword($password);

		if (strlen($hash) >= 20) {

			$pass = $hash;
			$insert = "INSERT INTO user (email, pass, date_register) values ('$email','$pass', Now())";
		}
		else
			$insert = "";
	}
}
elseif (strcmp($tp, "add-server") == 0) {

	require("../../phpass/phpass-0.3/PasswordHash.php");

	$rg_name = "/^[a-zA-Z0-9]+$/";
	
	if(preg_match($rg_name, $_POST['name']) == 0 || isset($_POST['name']) == false || empty($_POST['name']) == 1) {
		echo 'arg. wrong';
		die();
	}else
		$name = $_POST['name'];


	$email = $_SESSION['email'];
	//$token = uniqid('', true);
        $hasher = new PasswordHash(8, false);
        $token = $hasher->HashPassword(rand(5, 1500).$email.time());
	$token = str_replace("$2a$08$","",$token);

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

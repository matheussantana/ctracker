<? ob_start(); ?>
<?php
/******************************************************************************
 *
 * index.php - Main file - Used to login and show all control/display pages.
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
ini_set('display_errors',1); 
 error_reporting(E_ALL);
include "../mysqlconnection.php";
include_once "functions.php";
?>
<!doctype html>

<head>

	<!-- Basics -->
	
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>ctracker - Login</title>

	<!-- CSS -->
	
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/animate.css">
	<link rel="stylesheet" href="css/styles.css">
	
</head>

	<!-- Main HTML -->
	
<body>

<p style="position: fixed;top: 10px; left: 10px; font-size:30px;">ctracker</p>

<?if (isset($_SESSION['email']) == false) {?>

	<div id="container">
<?php
            if (isset($_GET['pg'])) {
                $pg = formatData($_GET['pg']);
                if (file_exists("$pg.php"))
                    include("$pg.php");
                elseif (file_exists("$pg.html"))
                    include("$pg.html");
                else
                    echo "<script>window.location='" . basename($_SERVER['PHP_SELF']) . "?pg=message&msg=error-page-not-found';</script>";
            }else {?>	
	<!-- Begin Page Content -->
		
 <form name="input" action="login.php" method="get">
		
		<label for="name">Email:</label>
		
		<input type="name" name="email">
		
		<label for="username">Password:</label>
		
		<input type="password" name="pass">
		
		<div id="lower">
		
		<input type="submit" name = "submit" value="Registrar">

		<input type="submit" name = "submit"  value="Login">
		
		</div>
		
		</form>
		

	
	
	<!-- End Page Content -->
	<?}
}else{
            if (isset($_GET['pg'])) {
                $pg = formatData($_GET['pg']);
                if (file_exists("$pg.php"))
                    include("$pg.php");
                elseif (file_exists("$pg.html"))
                    include("$pg.html");
                else
                    echo "<script>window.location='" . basename($_SERVER['PHP_SELF']) . "?pg=message&msg=error-page-not-found';</script>";
            }else {}
echo "<script>window.location='controlpanel/index.php';</script>";


}?>	
	</div>
<div style="position:fixed;
   left:0px;
   bottom:0px;
   height:30px;
   width:100%;
   background:black;"> <p style="position: fixed; bottom: 7px; left: 10px; color: white">ctracker © Matheus SantAna 2012-2013 - version 0.01 beta</p> </di>
</body>

</html>
	
	
	
	
	
		
	

<? ob_start(); ?>
<?php
/******************************************************************************
 *
 * index.php - Control panel with links to graphics and data dumps.
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
error_reporting(E_ALL);
ini_set('display_errors', 'On');
session_start();

include "../../mysqlconnection.php";
include "../functions.php";
?>
<?
if(isset($_SESSION['email']) == false){
	echo "<script>window.location='../index.php';</script>";
	die();
}

?>
﻿<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>ctracker - Control Panel</title>
<link rel="icon" href="images/favicon.gif" type="image/x-icon"/>
 <!--[if lt IE 9]>
 <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
<link rel="shortcut icon" href="images/favicon.gif" type="image/x-icon"/> 
<link rel="stylesheet" type="text/css" href="css/styles.css"/>
</head>
<body>
   <div class="bg">
    <!--start container-->
    <div id="container">
    <!--start header-->
    <header>
      <!--start logo-->
      <a href="../index.php" id="logo" style="color: red; text-decoration: none;"><h1>ctracker</h1></a>    
      <!--end logo-->
      <!--start menu-->
  	   <nav>
         <ul>
	<? include "facebox/facebox.php";?>
         <li><a href="../form.php?tp=add-server" rel="facebox">Add new server</a></li>
     	   <li><a href="#">About</a></li>
     	   <li><a href="#">FAQ</a></li>
	   <li><a href="../../top/Dyn/top.php"; ?>Process</a></li>
	<li><a href="../index.php?pg=logout">Logout</a></li>
         </ul>
      </nav>
  	   <!--end menu-->
      <!--end header-->
	</header>
   <!--start intro-->
   <section id="intro">
      <hgroup>
      <h1>Cloud Computing Monitoring Tool</h1>
<!--      <h2>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec molestie. Sed aliquam sem ut arcu. Phasellus sollicitudin. 
      Vestibulum condimentum  facilisis nulla. In hac habitasse platea dictumst. Nulla nonummy. Cras quis libero.</h2> -->
<br>
<?php
            if (isset($_GET['pg'])) {
                $pg = formatData($_GET['pg']);
                if (file_exists("$pg.php"))
                    include("$pg.php");
                elseif (file_exists("$pg.html"))
                    include("$pg.html");
                else
                    echo "<script>window.location='" . basename($_SERVER['PHP_SELF']) . "?pg=message&msg=error-page-not-found';</script>";
            }else {
                    echo "<script>window.location='" . basename($_SERVER['PHP_SELF']) . "?pg=view&tp=view-servers';</script>";
		}?>
<br>
      </hgroup>
   </section>
   <!--end intro-->
   <!--start holder-->
 
   </div>
   <!--end container-->
   <!--start footer-->

   <footer>

      <div class="container">  
         <div id="FooterTree"> ctracker © Matheus SantAna 2012-2014 - version 0.02 beta <br>HTML template based from: © 2011 Minimalis -Valid html5, design and code by <a href="http://www.marijazaric.com">marija zaric - creative simplicity</a> </div> 
      </div>
   </footer>

   <!--end footer-->
   </div>
   <!--end bg-->
   <!-- Free template distributed by http://freehtml5templates.com -->
  </body>
</html>

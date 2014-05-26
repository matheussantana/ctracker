<?php
/******************************************************************************
 *
 * tracker.php - Web-service used to retrieve JSON from clientes.
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
ini_set('display_errors', True);
//echo 'teste';
require 'jsonwrapper/jsonwrapper.php';
include 'mongoconnection.php';

$data = $_POST['data'];
//echo 'aaa';

$json = json_decode2($data);
echo $_POST['data'];
var_dump($json);

var_dump($data);
$collection->insert($json);
 $conn->close();
?>

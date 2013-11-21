<?php
/******************************************************************************
 *
 * mongodb.php - handle mongodb connection.
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

require 'jsonwrapper/jsonwrapper.php';

try {
  // open connection to MongoDB server
  $conn = new Mongo('localhost');

  // access database
  $db = $conn->test;

  // access collection
  $collection = $db->items;

  // execute query
  // retrieve all documents
  $cursor = $collection->find();

  // iterate through the result set
  // print each document
//  echo $cursor->count() . ' document(s) found. <br/>';
//header("Content-type: application/json");
  foreach ($cursor as $obj) {
/*    echo 'InputName: ' . $obj['InputDataName'] . '<br/>';
	echo "Procs:<br>";
    echo 'r: ' . $obj['procs']["r"];
    echo 'b: ' . $obj['procs']["b"] . '<br/>';
	echo '<br/>';*/
//	print_r($obj);
	$json = json_encode2($obj);
	echo $json;
  }

  // disconnect from server
  $conn->close();
} catch (MongoConnectionException $e) {
  die('Error connecting to MongoDB server');
} catch (MongoException $e) {
  die('Error: ' . $e->getMessage());
}
?>

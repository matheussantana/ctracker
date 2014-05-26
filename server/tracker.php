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
require 'jsonwrapper/jsonwrapper.php';
include 'mongoconnection.php';
include 'mysqlconnection.php';
 
	function objectToArray($d) {
//http://www.if-not-true-then-false.com/2009/php-tip-convert-stdclass-object-to-multidimensional-array-and-convert-multidimensional-array-to-stdclass-object/
		if (is_object($d)) {
			// Gets the properties of the given object
			// with get_object_vars function
			$d = get_object_vars($d);
		}
 
		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return array_map(__FUNCTION__, $d);
		}
		else {
			// Return array
			return $d;
		}
	}


	function arrayToObject($d) {
//http://www.if-not-true-then-false.com/2009/php-tip-convert-stdclass-object-to-multidimensional-array-and-convert-multidimensional-array-to-stdclass-object/
		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return (object) array_map(__FUNCTION__, $d);
		}
		else {
			// Return object
			return $d;
		}
	}

include 'json-validator/php-json-schema/php-json-schema-master/src/Json/Validator.php';
use Json\Validator;

function ValidateJSON($json){
	//Validate JSON
	//https://github.com/hasbridge/php-json-schema/tree/master/src#
	$jsonObject = json_decode2($json);
	$validator = new Validator('./json-validator/php-json-schema/php-json-schema-master/vmstat.json');
	$validator->validate($jsonObject);
	return $jsonObject;

}

$data = $_POST['data'];
//echo $data;
try{
	$json = ValidateJSON($data);

	$array_json = objectToArray($json);


	$itoken=$array_json['InstanceToken'];
	$inst_query = mysql_num_rows(mysql_query("SELECT * FROM instance WHERE instanceID='".$itoken."'")); 
	if($inst_query == 0)
		echo "Invalid token.";
	else{

		$time = $array_json['date']['time'];
		$day = $array_json['date']['day'];
		$month = $array_json['date']['month'];
		$year = $array_json['date']['year'];
		$timestamp = $year."-".$month.'-'.$day." ".$time;
		$dt = new DateTime($timestamp, new DateTimeZone('UTC'));
		$ts = $dt->getTimestamp();
		$array_json['timestamp'] = new MongoDate($ts);
		$obj_json = arrayToObject($array_json);

		$collection->insert($obj_json);
		$conn->close();
	}

}
catch (Exception $e) {
	echo 'Caught exception: ',  $e->getMessage(), "\n";
}




?>

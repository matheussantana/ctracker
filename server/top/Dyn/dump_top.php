<? ob_start(); ?>
<?php
/******************************************************************************
 *
 * dump_vmstat.php - Dump vmstat data from DB dynamicly and convert to json.
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
require '../../jsonwrapper/jsonwrapper.php';
include '../../mongoconnection.php';
include "../../mysqlconnection.php";
include_once "../../Archive/functions.php";
include "../../mongofunctions.php";

if (isset($_SESSION['email']) == false) {//check if user is login;
    echo "<script>window.location='../../Archive/index.php';</script>";
	die();
}




  // execute query
  // retrieve all documents
$json = array();
$count=0;
$inst_query = mysql_query("SELECT * FROM instance WHERE email='".$_SESSION["email"]."'");  
$return = array();
$i=0;
while ($inst= mysql_fetch_array($inst_query)) {
//var_dump($inst);
	$inst_stack[$count] = $inst['instanceID'];
			$criteria = array('InstanceToken' => $inst['instanceID']);


		$end = getTimestampByIndex(-1, $collection, $criteria);//get the last document inserted;
	//		$cursor = $collection->find(array('InstanceToken' => array('$in' => $inst_stack) ,"timestamp" => $end));
		if($end!= '{}'){
			$cursor = $collection->find(array('InstanceToken' => $inst['instanceID'] ,"timestamp" => $end));


			while( $cursor->hasNext() )
			{

				$return[$i] = $cursor->getNext();
			        // key() function returns the records '_id'
			        $return[$i++]['_id'] = $cursor->key();
			}

		}

}

echo json_encode($return);

//print_r($inst_stack);

//$cursor->sort(array('time' => 1));

?>


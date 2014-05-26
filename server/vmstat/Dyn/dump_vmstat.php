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

$itoken = safe($_GET['itoken']);
$rg_load = "/^[0-9]+$/";

if(isset($_GET['load']) == false || preg_match($rg_load, $_GET['load']) == 0){//sanity
	echo "arg. wrong";
	die();
}
$load = $_GET['load'];


if (isset($_SESSION['email']) == false || isInstanceOwner($_SESSION['email'],$itoken) == false) {//check if user is login;
    echo "<script>window.location='../Archive/index.php';</script>";
	die();
}




  // execute query
  // retrieve all documents
$criteria = array(
'InstanceToken' => $itoken);


switch($load){
	case "0":
		$freq_ts = 1;
		$type_ts = "minutes";
		$end = getTimestampByIndex(-1, $collection, $criteria);//get the last document inserted;
		date_default_timezone_set("UTC");
		$ts = date('Y-m-d H:i:s e', $end->sec);
		$ts = strtotime("-".$freq_ts." ".$type_ts,strtotime($ts));
		$start = new MongoDate($ts);
		$cursor = $collection->find(array('InstanceToken' => $itoken, "timestamp" => array('$gte' => $start)));
		break;
	default://Get just the updates using the timestamp.

		$rg_ts = "/^[0-9]+$/";
		if(isset($_GET["ts"]) == false || $_GET["ts"] == "0" || preg_match($rg_ts, $_GET['ts']) == 0){//sanity
			echo "null";
			die();
			break;
		}
		$ts = new MongoDate($_GET["ts"]);
		$end = getTimestampByIndex(-1, $collection, $criteria);
		$cursor = $collection->find(array('InstanceToken' => $itoken, "timestamp" => array('$gt' => $ts, '$lt' => $end)));

}
//$cursor->sort(array('time' => 1));
$return = array();
$i=0;
while( $cursor->hasNext() )
{

	$return[$i] = $cursor->getNext();
        // key() function returns the records '_id'
        $return[$i++]['_id'] = $cursor->key();
}
echo json_encode($return);

?>


<?php
ob_start();
/******************************************************************************
 *
 * dump.php - get JSON and send AJAX.
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

include "../mysqlconnection.php";
include_once "../Archive/functions.php";
include "../mongofunctions.php";

$itoken = safe($_GET['itoken']);

if (isset($_SESSION['email']) == false || isInstanceOwner($_SESSION['email'],$itoken) == false) {//check if user is login;
        die();
}


error_reporting(E_ALL);
ini_set('display_errors', True);
//header('Content-Type: application/json');
require '../jsonwrapper/jsonwrapper.php';
include '../mongoconnection.php';

  // execute query
  // retrieve all documents

//Use to verify if inputs are valids.
$rg_type = "/^[a-zA-Z]+$/";
$rg_field =  "/^[a-zA-Z]+$/";
$rg_load = "/^[0-9]+$/";
$rg_interval = "/^[a-z]+$/";

$type = $_GET['type'];
$field = $_GET['field'];
$load = $_GET['load'];
$interval = $_GET['interval'];

if(preg_match($rg_type, $type) == 0 || preg_match($rg_field, $field) == 0 || preg_match($rg_load, $load) == 0 || preg_match($rg_interval, $interval) == 0 ){
	echo 'arg. wrong';
	die();
}

$criteria = array(
'InstanceToken' => $itoken);



switch ($load) {
	case "0"://ignores this one - just to check ajax is connecting with dump.php - workaround. Used to flag the begging - somethink like a handshake.
		echo '[0,0]';
		die();
		break;
	case "1"://Get the first load of data.
		switch($interval){

			case "all":
				$end = getTimestampByIndex(-1, $collection, $criteria);//get the last document inserted;
				$start = getTimestampByIndex(1, $collection, $criteria);//get the first document inserted;
				$cursor = $collection->find(array('InstanceToken' => $itoken, "timestamp" => array('$gte' => $start, '$lte' => $end)));
				break;
			case "filter":
				$rg_freq = "/^[0-9]+$/";
				$rg_type = array("Minutes", "Hours", "Days");


				if(preg_match($rg_freq, $_GET['freq_ts']) == 0 || in_array($_GET['type_ts'], $rg_type) == 0){//sanity input
					echo 'arg. wrong';
					die();
				}

				if(isset($_GET['type_ts']) && isset($_GET['freq_ts'])){

					$type_ts = $_GET['type_ts'];
					$freq_ts = $_GET['freq_ts'];

					
					switch($type_ts){
						case "Hours":
							$type_ts = "hours";
							if($freq_ts < 1 && $freq_ts > 24)
								$freq_ts = 1;
							break;

						case "Minutes":
							$type_ts = "minutes";
							if($freq_ts < 1 && $freq_ts > 60)
								$freq_ts = 5;
							break;

						case "Days":
							$type_ts = "days";
							if($freq_ts < 1 && $freq_ts > 31)
								$freq_ts = 5;
							break;

						default:
							$type_ts = "minutes";
							$freq_ts = 5;
							break;
					}

					$end = getTimestampByIndex(-1, $collection, $criteria);//get the last document inserted;
					date_default_timezone_set("UTC");
					$ts = date('Y-m-d H:i:s e', $end->sec);
					$ts = strtotime("-".$freq_ts." ".$type_ts,strtotime($ts));
					//echo  date('Y-m-d H:i:s e',  $ts);
					$start = new MongoDate($ts);
					$cursor = $collection->find(array('InstanceToken' => $itoken, "timestamp" => array('$gte' => $start)));}
				else{
					echo '[0,0]';
				}
				break;


			default:
				$end = getTimestampByIndex(-1, $collection, $criteria);//get the last document inserted;
				if($end == "{}"){
					echo "[0,0]";
					$cursor = false;
				}
				else{
					date_default_timezone_set("UTC");
					$ts = date('Y-m-d H:i:s e', $end->sec);
					$ts = strtotime("-5 minutes",strtotime($ts));
					//echo  date('Y-m-d H:i:s e',  $ts);
					$start = new MongoDate($ts);
					$cursor = $collection->find(array('InstanceToken' => $itoken, "timestamp" => array('$gte' => $start)));
					break;
				}

		}

		break;
	default://Get just the updates using the timestamp.
		$rg_ts= "/^[0-9]+$/";

		if(isset($_GET["ts"]) == false || $_GET["ts"] == "0" || preg_match($rg_ts,$_GET['ts']) == 0 ){
			echo "null";
			die();
			break;
		}
		$ts_val = $_GET['ts'];
		$ts = new MongoDate($ts_val/1000);
		$end = getTimestampByIndex(-1, $collection, $criteria);
		$cursor = $collection->find(array('InstanceToken' => $itoken, "timestamp" => array('$gt' => $ts, '$lte' => $end)));
}

$cursor->limit(1000);
//  $cursor = $collection->find($criteria);
//$cursor->sort(array('time' => 1));
if($cursor == false)
	exit;
if($cursor->hasNext()){

$count=0;
  foreach ($cursor as $obj) {

if($obj['date']['month'] == "1")
	$month = "Jan";
elseif($obj['date']['month'] == "2")
	$month = "Feb";
elseif($obj['date']['month'] == "3")
	$month = "Mar";
elseif($obj['date']['month'] == "4")
	$month = "Apr";
elseif($obj['date']['month'] == "5")
	$month = "May";
elseif($obj['date']['month'] == "6")
	$month = "Jun";
elseif($obj['date']['month'] == "7")
	$month = "Jul";
elseif($obj['date']['month'] == "8")
	$month = "Aug";
elseif($obj['date']['month'] == "9")
	$month = "Sep";
elseif($obj['date']['month'] == "10")
	$month = "Oct";
elseif($obj['date']['month'] == "11")
	$month = "Nov";
elseif($obj['date']['month'] == "12")
	$month = "Dec";
else
	$month = $obj['date']['month']; 
/*
    * jan
    * feb
    * mar
    * apr
    * may
    * jun
    * jul
    * aug
    * sep
    * oct
    * nov
    * dec
*/
//echo $month;
$time_UTC = $month.' '.$obj['date']['day'].','. ' '.$obj['date']['year'].' '.$obj['date']['time']. ' '.$obj['date']['type'];
$ts = strtotime($time_UTC)*1000;//converte de UTC para Unix timestamp - multiplica por 1000 pra pegar o javascript timestamp;

date_default_timezone_set("UTC");

//Filtra para as ultimas 5 horas
//if(time()-$ts/1000<=18000){

//echo 'timestamp: '.$ts.'<br>';
//echo $time_UTC.'<br>';
if($type == "memory" && $field == "free")
	$vet_time[$count]='['.$ts.', '.$obj['memory']['free'].']';
elseif($type == "memory" && $field == "cache")
	$vet_time_mem_cache[$count]='['.$ts.', '.$obj['memory']['cache'].']';
elseif($type == "memory" && $field == "buff")
	$vet_time_mem_buff[$count]='['.$ts.', '.$obj['memory']['buff'].']';
elseif($type == "cpu" && $field == "id")
	$vet_time_cpu_id[$count]='['.$ts.', '.$obj['cpu']['id'].']';
elseif($type == "cpu" && $field == "us")
	$vet_time_cpu_us[$count]='['.$ts.', '.$obj['cpu']['us'].']';
elseif($type == "cpu" && $field == "sy")
	$vet_time_cpu_sy[$count]='['.$ts.', '.$obj['cpu']['sy'].']';
elseif($type == "cpu" && $field == "wa")
	$vet_time_cpu_wa[$count]='['.$ts.', '.$obj['cpu']['wa'].']';
elseif($type == "io" && $field == "bo")
	$vet_time_io_bo[$count]='['.$ts.', '.$obj['io']['bo'].']';
elseif($type == "io" && $field == "bi")
	$vet_time_io_bi[$count]='['.$ts.', '.$obj['io']['bi'].']';
elseif($type == "swap" && $field == "so")
	$vet_time_swap_so[$count]='['.$ts.', '.$obj['swap']['so'].']';
elseif($type == "swap" && $field == "si")
	$vet_time_swap_si[$count]='['.$ts.', '.$obj['swap']['si'].']';
elseif($type == "network" && $field == "txSum")
	$vet_time_network_txSum[$count]='['.$ts.', '.$obj['network']['txSum'].']';
elseif($type == "network" && $field == "rxSum")
	$vet_time_network_rxSum[$count]='['.$ts.', '.$obj['network']['rxSum'].']';
elseif($type == "procs" && $field == "b")
	$vet_time_procs_b[$count]='['.$ts.', '.$obj['procs']['b'].']';
elseif($type == "procs" && $field == "r")
	$vet_time_procs_r[$count]='['.$ts.', '.$obj['procs']['r'].']';


//$ar = sizeof(iterator_to_array($cursor));
//echo $ar."<br><br>";
//echo $count . " - ". sizeof($cursor) . "<br>";


//if($count != sizeof(iterator_to_array($cursor))){
	if($cursor->hasNext() == true){
if($type == "memory" && $field == "free")
		$vet_time[$count]=$vet_time[$count].',';
elseif($type == "memory" && $field == "cache")
		$vet_time_mem_cache[$count]=$vet_time_mem_cache[$count].',';
elseif($type == "memory" && $field == "buff")
		$vet_time_mem_buff[$count]=$vet_time_mem_buff[$count].',';
elseif($type == "cpu" && $field == "id")
		$vet_time_cpu_id[$count]=$vet_time_cpu_id[$count].',';
elseif($type == "cpu" && $field == "us")
		$vet_time_cpu_us[$count]=$vet_time_cpu_us[$count].',';
elseif($type == "cpu" && $field == "sy")
		$vet_time_cpu_sy[$count]=$vet_time_cpu_sy[$count].',';
elseif($type == "cpu" && $field == "wa")
		$vet_time_cpu_wa[$count]=$vet_time_cpu_wa[$count].',';
elseif($type == "io" && $field == "bo")
		$vet_time_io_bo[$count]=$vet_time_io_bo[$count].',';
elseif($type == "io" && $field == "bi")
		$vet_time_io_bi[$count]=$vet_time_io_bi[$count].',';
elseif($type == "swap" && $field == "so")
		$vet_time_swap_so[$count]=$vet_time_swap_so[$count].',';
elseif($type == "swap" && $field == "si")
		$vet_time_swap_si[$count]=$vet_time_swap_si[$count].',';
elseif($type == "network" && $field == "rxSum")
		$vet_time_network_rxSum[$count]=$vet_time_network_rxSum[$count].',';
elseif($type == "network" && $field == "txSum")
		$vet_time_network_txSum[$count]=$vet_time_network_txSum[$count].',';
elseif($type == "procs" && $field == "b")
		$vet_time_procs_b[$count]=$vet_time_procs_b[$count].',';
elseif($type == "procs" && $field == "r")
		$vet_time_procs_r[$count]=$vet_time_procs_r[$count].',';

	}
//}
//echo $vet_time[$count];
$count++;
}
if($type == "memory" && $field == "free")
sort($vet_time);
elseif($type == "memory" && $field == "cache")
sort($vet_time_mem_cache);
elseif($type == "memory" && $field == "buff")
sort($vet_time_mem_buff);
elseif($type == "cpu" && $field == "id")
sort($vet_time_cpu_id);
elseif($type == "cpu" && $field == "us")
sort($vet_time_cpu_us);
elseif($type == "cpu" && $field == "sy")
sort($vet_time_cpu_sy);
elseif($type == "cpu" && $field == "wa")
sort($vet_time_cpu_wa);
elseif($type == "io" && $field == "bo")
sort($vet_time_io_bo);
elseif($type == "io" && $field == "bi")
sort($vet_time_io_bi);
elseif($type == "swap" && $field == "so")
sort($vet_time_swap_so);
elseif($type == "swap" && $field == "si")
sort($vet_time_swap_si);
elseif($type == "network" && $field == "rxSum")
sort($vet_time_network_rxSum);
elseif($type == "network" && $field == "txSum")
sort($vet_time_network_txSum);
elseif($type == "procs" && $field == "b")
sort($vet_time_procs_b);
elseif($type == "procs" && $field == "r")
sort($vet_time_procs_r);
//echo $vet_time[0];
?>

<?

if($type == "io" && $field == "bo")
	$data_stats = $vet_time_io_bo;
elseif($type == "io" && $field == "bi")
	$data_stats = $vet_time_io_bi;
elseif($type == "cpu" && $field == "id")
	$data_stats = $vet_time_cpu_id;
elseif($type == "cpu" && $field == "us")
	$data_stats = $vet_time_cpu_us;
elseif($type == "cpu" && $field == "wa")
	$data_stats = $vet_time_cpu_wa;
elseif($type == "cpu" && $field == "sy")
	$data_stats = $vet_time_cpu_sy;
elseif($type == "memory" && $field == "free")
	$data_stats = $vet_time;
elseif($type == "memory" && $field == "cache")
	$data_stats = $vet_time_mem_cache;
elseif($type == "memory" && $field == "buff")
	$data_stats = $vet_time_mem_buff;
elseif($type == "swap" && $field == "so")
	$data_stats = $vet_time_swap_so;
elseif($type == "swap" && $field == "si")
	$data_stats = $vet_time_swap_si;
elseif($type == "network" && $field == "txSum")
	$data_stats = $vet_time_network_txSum;
elseif($type == "network" && $field == "rxSum")
	$data_stats = $vet_time_network_rxSum;
elseif($type == "procs" && $field == "b")
	$data_stats = $vet_time_procs_b;
elseif($type == "procs" && $field == "r")
	$data_stats = $vet_time_procs_r;

$v= $data_stats[count($data_stats)-1];
$data_stats[count($data_stats)-1] = str_replace("],","]",$v);
//$json="[";
$json = "";
foreach($data_stats as $data){
	$json = $json.$data;
}
//$json = $json."]";

//echo $vet_time[1];
$i=0;
//echo json_encode($data_stats);

	//echo $json;

	$str=str_replace("][", "],[", $json);//corrige problema com a criacao do json - nao sei pq acontece :(
	//echo $str;
	echo str_replace("],]", "]]", $str,$count);


//foreach($vet_time as $time){
//	echo $time;
//}
}
else
//	echo '[0,0]';
	echo "null";



?>


<?php
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
error_reporting(E_ALL);
ini_set('display_errors', True);
//header('Content-Type: application/json');
require '../jsonwrapper/jsonwrapper.php';
include '../mongoconnection.php';

  // execute query
  // retrieve all documents
$itoken = $_GET['itoken'];
$type = $_GET['type'];
$field = $_GET['field'];

$criteria = array(
'InstanceToken' => $itoken);

  $cursor = $collection->find($criteria);

//$cursor->sort(array('time' => 1));
?>
<?

$count=0;
  foreach ($cursor as $obj) {

if($obj['date']['month'] == "Jan")
	$month = "Jan";
elseif($obj['date']['month'] == "Fev")
	$month = "Feb";
elseif($obj['date']['month'] == "Mar")
	$month = "Mar";
elseif($obj['date']['month'] == "Abr")
	$month = "Apr";
elseif($obj['date']['month'] == "Mai")
	$month = "May";
elseif($obj['date']['month'] == "Jun")
	$month = "Jun";
elseif($obj['date']['month'] == "Jul")
	$month = "Jul";
elseif($obj['date']['month'] == "Ago")
	$month = "Aug";
elseif($obj['date']['month'] == "Set")
	$month = "Sep";
elseif($obj['date']['month'] == "Out")
	$month = "Oct";
elseif($obj['date']['month'] == "Nov")
	$month = "Nov";
elseif($obj['date']['month'] == "Dez")
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

$time_UTC = $month.' '.$obj['date']['day'].','. ' '.$obj['date']['year'].' '.$obj['date']['time']. ' '.$obj['date']['type'];
$ts = strtotime($time_UTC)*1000;//converte de UTC para Unix timestamp - multiplica por 1000 pra pegar o javascript timestamp;

date_default_timezone_set("UTC");

//Filtra para as ultimas 5 horas
if(time()-$ts/1000<=18000){

//echo 'timestamp: '.$ts.'<br>';
//echo $time_UTC.'<br>';
	$vet_time[$count]='['.$ts.', '.$obj['memory']['free'].']';
	$vet_time_cpu_us[$count]='['.$ts.', '.$obj['cpu']['id'].']';
	$vet_time_io_bo[$count]='['.$ts.', '.$obj['io']['bo'].']';
	$vet_time_swap_so[$count]='['.$ts.', '.$obj['swap']['so'].']';

//$ar = sizeof(iterator_to_array($cursor));
//echo $ar."<br><br>";
//echo $count . " - ". sizeof($cursor) . "<br>";


//if($count != sizeof(iterator_to_array($cursor))){
	if($cursor->hasNext() == true){
		$vet_time[$count]=$vet_time[$count].',';
		$vet_time_cpu_us[$count]=$vet_time_cpu_us[$count].',';
		$vet_time_io_bo[$count]=$vet_time_io_bo[$count].',';
		$vet_time_swap_so[$count]=$vet_time_swap_so[$count].',';
	}
}
//echo $vet_time[$count];
$count++;
}
sort($vet_time);
sort($vet_time_cpu_us);
sort($vet_time_io_bo);
sort($vet_time_swap_so);

//echo $vet_time[0];
?>

<?

if($type == "io" && $field == "bo")
	$data_stats = $vet_time_io_bo;
elseif($type == "cpu" && $field == "id")
	$data_stats = $vet_time_cpu_us;
elseif($type == "memory" && $field == "free")
	$data_stats = $vet_time;
elseif($type == "swap" && $field == "so")
	$data_stats = $vet_time_swap_so;

$json="[";
foreach($data_stats as $data){
	$json = $json.$data;
}
$json = $json."]";


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
?>


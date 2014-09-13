<? ob_start(); ?>
<?php
/******************************************************************************
 *
 * vmstat.php - Show stored OS data in a table form.
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
ini_set('max_execution_time', 300); //300 seconds = 5 minutes

require '../jsonwrapper/jsonwrapper.php';
include '../mongoconnection.php';
include "../mysqlconnection.php";
include_once "../Archive/functions.php";

$html = '';
#$html = $html . '<link rel="stylesheet" href="./pure-min.css">';


$css = '<style type="text/css">'.file_get_contents('./pure-min.css'). '</style>';
$html = $html.$css;
$html = $html ."Server analytic data: <br><br>";
function mmmr($array, $output = 'mean'){ 
    if(!is_array($array)){ 
        return FALSE; 
    }else{ 
        switch($output){ 
            case 'mean': 
                $count = count($array); 
                $sum = array_sum($array); 
                $total = $sum / $count; 
            break; 
            case 'median': 
                rsort($array); 
                $middle = round(count($array) / 2); 
                $total = $array[$middle-1]; 
            break; 
            case 'mode': 
                $v = array_count_values($array); 
                arsort($v); 
                foreach($v as $k => $v){$total = $k; break;} 
            break; 
            case 'range': 
                sort($array); 
                $sml = $array[0]; 
                rsort($array); 
                $lrg = $array[0]; 
                $total = $lrg - $sml; 
            break; 
        } 
        return $total; 
    } 
} 

function calculate_mmr($array){


	$values = array();

	// Mean = The average of all the numbers 
	array_push($values, mmmr($array, 'mean')); 
	array_push($values, stats_standard_deviation($array));

	// Median = The middle value after the numbers are sorted smallest to largest 
	array_push($values, mmmr($array, 'median'));

	// Mode = The number that is in the array the most times 
	array_push($values, mmmr($array, 'mode'));

	array_push($values, max($array));
	array_push($values,min($array));
	// Range = The difference between the highest number and the lowest number 
	array_push($values, mmmr($array, 'range'));

	return $values;
}

function insert_sql($type, $input_val, $start, $end, $itoken){
#		array_push("INSERT into '.$table.' VALUES (

	$sql = '';
	$size =  count($input_val);
	$cnt = 0; 
	foreach($input_val as $i){

		if($cnt < ($size-1))
			$sql = $sql.'\''.$i.'\', ';
		else
			$sql = $sql.'\''.$i.'\'';
		$cnt++;
	}

	$sql = "INSERT INTO stats VALUES('$type', '$start','$end','$itoken', ".$sql.")";
	return $sql;

}

function insert_sql_disk($end, $itoken, $pct, $mount){

	$sql = array();

	$size = count($pct);
	$count = 0;
	while($count != $size){

		array_push($sql, "INSERT INTO stat_disk_usage VALUES ('$end', '$itoken', '$mount[$count]', '$pct[$count]')");
		$count++;
	}

	return $sql;
}

function SQL_query($array){

	foreach($array as $query){
		$do_query = mysql_query($query);
		if (!$do_query){
			echo 'Error while inserting in the db:<br>'.$query.'<br>';
			echo "Error creating database: " . mysql_error(). "<br>";}
	}

}

function stats_standard_deviation(array $a, $sample = false) {
        $n = count($a);
        if ($n === 0) {
            trigger_error("The array has zero elements", E_USER_WARNING);
            return false;
        }
        if ($sample && $n === 1) {
            trigger_error("The array has only 1 element", E_USER_WARNING);
            return false;
        }
        $mean = array_sum($a) / $n;
        $carry = 0.0;
        foreach ($a as $val) {
            $d = ((double) $val) - $mean;
            $carry += $d * $d;
        };
        if ($sample) {
           --$n;
        }
        return sqrt($carry / $n);
}

function open_html_table($itoken, $fields){
	$html =  '
	<table class="pure-table">
		<thead>
        	<tr>
	            <th>'.getAlias($itoken).'</th>';
			foreach ($fields as $fd)
        	    		$html = $html . '<th>'.$fd.'</th>';

	 $html = $html .' </tr>
		</thead>
		<tbody>';

	return $html;
}

function close_html_table(){
	return '   </tbody>
     		</table>';
}


function print_html($array, $name){

	$size = count($array);
	$cnt = 0;
        $html =  '<tr>
		<td>'.$name.'</td>';
		while($cnt != $size){
			$html = $html . '<td>'.round($array[$cnt]).'</td>';
			$cnt++;
		}
       $html = $html . ' </tr>';
	return $html;
}
#$itoken = safe($_GET['itoken']);
#$itoken="C.JkxPpgXco3TGVDtCdm5O/tbaCuJrmmhC81wtaJKqp8cpskhuZlS";
if (isset($_SESSION['email']) == false) {//check if user is login;
    echo "<script>window.location='../../Archive/index.php';</script>";
        die();
}

$start_date = "2014-09-08 00:00:00";
$end_date = "2014-09-14 00:00:00";

$html = $html . "Start: ".$start_date." End: ".$end_date."<br>";
$html = $html . "<br>";


$inst_query = mysql_query("SELECT * FROM instance WHERE email='".$_SESSION["email"]."'");
while ($inst= mysql_fetch_array($inst_query)) {

	$itoken = $inst['instanceID'];
	$start = new MongoDate(strtotime($start_date));
	$end = new MongoDate(strtotime($end_date));
	$cursor = $collection->find(array('InstanceToken' => $itoken, "timestamp" => array('$gte' => $start,'$lt' => $end)));

	/*
	$cursor->sort( array( 'name' => 1 ) );
	$result = $cursor->getNext();*/

	#$UsedMemArray = array();#In Mb
	$MemFreeArray = array();
	$MemBuffArray = array();
	$MemCacheArray = array();
	$MemSwpdArray = array();
	$CPUIdleArray = array();#in %
	$CPUUsArray = array();
	$CPUSyArray = array();
	$CPUWaArray = array();
	$DiskReadArray = array();
	$DiskWriteArray = array();
	$NetTXArray = array();
	$NetRXArray = array();
	$SwapInArray = array();
	$SwapOutArray = array();
	$ProcRArray = array();
	$ProcBArray = array();


	$FSArray = array();
	$FSmount = array();
	foreach ($cursor as $document) {
#		array_push($UsedMemArray,$document["hardware"]["mem"]["size_mb"] - $document['memory']['free']);
		array_push($MemFreeArray, $document["memory"]["free"]);
		array_push($MemBuffArray, $document["memory"]["buff"]);
		array_push($MemCacheArray, $document["memory"]["cache"]);
		array_push($MemSwpdArray, $document["memory"]["swpd"]);
		array_push($CPUIdleArray,$document["cpu"]["id"]);
		array_push($CPUUsArray, $document["cpu"]["us"]);
		array_push($CPUSyArray, $document["cpu"]["sy"]);
		array_push($CPUWaArray, $document["cpu"]["wa"]);
		array_push($DiskReadArray,$document["io"]["bi"]);
		array_push($DiskWriteArray, $document["io"]["bo"]);
		array_push($NetTXArray, $document["network"]["txSum"]);
		array_push($NetRXArray, $document["network"]["rxSum"]);
		array_push($SwapInArray, $document["swap"]["si"]);
		array_push($SwapOutArray, $document["swap"]["so"]);
		array_push($ProcRArray, $document["procs"]["r"]);
		array_push($ProcBArray, $document["procs"]["b"]);



		

		$size = count($document["hardware"]["fs"]["pct"]);
		$count = 0;

		$tmp_fs_array = array();
		$tmp_mount_array = array();
		while($count != $size){
			$var = explode("%",$document["hardware"]["fs"]["pct"][$count]);
			$arr[1] = $document["hardware"]["fs"]["mount"][$count];
			array_push($tmp_fs_array, $var[0]);
			array_push($tmp_mount_array, $arr[1]);
			$count++;
		}
		$FSArray = $tmp_fs_array;
		$FSmount = $tmp_mount_array;
		
	}

	$fields = array('Mean', 'Standart Deviation', 'Median', 'Mode', 'High', 'Low', 'Range [High...Low]');

	$html = $html . open_html_table($itoken, $fields);
	$cpuid_array = calculate_mmr($CPUIdleArray);
	$html = $html . print_html($cpuid_array, "CPU Idle (%)");
	$cpuus_array = calculate_mmr($CPUUsArray);
	$html = $html . print_html($cpuus_array, "CPU User (%)");
	$cpusy_array = calculate_mmr($CPUSyArray);
	$html = $html . print_html($cpusy_array, "CPU System (%)");
	$cpuwa_array = calculate_mmr($CPUWaArray);
	$html = $html . print_html($cpuwa_array, "CPU Wait (%)");
	


	$memfree_array = calculate_mmr($MemFreeArray);
	$html = $html . print_html($memfree_array, "Memory Free (Mb)");

	$memcache_array = calculate_mmr($MemCacheArray);
	$html = $html . print_html($memcache_array, "Memory Cache (Mb)");

	$membuff_array = calculate_mmr($MemBuffArray);
	$html = $html . print_html($membuff_array, "Memory Buffer (Mb)");

	$memswpd_array = calculate_mmr($MemSwpdArray);
	$html = $html . print_html($memswpd_array, "Memory Swap");

	$diskread_array = calculate_mmr($DiskReadArray);
	$html = $html . print_html($diskread_array, "Disk Read/Input (block/s)");

	$diskwrite_array = calculate_mmr($DiskWriteArray);
	$html = $html .print_html($diskwrite_array, "Disk Write/Output (block/s)");

	$nettx_array = calculate_mmr($NetTXArray);
	$html = $html . print_html($nettx_array, "Network Sent/TX (KB/s)");

	$netrx_array = calculate_mmr($NetRXArray);
	$html =  $html .print_html($netrx_array, "Network Received/RX (KB/s)");

	$swapin_array = calculate_mmr($SwapInArray);
	$html = $html . print_html($swapin_array, "Swap In");

	$swapout_array = calculate_mmr($SwapOutArray);
	$html = $html . print_html($swapout_array,"Swap Out");

	$procr_array = calculate_mmr($ProcRArray);
	$html = $html .print_html($procr_array, "Process waiting");

	$procb_array = calculate_mmr($ProcBArray);
	$html = $html . print_html($procb_array, "Process Sleep");


	$html = $html . close_html_table();

	$html = $html . "<br>";

	$fields = array('Percentage',);
	$html = $html . open_html_table($itoken, $fields);
	$size = count($FSArray);
	$cnt = 0;
	while($cnt != $size){
		$var = array( strval($FSArray[$cnt]));
		$html = $html . print_html($var,$FSmount[$cnt]);
		$cnt++;
	}

	$html = $html . close_html_table(). "<br>";

	$insert	= array();
	array_push($insert, insert_sql("cpuid", $cpuid_array, $start_date, $end_date, $itoken));
	array_push($insert, insert_sql("cpuus", $cpuus_array, $start_date, $end_date, $itoken));
	array_push($insert, insert_sql("cpusy", $cpusy_array, $start_date, $end_date, $itoken));
	array_push($insert, insert_sql("cpuwa", $cpuid_array, $start_date, $end_date, $itoken));
	array_push($insert, insert_sql("memfree", $memfree_array, $start_date, $end_date, $itoken));
	array_push($insert, insert_sql("memswpd", $memswpd_array, $start_date, $end_date, $itoken));
	array_push($insert, insert_sql("membuff", $membuff_array, $start_date, $end_date, $itoken));
	array_push($insert, insert_sql("memcache", $memcache_array, $start_date, $end_date, $itoken));
	array_push($insert, insert_sql("disk_read", $diskread_array, $start_date, $end_date, $itoken));
	array_push($insert, insert_sql("disk_write", $diskwrite_array, $start_date, $end_date, $itoken));
	array_push($insert, insert_sql("nettx", $nettx_array, $start_date, $end_date, $itoken));
	array_push($insert, insert_sql("netrx", $netrx_array, $start_date, $end_date, $itoken));
	array_push($insert, insert_sql("swapin", $swapin_array, $start_date, $end_date, $itoken));
	array_push($insert, insert_sql("swapout", $swapout_array, $start_date, $end_date, $itoken));

	SQL_query($insert);

	SQL_query(insert_sql_disk($end_date, $itoken, $FSArray, $FSmount));


}

	echo $html;


	#require './PHPMailer/connection.php';
	require '../alert/PHPMailer/connection_smtp_no_auth.php'; 

	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	

	$mail->addAddress($_SESSION['email']);     // Add a recipient
	//$mail->addAddress('ellen@example.com');               // Name is optional
	//$mail->addReplyTo('info@example.com', 'Information');
	//$mail->addCC('cc@example.com');
	//$mail->addBCC('bcc@example.com');

	$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
	//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name


	$mail->Subject = 'Ctracker - Analytic';
	$mail->IsHTML(true);
	$mail->msgHTML(html_entity_decode($html));
	//This is the body in plain text for non-HTML mail clients
	$mail->AltBody = $html;
	echo "<br>";
	if(!$mail->send()) {
	    echo 'Message could not be sent to: '.$_SESSION['email']."<p>";
	    echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
	    echo 'Message has been sent to: '.$_SESSION['email']."<p>";
	}

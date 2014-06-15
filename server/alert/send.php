<?php
require '../jsonwrapper/jsonwrapper.php';
include '../mongoconnection.php';
include '../mongofunctions.php';
include "../mysqlconnection.php";
include_once "../Archive/functions.php";



function compare($filter, $data, $operator){

	$result = false;
	if($operator != "disabled"){

		switch ($operator) {
			case "greater":
				if ($filter > $data)
					$result = true;
				break;

			case "less":
				if($filter < $data)
					$result = true;
				break;
			
			case "equal":
				if($filter == $data)
					$result = true;
				break;

//			default:

	}
}
	return $result;

}


$user_query = mysql_query("SELECT * FROM instance GROUP BY email");
while ($user = mysql_fetch_array($user_query)) {


//list enabled alerts;
$query = "SELECT a.* FROM `instance_alert` as a, instance as b WHERE `option-status`=1 AND a.instanceID = b.instanceID AND b.email='".$user['email']."'";
$inst_query = mysql_query($query);
$date = date('m/d/Y h:i:s a', time());
$message = "Ctracker<p>".$date."<p>Alerts:<p><p>";

while ($inst= mysql_fetch_array($inst_query)) {
	$message = $message."Server: ". getAlias($inst['instanceID'])."<p>";
	$op_status = $inst['option-status'];	
	if($op_status == 1){
////

                        $criteria = array('InstanceToken' => $inst['instanceID']);


                $end = getTimestampByIndex(-1, $collection, $criteria);//get the last document inserted;
        //              $cursor = $collection->find(array('InstanceToken' => array('$in' => $inst_stack) ,"timestamp" => $end));
                if($end!= '{}'){
                        $cursor = $collection->find(array('InstanceToken' => $inst['instanceID'] ,"timestamp" => $end));

                date_default_timezone_set("UTC");
                $ts = date('Y-m-d H:i:s e', $end->sec);
		$now = date('Y-m-d H:i:s e', time());
                //$ts = strtotime("-5 minutes",strtotime($ts));
		if(strtotime($ts) >= (strtotime("-5 minutes", strtotime($now)))){
			

////

/*
	        $criteria = array('InstanceToken' => $inst['instanceID']);

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
		$cursor = $collection->find(array('InstanceToken' => $inst['instanceID'], "timestamp" => array('$gte' => $start)));


//        	$end = getTimestampByIndex(-1, $collection, $criteria);//get the last document inserted;

	        if($start == '{}'){
        	        echo 'No data found.';
                	exit;}*/

//	        $cursor = $collection->find(array('InstanceToken' => $inst['instanceID'] ,"timestamp" => $end));
        	$obj=$cursor->getNext();
//for each one, check restrictions and add message;

		$proc_r = $inst['proc-r'];
		$last_proc_r = $obj['procs']['r'];
		$op_proc_r= $inst['op-proc-r'];
		if(compare($proc_r, $last_proc_r, $op_proc_r)==true)
			$message = $message."Proc:r - ".$last_proc_r."<p>";

		$proc_b = $inst['proc-b'];
		$last_proc_b = $obj['procs']['b'];
		$op_proc_b = $inst['op-proc-b'];
		if(compare($proc_b, $last_proc_b, $op_proc_b)==true)
			$message = $message."Proc:b - ".$last_proc_b."<p>";

		$mem_swpd = $inst['mem-swpd'];
		$last_mem_swpd = $obj['memory']['swpd'];
		$op_mem_spwd = $inst['op-proc-b'];
		if(compare($mem_swpd, $last_mem_swpd, $op_mem_spwd) == true)
			$message = $message."Memory:swpd - ".$last_proc_b." Mb<p>";


		$mem_free = $inst['mem-free'];
		$last_mem_free = $obj['memory']['free'];
		$op_mem_free = $inst['op-mem-free'];
		if(compare($mem_free, $last_mem_free, $op_mem_free) == true)
			$message = $message."Memory:free - ".$last_mem_free." Mb<p>";

		$mem_buff = $inst['mem-buff'];
		$last_mem_buff = $obj['memory']['buff'];
		$op_mem_buff= $inst['op-mem-buff'];
		if(compare($mem_buff, $last_mem_buff, $op_mem_buff) == true)
			$message = $message."Memory:buff - ".$last_mem_buff."Mb<p>";

		$mem_cache = $inst['mem-cache'];
		$last_mem_cache = $obj['memory']['cache'];
		$op_mem_cache= $inst['op-mem-cache'];
		if(compare($mem_cache, $last_mem_cache, $op_mem_cache) == true)
			$message = $message."Memory:cache - ".$last_mem_cache."<p>";

		$swap_si = $inst['swap-si'];
		$last_swap_si = $obj['swap']['si'];
		$op_swap_si= $inst['op-swap-si'];
		if(compare($swap_si, $last_swap_si, $op_swap_si) == true)
			$message = $message."Swap:si - ".$last_swap_si."Mb<p>";

		$swap_so = $inst['swap-so'];
		$last_swap_so = $obj['swap']['so'];
		$op_swap_so= $inst['op-swap-so'];
		if(compare($swap_so, $last_swap_so, $op_swap_so) == true)
			$message = $message."Swap:so" - $last_swap_so."Mb<p>";

		$io_bi = $inst['io-bi'];
		$last_io_bi = $obj['io']['bi'];
		$op_io_bi= $inst['op-io-bi'];
		if(compare($io_bi, $last_io_bi, $op_io_bi) == true)
			$message = $message."IO:bi - ".$last_io_bi."<p>";

		$io_bo = $inst['io-bo'];
		$last_io_bo = $obj['io']['bo'];
		$op_io_bo= $inst['op-io-bo'];
		if(compare($io_bo, $last_io_bo, $op_io_bo) == true)
			$message = $message."IO:bo - ".$last_io_bo."<p>";

		$system_in = $inst['system-in'];
		$last_system_in = $obj['system']['in'];
		$op_system_in= $inst['op-system-in'];
		if(compare($system_in, $last_system_in, $op_system_in) == true)
			$message = $message."System:in: - ".$last_system_in."<p>";

		$system_cs = $inst['system-cs'];
		$last_system_cs = $obj['system']['cs'];
		$op_system_cs= $inst['op-system-cs'];
		if(compare($system_cs, $last_system_cs, $op_system_cs) == true)
			$message = $message."System:cs - ".$last_system_cs."<p>";

		$cpu_us = $inst['cpu-us'];
		$last_cpu_us = $obj['cpu']['us'];
		$op_cpu_us= $inst['op-cpu-us'];
		if(compare($cpu_us, $last_cpu_us, $op_cpu_us) == true)
			$message = $message."CPU:us - ".$last_cpu_us."<p>";

		$cpu_sy = $inst['cpu-sy'];
		$last_cpu_sy = $obj['cpu']['sy'];
		$op_cpu_sy= $inst['op-cpu-sy'];
		if(compare($cpu_sy, $last_cpu_sy, $op_cpu_sy) == true)
			$message = $message."CPU:sy - ".$last_cpu_sy."<p>";

		$cpu_id = $inst['cpu-id'];
		$last_cpu_id = $obj['cpu']['id'];
		$op_cpu_id= $inst['op-cpu-id'];
		if(compare($cpu_id, $last_cpu_id, $op_cpu_id) == true)
			$message = $message."CPU:id - ".$last_cpu_id."<p>";

		$cpu_wa = $inst['cpu-wa'];
		$last_cpu_wa = $obj['cpu']['wa'];
		$op_cpu_wa= $inst['op-cpu-wa'];
		if(compare($cpu_wa, $last_cpu_wa, $op_cpu_wa) == true)
			$message = $message."CPU:wa - ".$last_cpu_wa."<p>";

		$net_rxSum = $inst['net-rxSum'];
		$last_net_rxSum = $obj['network']['rxSum'];
		$op_net_rxSum= $inst['op-net-rxSum'];
		if(compare($net_rxSum, $last_net_rxSum, $op_net_rxSum) == true)
			$message = $message."Network:rxSum - ".$last_net_rxSum."<p>";

		$net_txSum = $inst['net-txSum'];
		$last_net_txSum = $obj['network']['txSum'];
		$op_net_txSum= $inst['op-net-txSum'];
		if(compare($net_txSum, $last_net_txSum, $op_net_txSum) == true)
			$message = $message."Network:txSum - ".$last_net_txSum."<p>";



		$process_status = $inst['process-status'];
		$process_cpu = $inst['process-cpu'];
		$process_mem = $inst['process-mem'];
		$op_process_cpu= $inst['op-process-cpu'];
		$op_process_mem= $inst['op-process-mem'];

		foreach ($obj['process']['plist'] as $p) {

			$last_process_name = $p['name'];
			$last_process_status = $p['status'];

			$tmp_msg = "Processes<p>";
			$changed = false;
			if($last_process_status == "Stopped" && $process_status == "Stopped"){
				$tmp_msg = $tmp_msg."Name: ".$last_process_name." Status: ".$last_process_status.'<p>';
				$changed = true;
			}elseif($last_process_status == "Sleeping" && $process_status == "Sleeping"){

                                $tmp_msg = $tmp_msg."Name: ".$last_process_name." Status: ".$last_process_status.'<p>';
                                $changed = true;

			} 
			elseif($last_process_status == "Running" && $process_status =="Running"){
				$last_process_cpu = $p['cpu'];
				$last_process_mem = $p['mem'];
				$last_process_pid = $p['pid'];
				$tmp_msg = "PPID: ".$last_process_pid. " - ".$last_process_name."<p>";
				if(compare($process_cpu, $last_process_cpu, $op_process_cpu) == true){
					$tmp_msg = $tmp_msg."	CPU: ".$last_process_cpu."<p>";
					$changed = true;
				}
				if(compare($process_mem, $last_process_mem, $op_process_mem) == true){
					$tmp_msg = $tmp_msg."	Memory: ".$last_process_mem."<p>";
					$changed = true;
				}
			}
			if($changed)
				$message = $message.$tmp_msg;
		}



		$fs_used = $inst['fs-used'];
		$fs_used_pct = str_replace("%","",$fs_used);

		$device_list = $obj['hardware']['fs']['device'];
		$device_pct = $obj['hardware']['fs']['pct'];
		$i=0;
		$size = count($device_list);
		while($i != $size){

			$dev = $device_list[$i];
			$pct = str_replace("%","",$device_pct[$i]);

			if($pct > $fs_used_pct){
				$message = $message."Device path: ".$dev." - Disk usage: ".$pct."%<p>";
			}
			$i++;

		}

	}else{
		$message = $message."Unable to contact server for more than 5 minutes<p>";

		$obj=$cursor->getNext();
#print_r($obj);
		$message = $message."Last recorded communcation: " .date('Y-m-d H:i:s', $obj['timestamp']->sec)."<p>";
	}
	}
	}
}

echo $message;
//send it!
require './PHPMailer/connection.php';

$mail->addAddress($user['email'], $user['Alias']);     // Add a recipient
//$mail->addAddress('ellen@example.com');               // Name is optional
//$mail->addReplyTo('info@example.com', 'Information');
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Ctracker - alert';
$mail->Body    = $message;
//This is the body in plain text for non-HTML mail clients
$mail->AltBody = $message;

if(!$mail->send()) {
    echo 'Message could not be sent to: '.$user['email']."<p>";
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent to: '.$user['email']."<p>";
}

}
?>

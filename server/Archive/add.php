<?
/******************************************************************************
 *
 * add.php - Class used to handle the insertion/add of new users and instances.
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
$tp = (isset($_POST["tp"])) ? $_POST["tp"] : $_GET["tp"];

if (strcmp($tp, "signup") == 0) {

	$hasher = new PasswordHash(8, false);
	$email = filter_var(safe($_POST['email']) , FILTER_VALIDATE_EMAIL);

	if($email == false){
		$insert = "";
		echo "arg. wrong";
	}else{

		$password = safe($_POST['password']);

		if (strlen($password) > 72) {
			die("Password must be 72 characters or less");
		}

		// The $hash variable will contain the hash of the password
		$hash = $hasher->HashPassword($password);

		if (strlen($hash) >= 20) {

			$pass = $hash;
			$insert = "INSERT INTO user (email, pass, date_register) values ('$email','$pass', Now())";
		}
		else
			$insert = "";
	}
}
elseif (strcmp($tp, "add-alert") == 0) {
/*foreach ($_POST as $key => $value){
  echo "{$key} = {$value}\r\n";
}*/

	$instanceID = $_POST['instanceID'];
	$option_status = $_POST['option-status'];
	if($option_status == 'Actived')
		$status = 1;
	elseif($option_status = 'Inactivated')
		$status = 0;
	else
		$status = 1;
	$proc_r = $_POST['proc-r'];
	$proc_b = $_POST['proc-b'];
	$mem_swpd = $_POST['mem-swpd'];
	$mem_free = $_POST['mem-free'];
	$mem_buff = $_POST['mem-buff'];
	$mem_cache = $_POST['mem-cache'];
	$swap_si = $_POST['swap-si'];
	$swap_so = $_POST['swap-so'];
	$io_bi = $_POST['io-bi'];
	$io_bo = $_POST['io-bo'];
	$system_in = $_POST['system-in'];
	$system_cs = $_POST['system-cs'];
	$cpu_us = $_POST['cpu-us'];
	$cpu_sy = $_POST['cpu-sy'];
	$cpu_id = $_POST['cpu-id'];
	$cpu_wa	= $_POST['cpu-wa'];
	$net_rxSum = $_POST['net-rxSum'];
	$net_txSum = $_POST['net-txSum'];
	$process_status = $_POST['process-status'];
	$process_cpu = $_POST['process-cpu'];
	$process_mem = $_POST['process-mem'];
	$fs_use = $_POST['fs-used'];
	$op_proc_r = $_POST['op-proc-r'];
	$op_proc_b = $_POST['op-proc-b'];
	$op_mem_swpd = $_POST['op-mem-swpd'];
	$op_mem_free = $_POST['op-mem-free'];
	$op_mem_buff = $_POST['op-mem-buff'];
	$op_mem_cache = $_POST['op-mem-cache'];
	$op_swap_si = $_POST['op-swap-si'];
	$op_swap_so = $_POST['op-swap-so'];
	$op_io_bi = $_POST['op-io-bi'];
	$op_io_bo = $_POST['op-io-bo'];
	$op_system_in = $_POST['op-system-in'];
	$op_system_cs = $_POST['op-system-cs'];
	$op_cpu_us = $_POST['op-cpu-us'];
	$op_cpu_sy = $_POST['op-cpu-sy'];
	$op_cpu_id = $_POST['op-cpu-id'];
	$op_cpu_wa = $_POST['op-cpu-wa'];
	$op_net_rxSum = $_POST['op-net-rxSum'];
	$op_net_txSum = $_POST['op-net-txSum'];
	$op_process_status = $_POST['op-process-status'];
	$op_process_cpu = $_POST['op-process-cpu'];
	$op_process_mem = $_POST['op-process-mem'];
	$op_fs_used = $_POST['op-fs-used'];


	$query = "SELECT * FROM instance_alert WHERE instanceID = '$instanceID'";
	$num_rows = mysql_num_rows(mysql_query($query));

	if($num_rows < 1){
		$insert="INSERT INTO `instance_alert` (`instanceID`, `option-status`, `proc-r`, `proc-b`, `mem-swpd`, `mem-free`, `mem-buff`, `mem-cache`, `swap-si`, `swap-so`, `io-bi`, `io-bo`, `system-in`, `system-cs`, `cpu-us`, `cpu-sy`, `cpu-id`, `cpu-wa`, `net-rxSum`, `net-txSum`, `process-status`, `process-cpu`, `process-mem`, `fs-used`, `op-proc-r`, `op-proc-b`, `op-mem-swpd`, `op-mem-free`, `op-mem-buff`, `op-mem-cache`, `op-swap-si`, `op-swap-so`, `op-io-bi`, `op-io-bo`, `op-system-in`, `op-system-cs`, `op-cpu-us`, `op-cpu-sy`, `op-cpu-id`, `op-cpu-wa`, `op-net-rxSum`, `op-net-txSum`, `op-process-cpu`, `op-process-mem`, `op-fs-used`, `op-process-status`) VALUES ('$instanceID',$status,'$proc_r', '$proc_b', '$mem_swpd', '$mem_free', '$mem_buff', '$mem_cache', '$swap_si', '$swap_so', '$io_bi', '$io_bo', '$system_in', '$system_cs', '$cpu_us', '$cpu_sy', '$cpu_id', '$cpu_wa', '$net_rxSum', '$net_txSum', '$process_status', '$process_cpu', '$process_mem', '$fs_use', '$op_proc_r', '$op_proc_b', '$op_mem_swpd', '$op_mem_free', '$op_mem_buff', '$op_mem_cache', '$op_swap_si', '$op_swap_so', '$op_io_bi', '$op_io_bo', '$op_system_in', '$op_system_cs', '$op_cpu_us', '$op_cpu_sy', '$op_cpu_id', '$op_cpu_wa', '$op_net_rxSum', '$op_net_txSum', '$op_process_cpu', '$op_process_mem' , '$op_fs_used', '$op_process_status')";

	}else{

 $insert="UPDATE instance_alert SET `option-status`='$status', `proc-r`='$proc_r', `proc-b`='$proc_b', `mem-swpd`='$mem_swpd', `mem-free`='$mem_free', `mem-buff`='$mem_buff', `mem-cache`='$mem_cache', `swap-si`='$swap_si', `swap-so`='$swap_so', `io-bi`='$io_bi', `io-bo`='$io_bo', `system-in`='$system_in', `system-cs`='$system_cs', `cpu-us`='$cpu_us', `cpu-sy`='$cpu_sy', `cpu-id`='$cpu_id', `cpu-wa`='$cpu_wa', `net-rxSum`='$net_rxSum', `net-txSum`='$net_txSum', `process-status`='$process_status', `process-cpu`='$process_cpu', `process-mem`='$process_mem', `fs-used`='$fs_use', `op-proc-r`='$op_proc_r', `op-proc-b`='$op_proc_b', `op-mem-swpd`='$op_mem_swpd', `op-mem-free`='$op_mem_free', `op-mem-buff`='$op_mem_buff', `op-mem-cache`='$op_mem_cache', `op-swap-si`='$op_swap_si', `op-swap-so`='$op_swap_so', `op-io-bi`='$op_io_bi', `op-io-bo`='$op_io_bo', `op-system-in`='$op_system_in', `op-system-cs`='$op_system_cs', `op-cpu-us`='$op_cpu_us', `op-cpu-sy`='$op_cpu_sy', `op-cpu-id`='$op_cpu_id', `op-cpu-wa`='$op_cpu_wa', `op-net-rxSum`='$op_net_rxSum', `op-net-txSum`='$op_net_txSum', `op-process-cpu`='$op_process_cpu', `op-process-mem`='$op_process_mem' , `op-fs-used`='$op_fs_used', `op-process-status`='$op_process_status' WHERE `instanceID`='$instanceID'";

	}

}
elseif (strcmp($tp, "add-server") == 0) {

	require("../../phpass/phpass-0.3/PasswordHash.php");

//	$rg_name = "/^[a-zA-Z0-9]+$/";
	$rg_name = "/[a-zA-Z0-9\s\p{P}]/";
	
	if(preg_match($rg_name, $_POST['name']) == 0 || isset($_POST['name']) == false || empty($_POST['name']) == 1) {
		echo 'arg. wrong';
		die();
	}else
		$name = $_POST['name'];


	$email = $_SESSION['email'];
	//$token = uniqid('', true);
        $hasher = new PasswordHash(8, false);
        $token = $hasher->HashPassword(rand(5, 1500).$email.time());
	$token = str_replace("$2a$08$","",$token);

	$insert = "INSERT INTO instance (instanceID, email, Alias) values ('$token','$email', '$name')";

	if($name=="")//if name is empty, we need to guarantee the constrain and so we will invalidate the $insert query;
		$insert = "";
}



//echo $query;
//echo $insert;
//exit();
$query = mysql_query($insert);
  //echo "Error creating database: " . mysql_error();
//	die();
if (!$query)
    echo "<script>window.location='" . basename($_SERVER['PHP_SELF']) . "?pg=message&msg=error-add';</script>";

else {
    if (strcmp($tp, "signup") == 0) {
        session_start();
        $_SESSION["email"] = $email;
        header("Location: index.php");
    }else
	echo "<script>window.location='" . basename($_SERVER['PHP_SELF']) . "?pg=message&msg=confirm-add';</script>";
}
?>

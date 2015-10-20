<?php
	include '../connection.php'; 
	session_start();
	$modulo_usado = 5;
	if (isset($_SESSION['modulo']))
    	$modulo_usado = $_SESSION['modulo'];

	$link = mysqli_connect($db_url, $db_user,$db_pass, $db_name, $db_port);
	if (isset($_GET['i'])){
		$rs = mysqli_query($link,"select ia,ib,ic,va,vb,vc,date_created from sensor_microturbina where modulo_id = $modulo_usado and date_created < '".$_GET['i']."' order by date_created desc limit 10");
	}
	else
		$rs = mysqli_query($link,"select ia,ib,ic,va,vb,vc,date_created from sensor_microturbina where modulo_id = $modulo_usado order by date_created desc limit 10;");
	$first = 0;
	while($row = mysqli_fetch_array($rs)){
		if ($first == 0)
			$first = 1;
		else
			echo "<";
	    for ($i = 0; $i<8;$i++)
	    	echo $row[$i].">";
	}
?>
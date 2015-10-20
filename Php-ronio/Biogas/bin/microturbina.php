<?php
	include '../connection.php'; 
	$link = mysqli_connect($db_url, $db_user,$db_pass, $db_name, $db_port);
	if (isset($_GET['i'])){
		$rs = mysqli_query($link,"select ia,ib,ic,va,vb,vc,flujo,date_created from controlador_microturbina where date_created < '".$_GET['i']."' order by date_created desc limit 10");
	}
	else
		$rs = mysqli_query($link,"select ia,ib,ic,va,vb,vc,flujo,date_created from controlador_microturbina order by date_created desc limit 10;");
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
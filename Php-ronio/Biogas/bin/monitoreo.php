<?php
 //monitoreo.php en el bin....
	
    include '../connection.php';
    $link = mysqli_connect($db_url, $db_user,$db_pass, $db_name, $db_port);
    //Obtiene los últimos valores del monitor biofil
    if($rs = mysqli_query($link, "select * from controlador_biofiltro order by date_created desc limit 1;")){
      $row = mysqli_fetch_array($rs);
      echo $row['h2s'].">".$row['o2'].">".$row['ch4'].">".$row['co2'];
    }
    else
    	echo "";

?>
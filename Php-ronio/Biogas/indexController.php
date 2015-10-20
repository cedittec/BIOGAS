<?php
	session_start(); 

	//asignar el sitio o módulo pedido, si es el caso
    if(isset($_GET['sitio']))
    {
        $_SESSION['sitio'] = $_GET['sitio'];  
    }
    if(isset($_GET['modulo']))
    {
        $_SESSION['modulo'] = $_GET['modulo'];
    }
  
	include 'connection.php';
 	$link = mysqli_connect($db_url, $db_user,$db_pass, $db_name, $db_port); 

	$query = "select * from hibrido_solar where modulo_id = ".$_SESSION['modulo']." order by date_created desc limit 10;";
	$rs = mysqli_query($link, $query);

	$lastData = null;
	$nombres = array();
	$potencias = array();
	$tempEntrada = array();
	$tempSalida = array();
	$highTemps = array();
	$high = 0;
	while ($row = mysqli_fetch_array($rs)){
		if ($lastData == null){
			$lastData = $row;
			$high = highTemp($row);
		}
		array_push($nombres,"     ".substr($row['date_created'],strlen($row['date_created'])-8,8))."     ";
		array_push($potencias, $row['sf_icd_fotovoltaico']*
			$row['sf_icd_fotovoltaico']);
		array_push($tempEntrada,$row['se_temp_entrada_agua']);
		array_push($tempSalida,$row['se_temp_salida_agua']);
		array_push($highTemps,highTemp($row));
	}
	$cadena = join("," ,$nombres)."\n".join("," ,$potencias)."\n".join(",", $tempEntrada)."\n".join(",", $salida)."\n";
	echo "<!--- $cadena  -->";

	function highTemp($temps){
		$alto = 0;
		for ($i = 1; $i<=10;$i++){
			$temp = $temps['sf_temp_panel'.$i];
			if ($temp>$alto)
				$alto = $temp;
		}
		return $alto;
	}
	
?>
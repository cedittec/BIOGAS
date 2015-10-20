<!DOCTYPE html>
<html lang="en">
  
<head>
    <meta charset="utf-8">
    <title>Login - Biogás</title>

	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes"> 
    
	<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />

	<link href="css/font-awesome.css" rel="stylesheet">
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
	    
	<link href="css/style.css" rel="stylesheet" type="text/css">
	<link href="css/pages/signin.css" rel="stylesheet" type="text/css">
</head>


<?php
	//error_reporting( error_reporting() & ~E_NOTICE & ~E_WARNING );

	if($_POST['user']!= ""){ // recibe usuario
		if ($_POST['recordar'] == 1) //hay cookie
		{
			setcookie("userbio", $_POST['user'], time()+2592000);
			$_COOKIE["userbio"] = $_POST['user']; 
		}
		else
		{
			setcookie("userbio", "",-3600);
			$_COOKIE["userbio"] = "";
		}
		if ( isset($_POST['pass'])) { // recibe pass
			
			session_start(); 
		    include 'connection.php';
		    $link = mysqli_connect($db_url, $db_user, $db_pass, $db_name, $db_port); 
		    if(!$link) echo "<script>alert('no link');</script>";
			$query = "select usuario.id, password, usuario.display_name, email, nombre as rol from usuario, rol where usuario.rol_id = rol.id and email ='".$_POST['user']."';";

				$array = mysqli_fetch_array(mysqli_query($link, $query), MYSQL_BOTH);
				$nombre= $array['display_name'];	
				$pass= $array['password'];
				$userID= $array['id'];
				$rol = $array['rol'];

			if($pass != md5($_POST['pass'])) { echo "<script>alert('Contraseña incorrecta');</script>";}
			else //pass correcto
			{
				session_start();
				$_SESSION['idbio'] = $userID;	
				$_SESSION['userbio']=$nombre;
				$_SESSION['typebio'] = $rol;

				header('Location: index.php?elegir=true');
			}
		} else { echo "You must fill in the password";}
	}
?>




<body>
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				 <a class="brand" href="index.php"><img src="img/logo.png"> &nbsp&nbsp&nbsp&nbsp&nbsp Biogás </a>		
			</div> <!-- /container -->
		</div> <!-- /navbar-inner -->
	</div> <!-- /navbar -->

	<div class="account-container">
		<div class="content clearfix">
				<form action= <?= "'".basename($_SERVER['SCRIPT_NAME'])."'" ?> method="post">
						<h1>Inicio de sesión</h1>	
						<hr><br>
						<div class="login-fields">
							<p>Ingrese sus datos</p>
								<div class="field"> <label for="username">Nombre de usuario:</label>
									<input type="text" id = "username" name="user" value="" placeholder="Nombre de usuario" class="login username-field" required>
								</div>
								<div class="field"> <label for="password">Contraseña:</label>
									<input type="password" id = "password" name="pass" value="" placeholder="Contraseña" class="login password-field" required>
								</div>
						</div> <!-- /login-fields -->
						
						<div class="login-actions">
							<br><hr>
								<table width = "100%">
									<tr>
										<td align = "center" style = "vertical-align:top;"><input type="submit" value="Iniciar Sesión"></td> 
										<!-- 
										<td align = "center" style = "vertical-align:top;"><a href="cambio.php">Cambiar contraseña</a></td>
										-->
									</tr>
									<tr>
										<!-- 
										<td colspan = "2" align = "center"><a href = "olvide.php">Olvidé mi contraseña</></td> 
										-->
									</tr>
								</table>
						</div> <!-- .actions -->
				</form>
		</div> <!-- /content -->
	</div> <!-- /account-container -->

	<script src="js/jquery-1.7.2.min.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/signin.js"></script>
</body>

</html>

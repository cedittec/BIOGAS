<!DOCTYPE html>
<html lang="en">
<head>
<?php

    include 'connection.php';

    $link = mysqli_connect($db_url, $db_user,$db_pass, $db_name); 
    if (isset($_POST['delete_id'])){
        //$link->query("delete from usuario_rol where usuario_id = $delete_id ;");        
        //$link->query("delete from usuario_sitio where usuario_id = $delete_id;");
        //$link->query("delete from usuario where id = $update_id;");
        $delete_id = $_POST['delete_id'];
        $link->query("update usuario set visible = 0 where id = $delete_id;");
    }
    if (isset($_POST['update_id'])){
        $update_version =  $_POST['update_version'];
        $update_apellido1 = $_POST['update_apellido1'];
        $update_apellido2 = $_POST['update_apellido2'];
        $update_display_name = $_POST['update_display_name'];
        $update_email = $_POST['update_email'];
        $update_last_updated = date('Y-m-d H:i:s');
        $update_nombre1 = $_POST['update_nombre1'];
        $update_nombre2 = $_POST['update_nombre2'];
        $update_id = $_POST['update_id'];
        $update_sitios = $_POST['update_sitios'];
        $update_rol = $_POST['update_rol'];

        $queryString = "update usuario  set version = $update_version , apellido1 = '$update_apellido1', apellido2 = '$update_apellido2',".
        "display_name = '$update_display_name' , email = '$update_email' , last_updated = '$update_last_updated' , nombre1 = '$update_nombre1',".
        "nombre2 = '$update_nombre2' where id = $update_id";

        echo "<!--- $update_id:\n  $queryString -->";
        
        $link->query($queryString);

        $link->query("delete from usuario_rol where usuario_id = $update_id ;");
        $link->query("insert into usuario_rol(rol_id,usuario_id) values ( $update_rol ,$update_id);");

        $link->query("delete from usuario_sitio where usuario_id = $update_id;");
        if (is_array($_POST['update_sitios'] )){
            for ($i = 0; $i<count($_POST['update_sitios']); $i++){
                $temp = $_POST['update_sitios'][$i];
                $link->query("insert into usuario_sitio(sitio_id,usuario_id) values ( $temp, $update_id );");
        
            }
        }
        else{

            $link->query("insert into usuario_sitio(sitio_id,usuario_id) values (". $_POST['update_sitios'].",$update_id);");
        }
    }
    if (isset($_POST['insert_version'])){
        //Se inserta el nuevo usuario a la base de datos...
        $passCreada = "";

        $punctuacion = "_.#%!";
        $letras = "abcdefghijklmnopqrstuvwxyz";
        $Letras = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $numeros = "1234567890";

        $passCreada .= substr($letras,      rand(0, 25), 1);
        $passCreada .= substr($letras,      rand(0, 25), 1);
        $passCreada .= substr($letras,      rand(0, 25), 1);
        $passCreada .= substr($punctuacion, rand(0, 4), 1);
        $passCreada .= substr($punctuacion, rand(0, 4), 1);
        $passCreada .= substr($Letras,      rand(0, 25), 1);
        $passCreada .= substr($numeros,     rand(0, 9), 1);
        $passCreada .= substr($numeros,     rand(0, 9), 1);
        $passCreada .= substr($letras,      rand(0, 25), 1);
        $passCreada .= substr($numeros,     rand(0, 9), 1);


        $queryString = "insert into usuario(visible,version,apellido1,apellido2".
            ",date_created,display_name,email,enabled,last_updated,nombre1,nombre2,password,password_expired,rol_id) values(1, ".$_POST['insert_version'].
            ", '".$_POST['insert_apellido1']."','".$_POST['insert_apellido2']."', '".date('Y-m-d H:i:s').
            "', '".$_POST['insert_display_name']."', '".$_POST['insert_email']."', 1,  '".date('Y-m-d H:i:s')."', '".
            $_POST['insert_nombre1']."', '".$_POST['insert_nombre2']."', '".
            md5($passCreada)."', 0, ".$_POST['insert_rol'].");";
        
        $link->query($queryString);

        $query =  mysqli_query($link, "select id from usuario where email = '".$_POST['insert_email']."';");
        $array =  mysqli_fetch_array($query);
        $userID = $array['id'];

        echo "<!--- ".$userID.":\n   ".$queryString."-->";

        /*
        $userID = saveUsuario($_POST['insert_version'],0,
            0, $_POST['insert_apellido1'],$_POST['insert_apellido2'],date('Y-m-d H:i:s'),
            $_POST['insert_display_name'],$_POST['insert_email'],1,date('Y-m-d H:i:s'),
            $_POST['insert_nombre1'],$_POST['insert_nombre2'],md5($_POST['insert_password']),0);
        */

        if (is_array($_POST['insert_sitios'] )){
            for ($i = 0; $i<count($_POST['insert_sitios']); $i++){
                $link->query("insert into usuario_sitio(sitio_id,usuario_id,visible) values (".$_POST['insert_sitios'][$i].",".$userID.",1);");
            }
        }
        else{

            $link->query("insert into usuario_sitio(sitio_id,usuario_id,visible) values (".$_POST['insert_sitios'].",".$userID.",1)");
        }


        include 'mailer/mailSender.php';
                sendMail(   
                    //"modulohibrido@gmail.com", "pei214535",
                    "filtro.biogas@gmail.com", "filtrobiogas2015",
                    $_POST['insert_email'],
                    "Hemos creado tu cuenta en Biogas",
                    "Clave: ".$passCreada
        ); // user, pass, to, subject, msg.
    }
?>
<meta charset="utf-8">
<title>Biogás</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600"
        rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/pages/reports.css" rel="stylesheet">
<link href="css/pages/dashboard.css" rel="stylesheet">
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
<?php
    session_start();  
    include "head.php";
?>
<div class="main">
  <div class="main-inner">
    <div class="container">
    	<div class="row">
        	<div class="span12">   
            	<div class="widget">
                	<div class="widget-header">
                    	<i class="icon-user"></i>
	      				<h3>Configuración de usuarios</h3>
	  				</div> <!-- /widget-header -->
                    <div class="widget-content">
                    	<div class="tabbable">
                        	<ul class="nav nav-tabs">
                            	<li class="active"><a href="#crear" data-toggle="tab">Crear usuario</a></li>
						  		<li><a href="#modificar" data-toggle="tab">Modificar usuario</a></li>
                                <li><a href="#eliminar" data-toggle="tab">Eliminar usuario</a></li>
							</ul>
                            <br>
                            <div class="tab-content">
								<div class="tab-pane active" id="crear">
                                    <form id="crear" class="form-horizontal" action = "" method = "POST">
                                        
                                        <fieldset>
                                            <div class="control-group">                                         
                                                <label class="control-label" for="version">Version</label>
                                                <div class="controls">
                                                    <input type="number" name ="insert_version" class="span4" id="codigo">
                                                    
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->

                                            <div class="control-group">											
                                                <label class="control-label" for="username">Nombre de usuario</label>
                                                <div class="controls">
                                                    <input type="text" name= "insert_display_name" class="span6" id="username">
                                                    
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->
                                            
                                            
                                            <div class="control-group">											
                                                <label class="control-label" for="firstname">Nombre</label>
                                                <div class="controls">
                                                    <input type="text" name = "insert_nombre1" class="span6" id="firstname">
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->

                                            <div class="control-group">                                         
                                                <label class="control-label" for="firstname">Nombre 2</label>
                                                <div class="controls">
                                                    <input type="text" name = "insert_nombre2" class="span6" id="firstname">
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->
                                            
                                            
                                            <div class="control-group">											
                                                <label class="control-label" for="lastname">Apellido</label>
                                                <div class="controls">
                                                    <input type="text"  name = "insert_apellido1" class="span6" id="lastname">
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->

                                            <div class="control-group">                                         
                                                <label class="control-label" for="lastname">Apellido 2</label>
                                                <div class="controls">
                                                    <input type="text"  name = "insert_apellido2" class="span6" id="lastname">
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->
                                            
                                            
                                            <div class="control-group">											
                                                <label class="control-label" for="email">Correo electrónico</label>
                                                <div class="controls">
                                                    <input type="text" name = "insert_email" class="span4" id="email">
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->
                                            
                                            
                                            <!--
x
                                            saveUsuario($_POST['insert_version']X,0,
            0, $_POST['insert_apellido1'],
                $_POST['insert_apellido2']),date('Y-m-d H:i:s');
            $_POST['insert_display_name'],$_POST['insert_email'],1,date('Y-m-d H:i:s'),
            $_POST['insert_nombre1'].$_POST['insert_nombre2'],md5($_POST['insert_password']),0);
                                        -->
                                           
                                            <div class="control-group">											
                                                <label class="control-label" for="radiobtns">Tipo de usuario</label>
                                                
                                                  <div class="controls">
                                                   <select name = "insert_rol" value = "1" class="form-control">
                                                        <option value = "1">Administrador del sistema</option>
                                                        <option value = "2">Mantenimiento</option>
                                                        <option value = "3">Administración</option>
                                                        <option value = "4">Cliente</option>
                                                    </select>
                                               </div>		
                                            </div> <!-- /control-group -->
                                            
                                            
                                            
                                           <div class="control-group">
                                               <label class="control-label" for="sitio">Sitio</label>
                                               <div class="controls">
                                                   <select class="form-control" name = "insert_sitios[]" multiple>

                                                    <?php 
                                                     if(!$rs = mysqli_query($link, 'select sitio.display_name, sitio.id from sitio;'))
                                                       {}
                                                      else
                                                        while($fila = mysqli_fetch_array($rs)){
                                                            echo "<option value = '".$fila['id']."'>".$fila['display_name']."</option>\n";
                                                        }
                                                    ?>
                                                    </select>
                                               </div>
                                           </div>
                                            
                                           
                                            
                                            
                                                
                                             <br />
                                            
                                                
                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-primary">Crear</button> 
                                                <button class="btn">Cancelar</button>
                                            </div> <!-- /form-actions -->
                                        </fieldset>
                                    </form>
                                </div>


                                <!---
////////////MODIFICAR...
                            -->
                                <div class="tab-pane" id="modificar">

                                	<form id="crear" method = "post" action = "" class="form-horizontal">
                                        <fieldset>
                                            <!--- 

        $update_version =  $_POST['update_version'];
        $update_apellido1 = $_POST['update_apellido1'];
        $update_apellido2 = $_POST['update_apellido2'];
        $update_display_name = $_POST['update_display_name'];
        $update_email = $_POST['update_email'];
        $update_last_updated = date('Y-m-d H:i:s');
        $update_nombre1 = $_POST['update_nombre1'];
        $update_nombre2 = $_POST['update_nombre2'];
        $update_password = md5($_POST['update_password']);
        $update_id = $_POST['update_id'];
        $update_sitios = $_POST['update_sitios'];
        $update_rol = $_POST['update_rol'];
                                -->
                                    <script>
                                        var updateUsuarios = [];
                                        function updateUsuario(update_version,update_apellido1,update_apellido2,
                                            update_display_name,update_email,update_nombre1,update_nombre2,
                                            update_id,update_sitios,update_rol){
                                            this.update_version = update_version;
                                            this.update_apellido1 = update_apellido1;
                                            this.update_apellido2 = update_apellido2;
                                            this.update_display_name = update_display_name;
                                            this.update_email = update_email;
                                            this.update_nombre1 = update_nombre1;
                                            this.update_nombre2 = update_nombre2;
                                            this.update_id = update_id;
                                            this.update_sitios = update_sitios;
                                            this.update_rol = update_rol;

                                            this.setModificar = function(){

                                                document.getElementsByName("update_version")[0].value = 
                                                    this.update_version;
                                                document.getElementsByName("update_apellido1")[0].value = 
                                                    this.update_apellido1;
                                                document.getElementsByName("update_apellido2")[0].value = 
                                                    this.update_apellido2;
                                                document.getElementsByName("update_display_name")[0].value = 
                                                    this.update_display_name;
                                                document.getElementsByName("update_email")[0].value = 
                                                    this.update_email;
                                                document.getElementsByName("update_nombre1")[0].value = 
                                                    this.update_nombre1;
                                                document.getElementsByName("update_nombre2")[0].value = 
                                                    this.update_nombre2;
                                                document.getElementById("update_id").value = 
                                                    this.update_id;
                                                document.getElementById("update_rol").value = 
                                                    this.update_rol;
                                                var sitiosSelector = document.getElementById("update_sitios");
                                                for (var j = 0; j<sitiosSelector.options.length;j++)
                                                    sitiosSelector.options[j].selected = false;
                                                for (var i= 0; i<this.update_sitios.length;i++){
                                                    for (var j = 0; j<sitiosSelector.options.length;j++){
                                                        if (sitiosSelector.options[j].value == this.update_sitios[i])
                                                            sitiosSelector.options[j].selected = true;
                                                    }
                                                }
                                            };
                                            this.setBorrar = function(){
                                                document.getElementsByName("delete_version")[0].value = 
                                                    this.update_version;
                                                document.getElementsByName("delete_apellido1")[0].value = 
                                                    this.update_apellido1;
                                                document.getElementsByName("delete_apellido2")[0].value = 
                                                    this.update_apellido2;
                                                document.getElementsByName("delete_display_name")[0].value = 
                                                    this.update_display_name;
                                                document.getElementsByName("delete_email")[0].value = 
                                                    this.update_email;
                                                document.getElementsByName("delete_nombre1")[0].value = 
                                                    this.update_nombre1;
                                                document.getElementsByName("delete_nombre2")[0].value = 
                                                    this.update_nombre2;
                                                document.getElementById("delete_id").value = 
                                                    this.update_id;
                                                document.getElementById("delete_rol").value = 
                                                    this.update_rol;
                                                var sitiosSelector = document.getElementById("delete_sitios");
                                                for (var j = 0; j<sitiosSelector.options.length;j++)
                                                    sitiosSelector.options[j].selected = false;
                                                for (var i= 0; i<this.update_sitios.length;i++){
                                                    for (var j = 0; j<sitiosSelector.options.length;j++){
                                                        if (sitiosSelector.options[j].value == this.update_sitios[i])
                                                            sitiosSelector.options[j].selected = true;
                                                    }
                                                }
                                            };
                                        };
                                        function setModificar(sel){
                                            var m = updateUsuarios[sel.value];
                                            m.setModificar();
                                        }
                                        function setBorrar(sel){
                                            var m = updateUsuarios[sel.value];
                                            m.setBorrar();
                                        }
                                    </script>
                                    <?php
                                        $usuarioos = array();
                                        $rs = mysqli_query($link, "select * from usuario where visible = 1;");
                                        $counter = 0;
                                        while($fila = mysqli_fetch_array($rs)){
                                            $update_version =  $fila['version'];
                                            $update_apellido1 = $fila['apellido1'];
                                            $update_apellido2 = $fila['apellido2'];
                                            $update_display_name = $fila['display_name'];
                                            array_push($usuarioos,$update_display_name);
                                            $update_email = $fila['email'];
                                            $update_nombre1 = $fila['nombre1'];
                                            $update_nombre2 = $fila['nombre2'];
                                            $update_id = $fila['id'];

                                            $query = mysqli_query($link, "select rol_id from usuario_rol where usuario_id = ".$update_id." ;");
                                            $rsw = mysqli_fetch_array($query);
                                            if ($rsw)
                                                $update_rol = $rsw['rol_id'];
                                            else
                                                $update_rol = "1";
                                            echo "
                                                <script> var sitios{$counter}= [];</script>\n";
                                            $rsw = mysqli_query($link, "select sitio_id from usuario_sitio where usuario_id = $update_id ;");
                                            while ($filaw = mysqli_fetch_array($rsw)){
                                                $tempId = $filaw['sitio_id'];
                                                echo "
                                                    <script>sitios{$counter}.push( $tempId );</script>\n";
                                            }
                                            echo "
                                                <script>
                                                updateUsuarios.push(new updateUsuario($update_version, '$update_apellido1' ,'$update_apellido2',
                                                '$update_display_name','$update_email','$update_nombre1','$update_nombre2','$update_id',sitios{$counter},
                                                '$update_rol'));
                                                </script>\n" ;
                                            $counter++;
                                        }

                                    ?>
                                            <div class="control-group">											
                                                <label class="control-label" for="username">Usuario</label>
                                                <div class="controls">
                                                   <select class="form-control" onchange = "setModificar(this);">
                                                    <?php
                                                        for ($i= 0; $i<count($usuarioos);$i++)
                                                            echo "<option value = '$i'>{$usuarioos[$i]}</option>\n";
                                                   ?>
                                                    </select>
                                               </div>					
                                            </div> <!-- /control-group -->
                                            
                                            <div class="control-group">                                         
                                                <label class="control-label" for="version">Version</label>
                                                <div class="controls">
                                                    <input type="number" name ="update_version" class="span4" id="codigo">
                                                    
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->

                                            <div class="control-group">                                         
                                                <label class="control-label" for="username">Nombre de usuario</label>
                                                <div class="controls">
                                                    <input type="text" name= "update_display_name" class="span6" id="username">
                                                    
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->
                                            
                                            
                                            <div class="control-group">                                         
                                                <label class="control-label" for="firstname">Nombre</label>
                                                <div class="controls">
                                                    <input type="text" name = "update_nombre1" class="span6" id="firstname">
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->

                                            <div class="control-group">                                         
                                                <label class="control-label" for="firstname">Nombre 2</label>
                                                <div class="controls">
                                                    <input type="text" name = "update_nombre2" class="span6" id="firstname">
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->
                                            
                                            
                                            <div class="control-group">                                         
                                                <label class="control-label" for="lastname">Apellido</label>
                                                <div class="controls">
                                                    <input type="text"  name = "update_apellido1" class="span6" id="lastname">
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->

                                            <div class="control-group">                                         
                                                <label class="control-label" for="lastname">Apellido 2</label>
                                                <div class="controls">
                                                    <input type="text"  name = "update_apellido2" class="span6" id="lastname">
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->
                                            
                                            
                                            <div class="control-group">                                         
                                                <label class="control-label" for="email">Correo electrónico</label>
                                                <div class="controls">
                                                    <input type="text" name = "update_email" class="span4" id="email">
                                                    <input type = "hidden" name = "update_id" id = "update_id" value "0" />
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->
                                            
                                            
                                           
                                            <div class="control-group">                                         
                                                <label class="control-label" for="radiobtns">Tipo de usuario</label>
                                                
                                                  <div class="controls">
                                                   <select name = "update_rol" id = "update_rol" value = "1" class="form-control">
                                                        <option value = "1">Administrador del sistema</option>
                                                        <option value = "2">Mantenimiento</option>
                                                        <option value = "3">Administración</option>
                                                        <option value = "4">Cliente</option>
                                                    </select>
                                               </div>       
                                            </div> <!-- /control-group -->
                                            
                                            
                                            
                                           <div class="control-group">
                                               <label class="control-label" for="sitio">Sitio</label>
                                               <div class="controls">
                                                   <select class="form-control" name = "update_sitios[]" id = "update_sitios" multiple>

                                                    <?php 
                                                     if(!$rs = mysqli_query($link, 'select sitio.display_name, sitio.id from sitio;'))
                                                       {}
                                                      else
                                                        while($fila = mysqli_fetch_array($rs)){
                                                            echo "<option value = '".$fila['id']."'>".$fila['display_name']."</option>\n";
                                                        }
                                                    ?>
                                                    </select>
                                               </div>
                                           </div>
                                            <script>
                                                var m = updateUsuarios[0];
                                                m.setModificar();
                                            </script>
                                           
                                            
                                            
                                                
                                             <br />
                                            
                                    

                                             <br />
                                            
                                                
                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-primary">Modificar</button> 
                                                <button class="btn">Cancelar</button>
                                            </div> <!-- /form-actions -->
                                        </fieldset>
                                    </form>
                                </div>
                                <div class="tab-pane" id="eliminar">
                                <form id="eliminar" class="form-horizontal" method = "POST" action = "">
                                        <fieldset>
                                        <div class="control-group">											
                                                <label class="control-label" for="username">Usuario</label>
                                                <div class="controls">
                                                   <select class="form-control" onchange = "setBorrar(this);">
                                                        <?php
                                                        for ($i= 0; $i<count($usuarioos);$i++)
                                                            echo "<option value = '$i'>{$usuarioos[$i]}</option>\n";
                                                   ?>
                                                    </select>
                                               </div>					
                                            </div> <!-- /control-group -->
                                            
                                            <div class="control-group">                                         
                                                <label class="control-label" for="version">Version</label>
                                                <div class="controls">
                                                    <input type="number" name ="delete_version" class="span4" id="codigo">
                                                    
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->

                                            <div class="control-group">                                         
                                                <label class="control-label" for="username">Nombre de usuario</label>
                                                <div class="controls">
                                                    <input type="text" name= "delete_display_name" class="span6" id="username">
                                                    
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->
                                            
                                            
                                            <div class="control-group">                                         
                                                <label class="control-label" for="firstname">Nombre</label>
                                                <div class="controls">
                                                    <input type="text" name = "delete_nombre1" class="span6" id="firstname">
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->

                                            <div class="control-group">                                         
                                                <label class="control-label" for="firstname">Nombre 2</label>
                                                <div class="controls">
                                                    <input type="text" name = "delete_nombre2" class="span6" id="firstname">
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->
                                            
                                            
                                            <div class="control-group">                                         
                                                <label class="control-label" for="lastname">Apellido</label>
                                                <div class="controls">
                                                    <input type="text"  name = "delete_apellido1" class="span6" id="lastname">
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->

                                            <div class="control-group">                                         
                                                <label class="control-label" for="lastname">Apellido 2</label>
                                                <div class="controls">
                                                    <input type="text"  name = "delete_apellido2" class="span6" id="lastname">
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->
                                            
                                            
                                            <div class="control-group">                                         
                                                <label class="control-label" for="email">Correo electrónico</label>
                                                <div class="controls">
                                                    <input type="text" name = "delete_email" class="span4" id="email">
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->
                                            
                                             
                                           
                                            <div class="control-group">                                         
                                                <label class="control-label" for="radiobtns">Tipo de usuario</label>
                                                
                                                  <div class="controls">
                                                   <select name = "delete_rol" id = "delete_rol" value = "1" class="form-control">
                                                        <option value = "1">Administrador del sistema</option>
                                                        <option value = "2">Mantenimiento</option>
                                                        <option value = "3">Administración</option>
                                                        <option value = "4">Cliente</option>
                                                    </select>
                                               </div>       
                                            </div> <!-- /control-group -->
                                            
                                            
                                            
                                           <div class="control-group">
                                               <label class="control-label" for="sitio">Sitio</label>
                                               <div class="controls">
                                                   <select class="form-control" id = "delete_sitios"  multiple>

                                                    <?php 
                                                     if(!$rs = mysqli_query($link, 'select sitio.display_name, sitio.id from sitio;'))
                                                       {}
                                                      else
                                                        while($fila = mysqli_fetch_array($rs)){
                                                            echo "<option value = '".$fila['id']."'>".$fila['display_name']."</option>\n";
                                                        }
                                                    ?>
                                                    </select>
                                               </div>
                                           </div>
                                            
                                           
                                            
                                            
                                                
                                             <br />
                                           
                                                
                                            <div class="form-actions">

                                                <input type = "hidden" name = "delete_id" id = "delete_id" value = "0" />
                                                <button onclick="preguntar();" type="submit" class="btn btn-primary">Eliminar</button> 
                                                <button class="btn">Cancelar</button>
                                            </div> <!-- /form-actions -->
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                        </div><!--tabbable-->
                    </div><!--widtget-content-->
                </div><!--widget-->
            </div><!--span-->
        <!--row-->
        
                                            
                                            <script>
                                                var m = updateUsuarios[0];
                                                m.setBorrar();
                                            </script>
    </div> <!-- /container --> 
  </div> <!-- /main-inner --> 
</div> <!-- /main -->


<?php include 'foot.php'; ?>

<script type = 'text/javascript'>           
    function preguntar()  
    {               
        var eliminar = confirm('Seguro que desea eliminar esto?');                 
        if(eliminar) 
        {
            location.href='clientes.php';
        }          
    }
</script>

</body>
</html>

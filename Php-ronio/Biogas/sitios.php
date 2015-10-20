<!DOCTYPE html>
<html lang="en">
<head>
<?php
    include 'connection.php';
    $link = mysqli_connect($db_url, $db_user,$db_pass, $db_name, $db_port); 
    
    
    $link = mysqli_connect($db_url, $db_user,$db_pass, $db_name, $db_port); 
    if (isset($_POST['delete_id'])){
        $delete_id = $_POST['delete_id'];
        $link->query("delete from modulo where sitio_id = $delete_id;");
        $link->query("delete from usuario_sitio where sitio_id = $delete_id;");
        $link->query("delete from sitio where id = $delete_id;");
    }
    if (isset($_POST['update_id'])){
        $update_id = $_POST['update_id'];
        $update_version =  $_POST['update_version'];
        $update_calle = $_POST['update_calle'];
        $update_ciudad = $_POST['update_ciudad'];
        $update_codigo_postal = $_POST['update_codigo_postal'];
        $update_colonia = $_POST['update_colonia'];
        $update_display_name = $_POST['update_display_name'];
        $update_estado = $_POST['update_estado'];
        $update_nombre_sitio = $_POST['update_nombre_sitio'];
        $update_numero_ext = $_POST['update_numero_ext'];
        $update_numero_int = $_POST['update_numero_int'];
        $update_pais = $_POST['update_pais'];
        $update_tipo = $_POST['update_tipo'];

        $queryString = "update sitio  set version = $update_version, calle = '$update_calle',".
        "ciudad = '$update_ciudad', codigo_postal = $update_codigo_postal, colonia = '$update_colonia',".
        "display_name = '$update_display_name', estado = '$update_estado',".
        " nombre_sitio = '$update_nombre_sitio', numero_ext = $update_numero_ext, numero_int = ".
        "$update_numero_int, pais = '$update_pais', tipo = '$update_tipo' where id = $update_id";

        echo "<!--- $update_id:\n  $queryString -->";
        
        $link->query($queryString);

        
    }
    if (isset($_POST['insert_version'])){
        
        $insert_version =  $_POST['insert_version'];
        $insert_calle = $_POST['insert_calle'];
        $insert_ciudad = $_POST['insert_ciudad'];
        $insert_codigo_postal = $_POST['insert_codigo_postal'];
        $insert_colonia = $_POST['insert_colonia'];
        $insert_display_name = $_POST['insert_display_name'];
        $insert_estado = $_POST['insert_estado'];
        $insert_nombre_sitio = $_POST['insert_nombre_sitio'];
        $insert_numero_ext = $_POST['insert_numero_ext'];
        $insert_numero_int = $_POST['insert_numero_int'];
        $insert_pais = $_POST['insert_pais'];
        $insert_tipo = $_POST['insert_tipo'];

        $queryString = "insert into sitio(version,calle,ciudad,codigo_postal,colonia,display_name,estado,nombre,".
            "numero_ext,numero_int,pais,tipo) values($insert_version,'$insert_calle','$insert_ciudad','$insert_codigo_postal',".
            "'$insert_colonia','$insert_display_name','$insert_estado','$insert_nombre_sitio',$insert_numero_ext,".
            "$insert_numero_int,'$insert_pais','$insert_tipo');";
        echo "<!--- $queryString --->";
        
         
        if (!mysqli_query($link, $queryString)){
            echo '<script>alert("'.$mysqli_error($link).'");</script>';
        }
        
    }
?>
<meta charset="utf-8">
<title>Módulo de energía solar híbrido</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600"
        rel="stylesheet">7
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
	      				<h3>Configuración de sitios</h3>
	  				</div> <!-- /widget-header -->
                    <div class="widget-content">
                    	<div class="tabbable">
                        	<ul class="nav nav-tabs">
                            	<li class="active"><a href="#crear" data-toggle="tab">Crear sitio</a></li>
						  		<li><a href="#modificar" data-toggle="tab">Modificar sitio</a></li>
                                <li><a href="#eliminar" data-toggle="tab">Eliminar sitio</a></li>
							</ul>
                            <br>
                            <div class="tab-content">
								<div class="tab-pane active" id="crear">
                                    <form id="crear" method = "post" action = "" class="form-horizontal">
                                        <fieldset>
                                            
                                            <div class="control-group">											
                                                <label class="control-label" for="site">Nombre del sitio</label>
                                                <div class="controls">
                                                    <input type="text" name = "insert_nombre_sitio" class="span6" id="site">
                                                    
                                                </div> <!-- /controls -->				
                                            </div> <!-- /control-group -->
                                            <div class="control-group">                                         
                                                <label class="control-label" for="site">Nombre oficial del sitio</label>
                                                <div class="controls">
                                                    <input type="text" name = "insert_display_name" class="span6" id="site">
                                                    
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->
                                            <div class="control-group">                                         
                                                <label class="control-label" for="site">Versión</label>
                                                <div class="controls">
                                                    <input type="number" name = "insert_version" class="span6" id="site">
                                                    
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->
                                            <div class="control-group">                                         
                                                <label class="control-label" for="site">Tipo</label>
                                                <div class="controls">
                                                    <input type="text" name = "insert_tipo" class="span6" id="site">
                                                    
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->
                                            
                                            
                                            <div class="control-group">											
                                                <label class="control-label" for="address">Dirección</label>
                                               <br>&nbsp;<br>
                                               <div class="controls">
                                                	<div class="control-group">											
                                                        <label class="control-label" for="lastname">Calle</label>
                                                        <div class="controls">
                                                            <input type="text" name = "insert_calle" class="span6" id="calle">
                                                        </div> <!-- /controls -->				
                                                    </div> <!-- /control-group -->
                                                    <div class="control-group">											
                                                        <label class="control-label" for="lastname">Número interno</label>
                                                        <div class="controls">
                                                            <input type="text" name = "insert_numero_int"  class="span6" id="numint">
                                                        </div> <!-- /controls -->				
                                                    </div> <!-- /control-group -->
                                                    <div class="control-group">											
                                                        <label class="control-label" for="lastname">Número externo</label>
                                                        <div class="controls">
                                                            <input type="text" name = "insert_numero_ext" class="span6" id="numext">
                                                        </div> <!-- /controls -->				
                                                    </div> <!-- /control-group -->
                                                    <div class="control-group" >											
                                                        <label class="control-label" for="lastname">Colonia</label>
                                                        <div class="controls">
                                                            <input type="text" name = "insert_colonia"  class="span6" id="col">
                                                        </div> <!-- /controls -->				
                                                    </div> <!-- /control-group -->
                                                    <div class="control-group" >											
                                                        <label class="control-label" for="lastname">Municipio o Delegación</label>
                                                        <div class="controls">
                                                            <input type="text" name = "insert_ciudad"  class="span6" id="ciudad">
                                                        </div> <!-- /controls -->				
                                                    </div> <!-- /control-group -->
                                                    <div class="control-group" >											
                                                        <label class="control-label" for="lastname">Estado</label>
                                                        <div class="controls">
                                                            <input type="text" name = "insert_estado" class="span6" id="edo">
                                                        </div> <!-- /controls -->				
                                                    </div> <!-- /control-group -->
                                                    <div class="control-group">											
                                                        <label class="control-label" for="lastname">País</label>
                                                        <div class="controls">
                                                            <input type="text" name = "insert_pais" class="span6" id="pais">
                                                        </div> <!-- /controls -->				
                                                    </div> <!-- /control-group --><div class="control-group">											
                                                        <label class="control-label" for="lastname">CP</label>
                                                        <div class="controls">
                                                            <input type="text" name = "insert_codigo_postal" class="span6" id="cp">
                                                        </div> <!-- /controls -->				
                                                    </div> <!-- /control-group -->
                                                    </div>
                                            </div> <!-- /control-group -->
                                            
                                            
                                            
                                            
                                             <br />
                                            
                                                
                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-primary">Crear</button> 
                                                <button class="btn">Cancelar</button>
                                            </div> <!-- /form-actions -->
                                        </fieldset>
                                    </form>
                                </div>
                                <!--- 
                                        Aqui es donde se editan y borran...
                                        $update_id = $_POST['update_id'];
                                        $update_version =  $_POST['update_version'];
                                        $update_calle = $_POST['update_calle'];
                                        $update_ciudad = $_POST['update_ciudad'];
                                        $update_codigo_postal = $_POST['update_codigo_postal'];
                                        $update_colonia = $_POST['update_colonia'];
                                        $update_display_name = $_POST['update_display_name'];
                                        $update_estado = $_POST['update_estado'];
                                        $update_nombre_sitio = $_POST['update_nombre_sitio'];
                                        $update_numero_ext = $_POST['update_numero_ext'];
                                        $update_numero_int = $_POST['update_numero_int'];
                                        $update_pais = $_POST['update_pais'];
                                        $update_tipo = $_POST['update_tipo'];
                                -->
                                <script>

                                    var updateSitios = [];
                                    function updateSitio(update_id,update_version,update_calle,update_ciudad,
                                        update_codigo_postal,update_colonia,update_display_name,update_estado,
                                        update_nombre_sitio,update_numero_ext,update_numero_int,
                                        update_pais,update_tipo){
                                            this.update_id = update_id;
                                            this.update_version = update_version;
                                            this.update_calle = update_calle;
                                            this.update_ciudad = update_ciudad;
                                            this.update_codigo_postal = update_codigo_postal;
                                            this.update_colonia = update_colonia;
                                            this.update_display_name = update_display_name;
                                            this.update_estado = update_estado;
                                            this.update_nombre_sitio = update_nombre_sitio;
                                            this.update_numero_ext = update_numero_ext;
                                            this.update_numero_int = update_numero_int;
                                            this.update_pais = update_pais;
                                            this.update_tipo = update_tipo;

                                        this.setModificar = function(){
                                            document.getElementsByName("update_id")[0].value =
                                                this.update_id;
                                            document.getElementsByName("update_version")[0].value =
                                                this.update_version;
                                            document.getElementsByName("update_calle")[0].value =
                                                this.update_calle;
                                            document.getElementsByName("update_ciudad")[0].value =
                                                this.update_ciudad;
                                            document.getElementsByName("update_codigo_postal")[0].value =
                                                this.update_codigo_postal;
                                            document.getElementsByName("update_colonia")[0].value =
                                                this.update_colonia;
                                            document.getElementsByName("update_display_name")[0].value =
                                                this.update_display_name;
                                            document.getElementsByName("update_estado")[0].value =
                                                this.update_estado;
                                            document.getElementsByName("update_nombre_sitio")[0].value =
                                                this.update_nombre_sitio;
                                            document.getElementsByName("update_numero_ext")[0].value =
                                                this.update_numero_ext;
                                            document.getElementsByName("update_numero_int")[0].value =
                                                this.update_numero_int;
                                            document.getElementsByName("update_pais")[0].value =
                                                this.update_pais;
                                            document.getElementsByName("update_tipo")[0].value =
                                                this.update_tipo;

                                        };
                                        this.setBorrar = function(){
                                            document.getElementsByName("delete_id")[0].value =
                                                this.update_id;
                                            document.getElementsByName("delete_version")[0].value =
                                                this.update_version;
                                            document.getElementsByName("delete_calle")[0].value =
                                                this.update_calle;
                                            document.getElementsByName("delete_ciudad")[0].value =
                                                this.update_ciudad;
                                            document.getElementsByName("delete_codigo_postal")[0].value =
                                                this.update_codigo_postal;
                                            document.getElementsByName("delete_colonia")[0].value =
                                                this.update_colonia;
                                            document.getElementsByName("delete_display_name")[0].value =
                                                this.update_display_name;
                                            document.getElementsByName("delete_estado")[0].value =
                                                this.update_estado;
                                            document.getElementsByName("delete_nombre_sitio")[0].value =
                                                this.update_nombre_sitio;
                                            document.getElementsByName("delete_numero_ext")[0].value =
                                                this.update_numero_ext;
                                            document.getElementsByName("delete_numero_int")[0].value =
                                                this.update_numero_int;
                                            document.getElementsByName("delete_pais")[0].value =
                                                this.update_pais;
                                            document.getElementsByName("delete_tipo")[0].value =
                                                this.update_tipo;
                                        };

                                    };
                                    function setModificar(sel){
                                        var m = updateSitios[sel.value];
                                        m.setModificar();
                                    }
                                    function setBorrar(sel){
                                        var m = updateSitios[sel.value];
                                        m.setBorrar();
                                    }
                                </script>
                                <?php
                                    $sitioos = array();
                                    $rs = mysqli_query($link, "select * from sitio;");
                                    while($fila = mysqli_fetch_array($rs)){
                                        array_push($sitioos,$fila['display_name']);
                                        /*
                    updateSitio(update_id,update_version,update_calle,update_ciudad,
                                        update_codigo_postal,update_colonia,update_display_name,update_estado,
                                        update_nombre_sitio,update_numero_ext,update_numero_int,
                                        update_pais,update_tipo)

                                        */
                                        $update_id = $fila['id'];
                                        $update_version =  ($fila['version'] ? $fila['version'] : 0 );
                                        $update_calle = $fila['calle'];
                                        $update_ciudad = $fila['ciudad'];
                                        $update_codigo_postal = $fila['codigo_postal'];
                                        $update_colonia = $fila['colonia'];
                                        $update_display_name = $fila['display_name'];
                                        $update_estado = $fila['estado'];
                                        $update_nombre_sitio = $fila['nombre_sitio'];
                                        $update_numero_ext = ($fila['numero_ext'] ? $fila['numero_ext']: 0);
                                        $update_numero_int = ($fila['numero_int'] ? $fila['numero_int'] : 0);
                                        $update_pais = $fila['pais'];
                                        $update_tipo = ($fila['tipo'] ? $fila['tipo'] : 0 );
                                        echo "
                                        <script>updateSitios.push(new updateSitio($update_id,$update_version,
                                            '$update_calle','$update_ciudad',$update_codigo_postal,'$update_colonia',
                                            '$update_display_name','$update_estado','$update_nombre_sitio',
                                            $update_numero_ext,$update_numero_int,'$update_pais',
                                            '$update_tipo'));</script>";
                                    }

                                ?>
                                <div class="tab-pane" id="modificar">
                                	<form id="modificar" method = "post" action = "" class="form-horizontal">
                                        <fieldset>
                                            <div class="control-group">                                         
                                                <label class="control-label" for="username">Nombre del sitio</label>
                                                <div class="controls">
                                                   <select class="form-control" onchange = "setModificar(this);">
                                                    <?php

                                                        for ($i= 0; $i<count($sitioos);$i++)
                                                            echo "<option value = '$i'>{$sitioos[$i]}</option>\n";
                                                   ?>
                                                    </select>
                                                    <input type = "hidden" name = "update_id" value = "0" />
                                               </div>                   
                                            </div>
                                            <div class="control-group">                                         
                                                <label class="control-label" for="site">Nombre del sitio</label>
                                                <div class="controls">
                                                    <input type="text" name = "update_nombre_sitio" class="span6" id="site">
                                                    
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->
                                            <div class="control-group">                                         
                                                <label class="control-label" for="site">Nombre oficial del sitio</label>
                                                <div class="controls">
                                                    <input type="text" name = "update_display_name" class="span6" id="site">
                                                    
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->
                                            <div class="control-group">                                         
                                                <label class="control-label" for="site">Versión</label>
                                                <div class="controls">
                                                    <input type="number" name = "update_version" class="span6" id="site">
                                                    
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->
                                            <div class="control-group">                                         
                                                <label class="control-label" for="site">Tipo</label>
                                                <div class="controls">
                                                    <input type="text" name = "update_tipo" class="span6" id="site">
                                                    
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->
                                            
                                            
                                            <div class="control-group">                                         
                                                <label class="control-label" for="address">Dirección</label>
                                               <br>&nbsp;<br>
                                               <div class="controls">
                                                    <div class="control-group">                                         
                                                        <label class="control-label" for="lastname">Calle</label>
                                                        <div class="controls">
                                                            <input type="text" name = "update_calle" class="span6" id="calle">
                                                        </div> <!-- /controls -->               
                                                    </div> <!-- /control-group -->
                                                    <div class="control-group">                                         
                                                        <label class="control-label" for="lastname">Número interno</label>
                                                        <div class="controls">
                                                            <input type="text" name = "update_numero_int"  class="span6" id="numint">
                                                        </div> <!-- /controls -->               
                                                    </div> <!-- /control-group -->
                                                    <div class="control-group">                                         
                                                        <label class="control-label" for="lastname">Número externo</label>
                                                        <div class="controls">
                                                            <input type="text" name = "update_numero_ext" class="span6" id="numext">
                                                        </div> <!-- /controls -->               
                                                    </div> <!-- /control-group -->
                                                    <div class="control-group" >                                            
                                                        <label class="control-label" for="lastname">Colonia</label>
                                                        <div class="controls">
                                                            <input type="text" name = "update_colonia"  class="span6" id="col">
                                                        </div> <!-- /controls -->               
                                                    </div> <!-- /control-group -->
                                                    <div class="control-group" >                                            
                                                        <label class="control-label" for="lastname">Municipio o Delegación</label>
                                                        <div class="controls">
                                                            <input type="text" name = "update_ciudad"  class="span6" id="ciudad">
                                                        </div> <!-- /controls -->               
                                                    </div> <!-- /control-group -->
                                                    <div class="control-group" >                                            
                                                        <label class="control-label" for="lastname">Estado</label>
                                                        <div class="controls">
                                                            <input type="text" name = "update_estado" class="span6" id="edo">
                                                        </div> <!-- /controls -->               
                                                    </div> <!-- /control-group -->
                                                    <div class="control-group">                                         
                                                        <label class="control-label" for="lastname">País</label>
                                                        <div class="controls">
                                                            <input type="text" name = "update_pais" class="span6" id="pais">
                                                        </div> <!-- /controls -->               
                                                    </div> <!-- /control-group --><div class="control-group">                                           
                                                        <label class="control-label" for="lastname">CP</label>
                                                        <div class="controls">
                                                            <input type="text" name = "update_codigo_postal" class="span6" id="cp">
                                                        </div> <!-- /controls -->               
                                                    </div> <!-- /control-group -->
                                                    </div>
                                            </div> <!-- /control-group -->
                                            
                                            
                                            
                                            
                                             <br />
                                            
                                                
                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-primary">Modificar</button> 
                                                <button class="btn">Cancelar</button>
                                            </div> <!-- /form-actions -->
                                        </fieldset>
                                    </form>
                                </div>
                                <script>
                                    var m = updateSitios[0];
                                    m.setModificar();
                                </script>
                                <div class="tab-pane" id="eliminar">
                               <form id="modificar" class="form-horizontal" method = "post" action = "">
                                        <fieldset>
                                        <fieldset>
                                            <div class="control-group">                                         
                                                <label class="control-label" for="username">Nombre del sitio</label>
                                                <div class="controls">
                                                   <select class="form-control" onchange = "setBorrar(this);">
                                                    <?php

                                                        for ($i= 0; $i<count($sitioos);$i++)
                                                            echo "<option value = '$i'>{$sitioos[$i]}</option>\n";
                                                   ?>
                                                    </select>
                                               </div>                   
                                            </div>
                                            <div class="control-group">                                         
                                                <label class="control-label" for="site">Nombre del sitio</label>
                                                <div class="controls">
                                                    <input type="text" name = "delete_nombre_sitio" class="span6" id="site">

                                                    <input type = "hidden" name = "delete_id" value = "0" />
                                                    
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->
                                            <div class="control-group">                                         
                                                <label class="control-label" for="site">Nombre oficial del sitio</label>
                                                <div class="controls">
                                                    <input type="text" name = "delete_display_name" class="span6" id="site">
                                                    
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->
                                            <div class="control-group">                                         
                                                <label class="control-label" for="site">Versión</label>
                                                <div class="controls">
                                                    <input type="number" name = "delete_version" class="span6" id="site">
                                                    
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->
                                            <div class="control-group">                                         
                                                <label class="control-label" for="site">Tipo</label>
                                                <div class="controls">
                                                    <input type="text" name = "delete_tipo" class="span6" id="site">
                                                    
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->
                                            
                                            
                                            <div class="control-group">                                         
                                                <label class="control-label" for="address">Dirección</label>
                                               <br>&nbsp;<br>
                                               <div class="controls">
                                                    <div class="control-group">                                         
                                                        <label class="control-label" for="lastname">Calle</label>
                                                        <div class="controls">
                                                            <input type="text" name = "delete_calle" class="span6" id="calle">
                                                        </div> <!-- /controls -->               
                                                    </div> <!-- /control-group -->
                                                    <div class="control-group">                                         
                                                        <label class="control-label" for="lastname">Número interno</label>
                                                        <div class="controls">
                                                            <input type="text" name = "delete_numero_int"  class="span6" id="numint">
                                                        </div> <!-- /controls -->               
                                                    </div> <!-- /control-group -->
                                                    <div class="control-group">                                         
                                                        <label class="control-label" for="lastname">Número externo</label>
                                                        <div class="controls">
                                                            <input type="text" name = "delete_numero_ext" class="span6" id="numext">
                                                        </div> <!-- /controls -->               
                                                    </div> <!-- /control-group -->
                                                    <div class="control-group" >                                            
                                                        <label class="control-label" for="lastname">Colonia</label>
                                                        <div class="controls">
                                                            <input type="text" name = "delete_colonia"  class="span6" id="col">
                                                        </div> <!-- /controls -->               
                                                    </div> <!-- /control-group -->
                                                    <div class="control-group" >                                            
                                                        <label class="control-label" for="lastname">Municipio o Delegación</label>
                                                        <div class="controls">
                                                            <input type="text" name = "delete_ciudad"  class="span6" id="ciudad">
                                                        </div> <!-- /controls -->               
                                                    </div> <!-- /control-group -->
                                                    <div class="control-group" >                                            
                                                        <label class="control-label" for="lastname">Estado</label>
                                                        <div class="controls">
                                                            <input type="text" name = "delete_estado" class="span6" id="edo">
                                                        </div> <!-- /controls -->               
                                                    </div> <!-- /control-group -->
                                                    <div class="control-group">                                         
                                                        <label class="control-label" for="lastname">País</label>
                                                        <div class="controls">
                                                            <input type="text" name = "delete_pais" class="span6" id="pais">
                                                        </div> <!-- /controls -->               
                                                    </div> <!-- /control-group --><div class="control-group">                                           
                                                        <label class="control-label" for="lastname">CP</label>
                                                        <div class="controls">
                                                            <input type="text" name = "delete_codigo_postal" class="span6" id="cp">
                                                        </div> <!-- /controls -->               
                                                    </div> <!-- /control-group -->
                                                    </div>
                                            </div> <!-- /control-group -->
                                            
                                            
                                            
                                            
                                                
                                            <div class="form-actions">
                                                <button  onclick="preguntar();" type="submit" class="btn btn-primary">Eliminar</button> 
                                                <button  class="btn">Cancelar</button>
                                            </div> <!-- /form-actions -->
                                        </fieldset>
                                    </form>
                                </div>
                            </div>

                                <script>
                                    var m = updateSitios[0];
                                    m.setBorrar();
                                </script>
                        </div><!--tabbable-->
                    </div><!--widtget-content-->
                </div><!--widget-->
            </div><!--span-->
        <!--row-->
    
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

<!DOCTYPE html>
<html lang="en">
<head>
<?php

    include 'connection.php';

    $link = mysqli_connect($db_url, $db_user,$db_pass, $db_name); 
    if (isset($_POST['delete_id'])){
        $delete_id = $_POST['delete_id'];
        echo "<!--- $queryString --->";
        $link->query("delete modulo where id = $delete_id;");
    }
    if (isset($_POST['update_id'])){
        $queryString = "insert into modulo set display_name = '".$_POST['update_display_name']."', ".
        " nombre = '".$_POST['insert_nombre'].", sitio_id = ".$_POST['update_sitio_id']. 
        " , ip = '".$_POST['update_ip']."' where id = ".$_POST['update_id'].";";
        echo "<!--- $queryString --->";
        $link->query($queryString);
    }
    if (isset($_POST['insert_display_name'])){
        //Se inserta el nuevo modulo a la base de datos...
        $queryString = "insert into modulo(display_name,nombre,sitio_id,ip)".
        " values('".$_POST['insert_display_name']."','".$_POST['insert_nombre'].
                "',".$_POST['insert_sitio_id'].",'".$_POST['insert_ip']."');";
        echo "<!--- $queryString --->";
        $link->query($queryString);
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
                        <h3>Configuración de módulos</h3>
                    </div> <!-- /widget-header -->
                    <div class="widget-content">
                        <div class="tabbable">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#crear" data-toggle="tab">Crear módulo</a></li>
                                <li><a href="#modificar" data-toggle="tab">Modificar módulo</a></li>
                                <li><a href="#eliminar" data-toggle="tab">Eliminar módulo</a></li>
                            </ul>
                            <br>
                            <div class="tab-content">
                                <div class="tab-pane active" id="crear">
                                    <form id="crear" class="form-horizontal" action = "" method = "POST">
                                        
                                        <fieldset>
                                            <div class="control-group">                                         
                                                <label class="control-label" for="sitio">Nombre oficial</label>
                                                <div class="controls">
                                                    <input type="text" name ="insert_display_name" class="span4" id="codigo">
                                                    
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->

                                            <div class="control-group">                                         
                                                <label class="control-label" for="sitio">Nombre del módulo</label>
                                                <div class="controls">
                                                    <input type="text" name ="insert_nombre" class="span4" id="codigo">
                                                    
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->

                                            <div class="control-group">                                         
                                                <label class="control-label" for="radiobtns">Sitio</label>
                                                
                                                  <div class="controls">
                                                   <select name = "insert_sitio_id" value = "1" class="form-control">
                                                        <?php 
                                                            $rs = mysqli_query($link, 'select id, nombre from sitio order by id;');
                                                            while($row = mysqli_fetch_array($rs)){
                                                                echo "<option value = '".$row['id']."'>".
                                                                    $row['nombre']."</option>";
                                                            }
                                                        ?>
                                                    </select>
                                               </div>       
                                            </div> <!-- /control-group -->
                                            
                                            
                                            
                                           <div class="control-group">
                                               <label class="control-label" for="sitio">IP</label>
                                               <div class="controls">
                                                   <input type="text" name ="insert_ip" class="span4" id="codigo">

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
                                    <script>
                                        var updateModulos = [];
                                        function updateModulo(update_id, update_display_name,update_nombre,
                                            update_sitio_id,update_ip){
                                            this.update_id = update_id;
                                            this.update_display_name = update_display_name;
                                            this.update_nombre = update_nombre;
                                            this.update_sitio_id = update_sitio_id;
                                            this.update_ip = update_ip;

                                            this.setModificar = function(){

                                                document.getElementsByName("update_id")[0].value = 
                                                    this.update_id;
                                                document.getElementsByName("update_display_name")[0].value = 
                                                    this.update_display_name;
                                                document.getElementsByName("update_nombre")[0].value = 
                                                    this.update_nombre;
                                                document.getElementsByName("update_sitio_id")[0].value = 
                                                    this.update_sitio_id;
                                                document.getElementsByName("update_ip")[0].value = 
                                                    this.update_ip;
                                                
                                            };
                                            this.setBorrar = function(){
                                                document.getElementsByName("delete_id")[0].value = 
                                                    this.update_id;
                                                document.getElementsByName("delete_display_name")[0].value = 
                                                    this.update_display_name;
                                                document.getElementsByName("delete_nombre")[0].value = 
                                                    this.update_nombre;
                                                document.getElementsByName("delete_sitio_id")[0].value = 
                                                    this.update_sitio_id;
                                                document.getElementsByName("delete_ip")[0].value = 
                                                    this.update_ip;
                                            };
                                        };
                                        function setModificar(sel){
                                            var m = updateModulos[sel.value];
                                            m.setModificar();
                                        }
                                        function setBorrar(sel){
                                            var m = updateModulos[sel.value];
                                            m.setBorrar();
                                        }
                                    </script>
                                    <?php
                                        $moduloos = array();
                                        $rs = mysqli_query($link, "select * from modulo order by id;");

                                        while($row = mysqli_fetch_array($rs)){

                                            $update_id = $row['id'];
                                            $update_display_name = $row['display_name'];
                                            $update_nombre = $row['nombre'];
                                            $update_sitio_id = $row['sitio_id'];
                                            $update_ip = $row['ip'];

                                            array_push($moduloos, $update_display_name);

                                            echo "
                                                <script>
                                                    updateModulos.push(new updateModulo($update_id, '$update_display_name' ,'$update_nombre',
                                                        $update_sitio_id,'$update_ip'));
                                                </script>\n" ;
                                        }

                                    ?>
                                            <div class="control-group">                                         
                                                <label class="control-label" for="username">Modulo</label>
                                                <div class="controls">
                                                   <select class="form-control" onchange = "setModificar(this);">
                                                    <?php
                                                        for ($i= 0; $i<count($moduloos);$i++)
                                                            echo "<option value = '$i'>{$moduloos[$i]}</option>\n";
                                                   ?>
                                                    </select>
                                               </div>                   
                                            </div> <!-- /control-group -->
                                            
                                            <div class="control-group">                                         
                                                <label class="control-label" for="sitio">Nombre oficial</label>
                                                <div class="controls">
                                                    <input type="text" name ="update_display_name" class="span4" id="codigo">
                                                    <input type = "hidden" name = "update_id" id = "update_id" value "0" />
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->

                                            <div class="control-group">                                         
                                                <label class="control-label" for="sitio">Nombre del módulo</label>
                                                <div class="controls">
                                                    <input type="text" name ="update_nombre" class="span4" id="codigo">
                                                    
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->

                                            <div class="control-group">                                         
                                                <label class="control-label" for="radiobtns">Sitio</label>
                                                
                                                  <div class="controls">
                                                   <select name = "update_sitio_id" value = "1" class="form-control">
                                                        <?php 
                                                            $rs = mysqli_query($link, 'select id, nombre from sitio order by id;');
                                                            while($row = mysqli_fetch_array($rs)){
                                                                echo "<option value = '".$row['id']."'>".
                                                                    $row['nombre']."</option>";
                                                            }
                                                        ?>
                                                    </select>
                                               </div>       
                                            </div> <!-- /control-group -->
                                            
                                            
                                            
                                           <div class="control-group">
                                               <label class="control-label" for="sitio">IP</label>
                                               <div class="controls">
                                                   <input type="text" name ="update_ip" class="span4" id="codigo">

                                               </div>
                                           </div>
                                            
                                            <script>
                                                var m = updateModulos[0];
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
                                                <label class="control-label" for="username">Modulo</label>
                                                <div class="controls">
                                                   <select class="form-control" onchange = "setModificar(this);">
                                                    <?php
                                                        for ($i= 0; $i<count($moduloos);$i++)
                                                            echo "<option value = '$i'>{$moduloos[$i]}</option>\n";
                                                   ?>
                                                    </select>
                                               </div>                   
                                            </div> <!-- /control-group -->
                                            
                                            <div class="control-group">                                         
                                                <label class="control-label" for="sitio">Nombre oficial</label>
                                                <div class="controls">
                                                    <input type="text" name ="delete_display_name" class="span4" id="codigo">
                                                    <input type = "hidden" name = "delete_id" id = "update_id" value "0" />
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->

                                            <div class="control-group">                                         
                                                <label class="control-label" for="sitio">Nombre del módulo</label>
                                                <div class="controls">
                                                    <input type="text" name ="delete_nombre" class="span4" id="codigo">
                                                    
                                                </div> <!-- /controls -->               
                                            </div> <!-- /control-group -->

                                            <div class="control-group">                                         
                                                <label class="control-label" for="radiobtns">Sitio</label>
                                                
                                                  <div class="controls">
                                                   <select name = "delete_sitio_id" value = "1" class="form-control">
                                                        <?php 
                                                            $rs = mysqli_query($link, 'select id, nombre from sitio order by id;');
                                                            while($row = mysqli_fetch_array($rs)){
                                                                echo "<option value = '".$row['id']."'>".
                                                                    $row['nombre']."</option>";
                                                            }
                                                        ?>
                                                    </select>
                                               </div>       
                                            </div> <!-- /control-group -->
                                            
                                            
                                            
                                           <div class="control-group">
                                               <label class="control-label" for="sitio">IP</label>
                                               <div class="controls">
                                                   <input type="text" name ="delete_ip" class="span4" id="codigo">

                                               </div>
                                           </div>
                                            
                                            <script>
                                                var n = updateModulos[0];
                                                n.setBorrar();
                                            </script>
                                           
                                             <br />
                                            
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

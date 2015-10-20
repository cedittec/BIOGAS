<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Módulo de energía solar híbrido</title>
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
    include 'head.php';
    include 'connectionTools';
?>



<div class="main">
  <div class="main-inner">
    <div class="container">
    	<div class="row">
        	<div class="span12">   
            	<div class="widget">
                	<div class="widget-header">
                    	<i class="icon-user"></i>
	      				<h3>Configuración de clientes</h3>
	  				</div> <!-- /widget-header -->
                    <div class="widget-content">
                    	<div class="tabbable">
                        	<ul class="nav nav-tabs">

                                <?php 
                                    if(isset($_POST['cargar']) || isset($_POST['modificar']))
                                    {
                                        ?>                                        
                                <li><a href="#crear" data-toggle="tab">Crear cliente</a></li>
                                <li class="active"><a href="#modificar" data-toggle="tab">Modificar cliente</a></li>
                                <li><a href="#eliminar" data-toggle="tab">Eliminar cliente</a></li>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                <li class="active"><a href="#crear" data-toggle="tab">Crear cliente</a></li>
                                <li><a href="#modificar" data-toggle="tab">Modificar cliente</a></li>
                                <li><a href="#eliminar" data-toggle="tab">Eliminar cliente</a></li>
                                        <?php
                                    }
                                ?>
							</ul>
                            <br>













                            <div class="tab-content">
        <?php
            if(!isset($_POST['cargar']) && !isset($_POST['modificar']) && !isset($_POST['eliminar']))
                { ?> <div class="tab-pane active" id="crear"> <?php }
            else
                { ?> <div class="tab-pane" id="crear"> <?php }

            if(isset($_POST['crear']) )
            {
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
                    
                include 'mailer/mailSender.php';
                sendMail(   
                    "energia@emsmx.com", "energiaems",
                    $_POST['email'],
                    "Hemos creado tu cuenta en Híbrido",
                    "Contraseña: ".$passCreada
                ); // user, pass, to, subject, msg.

                $query = "insert into cliente ".
                "(visible, responsable_id, razon_social, rfc, representante_legal, nombre_representante, apellido_representante, email, password, display_name) values ".
                "( 1, ".
                    $_SESSION['id'].", '".
                    $_POST['razonSocial']."', '".
                    $_POST['rfc']."', '".
                    $_POST['representanteLegal']."', '".
                    $_POST['nombreRepresentante']."', '".
                    $_POST['apellidoRepresentante']."', '".
                    $_POST['email']."', '".
                    md5($passCreada)."', '".
                    $_POST['display'].
                    "');";
            
                if(!$rs = mysqli_query($link, $query))
                    echo "<script>alert('Error al insertar cliente, ya existía en la BD.');</script>";
                else
                {
                            echo "<script>alert('Cliente agregado exitosamente');</script>";
                            $query ="select id from cliente where email = '".$_POST['email']."'";

                            mysqli_query($link, $query);
                            if($fila = mysqli_fetch_array($rs))
                                $idNuevo = $fila['id'];

                            $query = "insert into direccion_cliente ".
                            "(calle, numero_int, numero_ext, colonia, codigo_postal, ciudad, estado, pais, id_cliente_id) values ".
                            "('".$_POST['calle']."', ".
                                $_POST['numeroInt'].", ".
                                $_POST['numeroExt'].", '".
                                $_POST['colonia']."', ".
                                $_POST['codigoPostal'].", '".
                                $_POST['ciudad']."', '".
                                $_POST['estado']."', '".
                                $_POST['pais']."', ".
                                $idNuevo.
                                ");";
                            mysqli_query($link, $query);
                            $query = "insert into telefono_cliente values".
                            "(version,date_created, display_name,".
                                " id_cliente_id,lada,last_updated,num_telefono, tipo_telefono)".
                                "(1,'".date('Y-m-d H:i:s')."', '".$_POST['display']."', ".
                                $idNuevo.", '".$_POSt['lada']."', '".date('Y-m-d H:i:s').
                                "', '".$_POST['numTelefono']."','".$_POST['tipoTelefono']."');";
                            mysqli_query($link,$query);

                }
            }
        
        ?>
                                    <form id="crear" class="form-horizontal" method = "post" action = 'clientes.php' >
                                        <fieldset>
                                            <h4>Display Name</h4>
                                            <input type="text"                  name="display" required="" placeholder="Display Name"/>
                                            <input type = "hidden" name = "crear" value = "true">
                                            <h4>Responsable</h4>
                                            <select                             name="responsable" >
                                                <?php
                                                $query = "select * from usuario, usuario_rol where rol_id < 4 and usuario.id = usuario_rol.usuario_id;";


                                                if(!$rs = mysqli_query($link, $query))
                                                  echo "Error al ejecutar query 2";
                                                else
                                                    while($fila = mysqli_fetch_array($rs))
                                                    {
                                                        $filaId = $fila['id'];
                                                        $displayName = $fila['display_name'];
                                                        echo "<option value='".$filaId."'>".$displayName."</option> <!--RESPONSABLE-->";

                                                    }
                                                ?>
                                            </select>
                                            <h4>Información General</h4>
                                            Nombre de la Institucion
                                            <input type="text"                  name="razonSocial" required="" placeholder="Institución"/>
                                            RFC
                                            <input type="text"                  name="rfc" required="" placeholder="XXXXXXXXXX"/>
                                            <br>
                                            <h4>Representante Legal</h4>
                                            <input type="text" class="hidden"   name="representanteLegal" />
                                            Nombre
                                            <input type="text"                  name="nombreRepresentante"   required="" placeholder="Nombre" />
                                            Apellido
                                            <input type="text"                  name="apellidoRepresentante" required="" placeholder="Apellido" />
                                            Correo Electronico
                                            <input                              name="email" type="email" required="" placeholder="example@server.com"/>
                                            <h4>Dirección de Cliente</h4>
                                            Calle
                                            <input type="text"                  name="calle" required="" placeholder="Calle"/>
                                            Int
                                            <input type="number"                name="numeroInt" required="" placeholder="NumInt"/>
                                            Ext
                                            <input type="number"                name="numeroExt" required="" placeholder="NumExt"/>
                                            Colonia
                                            <input type="text"                  name="colonia" required="" placeholder="Colonia"/>
                                            CP
                                            <input type="number"                name="codigoPostal" required="" placeholder="CP"/>
                                            Ciudad
                                            <input type="text"                  name="ciudad" required="" placeholder="Ciudad"/>
                                            Estado
                                            <input type="text"                  name="estado" required="" placeholder="Estado"/>
                                            Pais
                                            <input type="text"                  name="pais" required="" value="México" placeholder="Pais"/>
                                            <h4>Contacto</h4>
                                            Telefono
                                            <input type="text"                  name="lada" maxlength="3" min="0" max="999"  required="" class="Lada form-control" placeholder="Lada"/>
                                            <input type="text" maxlength="7"    name="numTelefono" required="" placeholder="Tel"/>
                                            Tipo
                                            <select id="tipoTelefono"           name="tipoTelefono">
                                                <option id="Fijo">Fijo</option>
                                                <option id="Celular">Celular</option>
                                                <option id="Fax">Fax</option>
                                            </select>



                                                
                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-primary">Crear</button> 
                                                <button class="btn">Cancelar</button>
                                            </div> <!-- /form-actions -->

                                        </fieldset>
                                    </form>
                                </div>













                            <!--Modificar-->






                                <?php
                                    if(isset($_POST['cargar']) || isset($_POST['modificar'])  )
                                    {
                                        ?><div class="tab-pane active" id="modificar"><?php
                                    }
                                    else
                                    {
                                        ?><div class="tab-pane" id="modificar"> <?php   
                                    }

                                    if(isset($_POST['modificar']) )
                                    {
                                        $query = "update cliente set ".
                                        " razon_social = '".$_POST['razonSocial']."', ".
                                        " rfc = '".$_POST['rfc']."', ".
                                        " representante_legal = '".$_POST['representanteLegal']."', ".
                                        " nombre_representante = '".$_POST['nombreRepresentante']."', ".
                                        " apellido_representante = '".$_POST['apellidoRepresentante']."', ".
                                        " email = '".$_POST['email']."' ".
                                        "where display_name = '".$_POST['display']."';";

                                        if(!$rs = mysqli_query($link, $query))
                                        ;//    echo "Error al ejecutar query update 1 ".$query."|".mysqli_error($link)."|".$query;
                                        

                                        $query = "update direccion_cliente set ".
                                        " calle = '".$_POST['calle']."', ".
                                        " numero_int = '".$_POST['numeroInt']."', ".
                                        " numero_ext = '".$_POST['numeroExt']."', ".
                                        " colonia = '".$_POST['colonia']."', ".
                                        " codigo_postal = '".$_POST['codigoPostal']."', ".
                                        " ciudad = '".$_POST['ciudad']."', ".
                                        " estado = '".$_POST['estado']."', ".
                                        " pais = '".$_POST['pais']."' ".
                                        "where id_cliente_id = (select id from cliente where display_name = '".$_POST['display']."');";

                                        if(!$rs = mysqli_query($link, $query))
                                         ;//   echo "Error al ejecutar query update 2 ".$query."|".mysqli_error($link)."|".$query;
                                        

                                        $query = "update telefono_cliente set ".
                                        " lada = '".$_POST['lada']."', ".
                                        " num_telefono = '".$_POST['numTelefono']."', ".
                                        " tipo_telefono = '".$_POST['tipoTelefono']."' ".
                                        "where id_cliente_id = (select id from cliente where display_name = '".$_POST['display']."');";

                                        if(!$rs = mysqli_query($link, $query))
                                           ;// echo "Error al ejecutar query update 3 ".$query."|".mysqli_error($link)."|".$query;
                                    }
                                
                                    if(isset($_POST['cargar']))
                                    {
                                            $query = "select * from cliente where display_name = '".$_POST['display']."';";
                                            if(!$rs = mysqli_query($link, $query))
                                            {
                                                echo "Error al ejecutar query 4";
                                            }
                                            else
                                                if(!$fila = mysqli_fetch_array($rs))
                                                   ;// echo "error 4";

                                            $query = "select * from direccion_cliente where id_cliente_id = (select id from cliente where display_name = '".$_POST['display']."');";
                                            if(!$rs = mysqli_query($link, $query))
                                               echo "Error al ejecutar query 5";
                                            else
                                                if(!$filaD = mysqli_fetch_array($rs))
                                                 ;//   echo "error 5";

                                            $query = "select * from telefono_cliente where id_cliente_id = (select id from cliente where display_name = '".$_POST['display']."');";
                                            if(!$rs = mysqli_query($link, $query))
                                              echo "Error al ejecutar query 6 ";
                                            else
                                                if(!$filaT = mysqli_fetch_array($rs))
                                             ;//       echo "error 6";
                                    }
                                ?>
                                    <form method = "post" action = "clientes.php">
                                         <h4>Qué cliente deseas modificar?</h4>
                                            <select                             name = "display"   >
                                                <?php
                                                $query = "select display_name, id from cliente where visible = 1;";
                                                if(!$rs = mysqli_query($link, $query))
                                                ;//    echo "<script>alert('Error al ejecutar query 3".$query."');</script><div>".mysqli_error($link);
                                                else
                                                    while($filaDN = mysqli_fetch_array($rs))
                                                    {
                                                        echo "<option id='".$filaDN['id']."'>".$filaDN['display_name']."</option>";
                                                    }
                                                ?>
                                            </select>
                                            <input type = "hidden" name = "cargar" value = "true">
                                            <input type = "submit" value ="Cargar"/>
                                    </form>
                                	<form id="crear" class="form-horizontal" method = "post" action = "clientes.php">
                                       <fieldset>
                                            <h4>Información General</h4>
                                            Nombre de la Institucion
                                            <input type="text"                  name="razonSocial" value = <?= "'".htmlentities($fila['razon_social'])."'" ?> required="" value="" placeholder="Institución"/>
                                            RFC
                                            <input type="text"                  name="rfc"                  value = <?= "'".htmlentities($fila['rfc'])."'" ?> required="" placeholder="XXXXXXXXXX"/>
                                            <br>
                                            <h4>Representante Legal</h4>
                                            <input type="text" class="hidden"   name="representanteLegal"   value = <?= "'".htmlentities($fila['representante_legal'])."'" ?>/>
                                            Nombre
                                            <input type="text"                  name="nombreRepresentante"  value = <?= "'".htmlentities($fila['nombre_representante'])."'" ?> required="" placeholder="Nombre" />
                                            Apellido
                                            <input type="text"                  name="apellidoRepresentante" value = <?= "'".htmlentities($fila['apellido_representante'])."'" ?> required="" placeholder="Apellido" />
                                            Correo Electronico
                                            <input                              name="email" type="email"   value = <?= "'".htmlentities($fila['email'])."'" ?> required="" placeholder="example@server.com"/>
                                            
                                            <h4>Dirección de Cliente</h4>
                                            Calle
                                            <input type="text"                  name="calle"                value = <?= "'".htmlentities($filaD['calle'])."'" ?> required="" placeholder="Calle"/>
                                            Int
                                            <input type="number"                name="numeroInt"            value = <?= "'".htmlentities($filaD['numero_int'])."'" ?> required="" placeholder="NumInt"/>
                                            Ext
                                            <input type="number"                name="numeroExt"            value = <?= "'".htmlentities($filaD['numero_ext'])."'" ?> required="" placeholder="NumExt"/>
                                            Colonia
                                            <input type="text"                  name="colonia"              value = <?= "'".htmlentities($filaD['colonia'])."'" ?> required="" placeholder="Colonia"/>
                                            CP
                                            <input type="number"                name="codigoPostal"         value = <?= "'".htmlentities($filaD['codigo_postal'])."'" ?> required="" placeholder="CP"/>
                                            Ciudad
                                            <input type="text"                  name="ciudad"               value = <?= "'".htmlentities($filaD['ciudad'])."'" ?> required="" placeholder="Ciudad"/>
                                            Estado
                                            <input type="text"                  name="estado"               value = <?= "'".htmlentities($filaD['estado'])."'" ?> required="" placeholder="Estado"/>
                                            Pais
                                            <input type="text"                  name="pais"                 value = <?= "'".htmlentities($filaD['pais'])."'" ?> required="" value="México" placeholder="Pais"/>
                                           
                                           <!-- 
                                            <h4>Contacto</h4>
                                            Telefono
                                            <input type="text"                  name="lada"                 value = <?= "'".htmlentities($filaT['lada'])."'" ?>    maxlength="3" min="0" max="999"  required="" class="Lada form-control" placeholder="Lada"/>
                                            <input type="text" maxlength="7"    name="numTelefono"          value = <?= "'".htmlentities($filaT['num_telefono'])."'" ?>    required="" placeholder="Tel"/>
                                            Tipo
                                            <select id="tipoTelefono"           name="tipoTelefono"   >
                                                <option <?= ($filaT['tipo_telefono']=="Fijo")?"selected": "" ?> id="Fijo">Fijo</option>
                                                <option <?= ($filaT['tipo_telefono']=="Celular")?"selected": "" ?> id="Celular">Celular</option>
                                                <option <?= ($filaT['tipo_telefono']=="Fax")?"selected": "" ?> id="Fax">Fax</option>
                                            </select>

                                            -->
                                            <input type = "hidden" name = "modificar" value= "true"/>
                                            <input type = "hidden" name = "display" value= <?= "'". $_POST['display']."'" ?>>

                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-primary">Modificar</button> 
                                                <button class="btn">Cancelar</button>
                                            </div> <!-- /form-actions -->

                                        </fieldset>
                                    </form>
                                </div>












                                <!--Eliminar-->







                                <?php
                                    if(isset($_GET['eliminar']))
                                    {   
                                        $borradoExito = true;
                                        $query = "select id from cliente where display_name = '".$_GET['aborrar']."'";
                                        if(!$rs = mysqli_query($link, $query))
                                        {
                                            echo "error en query borrar 1|".$query."|".mysqli_error($link);
                                            $borradoExito = false;
                                        }   
                                        else
                                        {
                                            $filaIDCli = mysqli_fetch_array($rs);
                                            $idCliente = $filaIDCli['id'];
                                        }

/*
                                        $query = "delete from direccion_cliente where id_cliente_id = '".$idCliente."'";
                                        if(!$rs = mysqli_query($link, $query))
                                        {
                                            echo "error en query borrar 2|".$query."|".mysqli_error($link);
                                            $borradoExito = false;
                                        }

                                        $query = "delete from telefono_cliente where id_cliente_id = '".$idCliente."'";
                                        if(!$rs = mysqli_query($link, $query))
                                        {
                                            echo "error en query borrar 3|".$query."|".mysqli_error($link);
                                            $borradoExito = false;
                                        }
*/
                                        //$query = "delete from cliente where display_name = '".$_GET['aborrar']."'";
                                        $query = "update cliente set visible = 0 where display_name = '".$_GET['aborrar']."'";
                                        if(!$rs = mysqli_query($link, $query) && $borradoExito)
                                            echo "error en query borrar 4|".$query."|".mysqli_error($link);
                                        else
                                            header('Location: clientes.php');
                                    }
                                ?>

                                <div class="tab-pane" id="eliminar">
                                <form id="crear" class="form-horizontal">
                                        <fieldset>
                                           
                                            <form method = "POST">
                                                 <h4>Qué cliente deseas eliminar?</h4>
                                                    <select                             name = "aborrar" id="selectEliminar"  >
                                                        <?php
                                                        $query = "select display_name, id from cliente;";
                                                        if(!$rs = mysqli_query($link, $query))
                                                         echo "Error al ejecutar query 6";
                                                        else
                                                            while($filaDN = mysqli_fetch_array($rs))
                                                            {
                                                                echo "<option id='".$filaDN['id']."'>".$filaDN['display_name']."</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                    <input type = "hidden" name = "eliminar" value = "true">
                                                    <input onclick="preguntar();" type = "submit" value ="Eliminar"/>
                                            </form>


                                        
                                    


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

<script>     

        var lineChartData = {
            labels: ["10:00am", "10:15am", "10:30am", "10:45am", "11:00am", "11:15am", "11:30am"],
            datasets: [
				{
				    fillColor: "rgba(220,220,220,0.5)",
				    strokeColor: "rgba(220,220,220,1)",
				    pointColor: "rgba(220,220,220,1)",
				    pointStrokeColor: "#fff",
				    data: [65, 59, 90, 81, 56, 55, 40]
				},
				{
				    fillColor: "rgba(151,187,205,0.5)",
				    strokeColor: "rgba(151,187,205,1)",
				    pointColor: "rgba(151,187,205,1)",
				    pointStrokeColor: "#fff",
				    data: [28, 48, 40, 19, 96, 27, 100]
				}
			]

        }

        var myLine = new Chart(document.getElementById("area-chart").getContext("2d")).Line(lineChartData);


        var barChartData = {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [
				{
				    fillColor: "rgba(220,220,220,0.5)",
				    strokeColor: "rgba(220,220,220,1)",
				    data: [65, 59, 90, 81, 56, 55, 40]
				},
				{
				    fillColor: "rgba(151,187,205,0.5)",
				    strokeColor: "rgba(151,187,205,1)",
				    data: [28, 48, 40, 19, 96, 27, 100]
				}
			]

        }    

        $(document).ready(function() {
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        var calendar = $('#calendar').fullCalendar({
          header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
          },
          selectable: true,
          selectHelper: true,
          select: function(start, end, allDay) {
            var title = prompt('Event Title:');
            if (title) {
              calendar.fullCalendar('renderEvent',
                {
                  title: title,
                  start: start,
                  end: end,
                  allDay: allDay
                },
                true // make the event "stick"
              );
            }
            calendar.fullCalendar('unselect');
          },
          editable: true,
          events: [
            {
              title: 'All Day Event',
              start: new Date(y, m, 1)
            },
            {
              title: 'Long Event',
              start: new Date(y, m, d+5),
              end: new Date(y, m, d+7)
            },
            {
              id: 999,
              title: 'Repeating Event',
              start: new Date(y, m, d-3, 16, 0),
              allDay: false
            },
            {
              id: 999,
              title: 'Repeating Event',
              start: new Date(y, m, d+4, 16, 0),
              allDay: false
            },
            {
              title: 'Meeting',
              start: new Date(y, m, d, 10, 30),
              allDay: false
            },
            {
              title: 'Lunch',
              start: new Date(y, m, d, 12, 0),
              end: new Date(y, m, d, 14, 0),
              allDay: false
            },
            {
              title: 'Birthday Party',
              start: new Date(y, m, d+1, 19, 0),
              end: new Date(y, m, d+1, 22, 30),
              allDay: false
            },
            {
              title: 'EGrappler.com',
              start: new Date(y, m, 28),
              end: new Date(y, m, 29),
              url: 'http://EGrappler.com/'
            }
          ]
        });
      });
    </script><!-- /Calendar -->


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

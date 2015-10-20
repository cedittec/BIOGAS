<?php 
  session_start();
  if(!isset($_SESSION['userbio'])) 
  {
    header('Location: login.php');
    exit();
  }
  if (isset($_GET['sitio'])){
    $_SESSION['sitio'] = $_GET['sitio'];
  }
  if (!isset($_SESSION['modulo']))
    $_SESSION['modulo'] = 5;
  if (isset($_GET['modulo']))
    $_SESSION['modulo'] = $_GET['modulo'];

  $modulo_usado = $_SESSION['modulo'];
  
  if(
      $_SESSION['typebio'] != 'SysAdmin' && 
      (
        basename($_SERVER['SCRIPT_NAME']) == "usuarios.php" || 
        basename($_SERVER['SCRIPT_NAME']) == "sitios.php" ||
        basename($_SERVER['SCRIPT_NAME']) == "modulos.php"
      )
    )
    header('Location: logout.php');
    
?>

<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <a class="brand" href="index.php"><img src="img/logo.png"> &nbsp&nbsp&nbsp&nbsp&nbsp Biogás </a>
        <div class="nav-collapse">
          
        <ul class="nav pull-right">
          <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="icon-user"></i> <?= htmlentities($_SESSION['userbio'])." - ".$_SESSION['typebio'] ?> <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a onclick="preguntarSalir();" >Salir</a></li>
              <li><a href="cambio.php" >Cambiar contraseña</a></li>
            </ul>
          </li>
        </ul>
      </div>
     
    </div>
    <!-- /container --> 
  </div>
  <!-- /navbar-inner --> 
</div>
<!-- /navbar -->
<div class="subnavbar">
  <div class="subnavbar-inner">
    <div class="container" style = "width: 90%;">
      <ul class="mainnav">

        <li <?= (basename($_SERVER['SCRIPT_NAME']) == "index.php"?"class = 'active'":"")?> >
          <a href="index.php"><i class="icon-dashboard"></i><span>Panel de control</span> </a> 
        </li>
        <li <?= (basename($_SERVER['SCRIPT_NAME']) == "monitoreo.php"?"class = 'active'":"")?> >
          <a href="monitoreo.php"><i class="icon-dashboard"></i><span>Monitoreo del biofiltro</span> </a> 
        </li>
        <li <?= (basename($_SERVER['SCRIPT_NAME']) == "microturbina.php"?"class = 'active'":"")?> >
          <a href="microturbina.php"><i class="icon-filter"></i><span>Microturbina</span> </a> 
        </li>
        <!---Inicio de la parte que selecciona módulos y sitios-->
        <li class="dropdown"><a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-globe"></i><span>Sitios</span> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <?php
                      include 'connectionTools.php'; 

                      if(!$rs = mysqli_query($link, 'select sitio.display_name, sitio.id from sitio, usuario_sitio where usuario_sitio.sitio_id = sitio.id and usuario_sitio.usuario_id = '.$_SESSION['idbio']))
                        echo "Error al ejecutar query.".mysqli_error($link)." ".$_SESSION['id'];
                      else
                        while($fila = mysqli_fetch_array($rs))
                          echo "<li><a href='".basename($_SERVER['SCRIPT_NAME'])."?sitio=".$fila['id']."'>".$fila['display_name']."</a></li>";
                    ?>
                </ul>
        </li>
        <li class="dropdown"><a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-gears"></i><span>Módulos</span> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                   <?php
                        if(isset($_SESSION['sitio']))
                        {
                          if(!$rs = mysqli_query($link, 'select id, display_name from modulo where sitio_id = '.$_SESSION['sitio']))
                            echo "Error al ejecutar query.".mysqli_error($link);
                          else
                            while($fila = mysqli_fetch_array($rs)){
                              $option =  "<li><a href='".basename($_SERVER['SCRIPT_NAME'])."?modulo=".$fila['id']."'>".$fila['display_name']."</a></li>";
                              echo $option;
                            }
                          
                        }                        
                    ?>
                </ul>
        </li>
        <!--- Fin del selector de módulos y sitios -->
        <?php
          if($_SESSION['typebio'] == 'SysAdmin')
          {
              ?>
                  <li class="dropdown"><a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-gear"></i><span>Configuración</span> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                      <li><a href="modulos.php">Módulos</a></li>
                      <li><a href="usuarios.php">Usuarios</a></li>
                      <li><a href="sitios.php">Sitios</a></li>
                    </ul>
                  </li>
              <?php
            }
          ?>        
      </ul>
    
    </div>
    <!-- /container --> 
  </div>
  <!-- /subnavbar-inner --> 
</div>
<!-- /subnavbar -->

    <script type = 'text/javascript'>           
        function preguntarSalir()  
        {               
            var eliminar = confirm('Seguro que deseas salir?');                 
            if(eliminar) 
            {
                location.href='logout.php';
            }          
        }
    </script>
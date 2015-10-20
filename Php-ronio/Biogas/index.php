<?php include 'indexController.php'; ?> 

<!DOCTYPE html>
<html lang="en">
<head>
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
  <link href="js/guidely/guidely.css" rel="stylesheet">
  <link href="css/pages/reports.css" rel="stylesheet">
  <link href="css/pages/dashboard.css" rel="stylesheet">

  <!-- El script de la gráfica legendaria...-->
  <script src = "js/Chart/Chart.Doughnut.js"></script>

  <!-- JQuery -->
  <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
  <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
  
</head>
<body>

<?php include 'head.php'; ?>
<!-- Script de la página... -->
  <?php
    $rs = mysqli_query($link,"select ch4,h2s,co2,o2 from controlador_microturbina where modulo_id = $modulo_usado order by date_created desc limit 1;");
    $row = mysqli_fetch_array($rs);

    $ch4 = $row['ch4'];
    $h2s = $row['h2s'];
    $co2 = $row['co2'];
    $o2 = $row['o2']; 

    
    
    if (!$ch4)
        $ch4 = 25;
    if (!$h2s)
        $h2s = 25;
    if (!$co2)
        $co2 = 25;
    if (!$o2)
        $o2 = 25;
    
    $total = $ch4+$h2s+$co2+$o2;

    $porcentaje_ch4 = 0;
    $porcentaje_h2s = 0;
    $porcentaje_co2 = 0;
    $porcentaje_o2 = 0;

    if ($total != 0){
        $porcentaje_ch4 = round(($ch4*100/$total),2);
        $porcentaje_h2s = round(($h2s*100/$total),2);
        $porcentaje_co2 = round(($co2*100/$total),2);
        $porcentaje_o2 = round(($o2*100/$total),2);
    }
    else{
        $porcentaje_ch4=$porcentaje_h2s=$porcentaje_co2=$porcentaje_o2=25;
    }

  ?>
  <script>
      var metano = <?php echo $ch4 ?>;
      var acido_sulfurico = <?php echo $h2s ?>;
      var dioxido_de_carbono = <?php echo $co2 ?>;
      var oxigeno = <?php echo $o2 ?>;

      var doughnutData = [
                {
                    value: metano,
                    color:"#F7464A",
                    highlight: "#FF5A5E",
                    label: "Metano"
                },
                {
                    value: acido_sulfurico,
                    color: "#46BFBD",
                    highlight: "#5AD3D1",
                    label: "Ácido sulfúrico"
                },
                {
                    value: dioxido_de_carbono,
                    color: "#FDB45C",
                    highlight: "#FFC870",
                    label: "Dióxido de carbono"
                },
                {
                    value: oxigeno,
                    color: "#949FB1",
                    highlight: "#A8B3C5",
                    label: "Oxígeno"
                }

            ];

  </script>
  <script>
  //Este será el script principal de la página.
    window.onload  = function(){
        var ctx = document.getElementById("donut-chart").getContext("2d");
        window.myDoughnut = new Chart(ctx).Doughnut(doughnutData, {responsive : true});
    };
  </script>
<div class="main">
  <div class="main-inner">
    <div class="container">
      <div class="row">	      	
                        <div class="span5">
                        <!-- /widget -->
                            <div class="widget">
                                <div class="widget-header"> <i class="icon-signal"></i>
                                    <h3> Porcentaje de sustancias</h3>
                                </div>
                                <!-- /widget-header -->
                                <div class="widget-content">

                                    <canvas id="donut-chart" class="chart-holder" width="inherit" height="250" style="padding-bottom:20px;"></canvas>

                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th> </th>
                                                <th> Sustancia </th>
                                                <th> Porcentaje</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td> <i class="icon-stop" style="color:#F7464A"> CH4 </i> </td>
                                                <td> Metano </td>
                                                <td> <?php echo $porcentaje_ch4; ?>%</td>
                                            </tr>
                                            <tr>
                                                <td> <i class="icon-stop" style="color:#46BFBD"> H2S </i> </td>
                                                <td> Ácido sulfhídrico </td>
                                                <td ><?php echo $porcentaje_h2s; ?>%</td>
                                            </tr>
                                            <tr>
                                                <td> <i class="icon-stop" style="color:#FDB45C"> C02 </i>  </td>
                                                <td> Dióxido de carbono </td>
                                                <td><?php echo $porcentaje_co2; ?> %</td>
                                            </tr>
                                            <tr>
                                                <td> <i class="icon-stop" style="color:#4D5360"> O2 </i> </td>
                                                <td> Oxígeno </td>
                                                <td> <?php echo $porcentaje_o2; ?> %</td>
                                            </tr>


                                        </tbody>
                                    </table>
                                    <!-- /area-chart --> 
                                </div>
                                <!-- /widget-content --> 
                            </div>
                            <!-- /widget -->
                        </div>
                            <?php 
                                //Obtener la corriente y voltaje trifásicos de los sensores
                                $rs = mysqli_query($link,"select va,vb,vc,ia,ib,ic from sensor_microturbina where modulo_id = $modulo_usado order by date_created desc limit 1;");
                                $row = mysqli_fetch_array($rs);
                                $sensor_va = ($row['va'] ? $row['va'] : 0 );
                                $sensor_vb = ($row['vb'] ? $row['vb'] : 0 );
                                $sensor_vc = ($row['vc'] ? $row['vc'] : 0 );
                                $sensor_ia = ($row['ia'] ? $row['ia'] : 0 );
                                $sensor_ib = ($row['ib'] ? $row['ib'] : 0 );
                                $sensor_ic = ($row['ic'] ? $row['ic'] : 0 );
                                $sensor_voltaje_trifasico = 0;
                                $sensor_corriente_trifasica = 0;

                                //Obtener la corriente y voltaje trifásicos de la caja negra
                                $rs = mysqli_query($link,"select va,vb,vc,ia,ib,ic, flujo from controlador_microturbina where modulo_id = $modulo_usado order by date_created desc limit 1;");
                                $row = mysqli_fetch_array($rs);
                                $caja_negra_va = ($row['va'] ? $row['va'] : 0 );
                                $caja_negra_vb = ($row['vb'] ? $row['vb'] : 0 );
                                $caja_negra_vc = ($row['vc'] ? $row['vc'] : 0 );
                                $caja_negra_ia = ($row['ia'] ? $row['ia'] : 0 );
                                $caja_negra_ib = ($row['ib'] ? $row['ib'] : 0 );
                                $caja_negra_ic = ($row['ic'] ? $row['ic'] : 0 );
                                $caja_negra_voltaje_trifasico = 0;
                                $caja_negra_corriente_trifasica = 0;

                                $microturbina_flujo = ($row['flujo'] ? $row['flujo'] : 0 );

                                $rs = mysqli_query($link,"select flujo from controlador_biofiltro where modulo_id = $modulo_usado order by date_created desc limit 1;");
                                $biogas_flujo = ($row['flujo'] ? $row['flujo'] : 0 );
                                

                            ?>
                            <div class="span7">
                                <div class="widget widget-nopad">
                                    <div class="widget-header"> <i class="icon-list-alt"></i>
                                        <h3> Energía eléctrica (Sensores)</h3>
                                    </div>
                                    <!-- /widget-header -->
                                    <div class="widget-content">
                                        <div class="row">
                                            <div class="col-xs-6 col-md-6" style="text-align: center;">
                                            <table align = "center">
                                                <tr>
                                                    <td>
                                                        <div class="span2">
                                                            <h1><i class="icon-bolt"></i></h1>
                                                            <h4 class="text-muted">Corriente Fase A</h4>
                                                            <h2><?php echo $sensor_ia; ?> A</h2>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class = "span4">
                                                            <h1><i class="icon-bolt"></i></h1>
                                                            <h4 class="text-muted">Corriente Fase B</h4>
                                                            <h2><?php echo $sensor_ib; ?> A</h2>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="span2">
                                                            <h1><i class="icon-bolt"></i></h1>
                                                            <h4 class="text-muted">Corriente Fase C</h4>
                                                            <h2><?php echo $sensor_ic; ?> A</h2>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>

                                            <table align = "center">
                                                <tr>
                                                    <td>
                                                        <div class="span2">
                                                            <h1><i class="icon-bolt"></i></h1>
                                                            <h4 class="text-muted">Voltaje Fase A</h4>
                                                            <h2><?php echo $sensor_va; ?> A</h2>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class = "span4">
                                                            <h1><i class="icon-bolt"></i></h1>
                                                            <h4 class="text-muted">Voltaje Fase B</h4>
                                                            <h2><?php echo $sensor_vb; ?> A</h2>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="span2">
                                                            <h1><i class="icon-bolt"></i></h1>
                                                            <h4 class="text-muted">Voltaje Fase C</h4>
                                                            <h2><?php echo $sensor_vc; ?> A</h2>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                    </div>
                                </div>

                                <br>
                                <div class="widget widget-nopad">
                                    <div class="widget-header"> <i class="icon-list-alt"></i>
                                        <h3> Energía eléctrica (Controlador)</h3>
                                    </div>
                                    <!-- /widget-header -->
                                    <div class="widget-content">
                                        <div class="row">
                                            <div class="col-xs-6 col-md-6" style="text-align: center;">
                                            <table align = "center">
                                                <tr>
                                                    <td>
                                                        <div class="span2">
                                                            <h1><i class="icon-bolt"></i></h1>
                                                            <h4 class="text-muted">Corriente Fase A</h4>
                                                            <h2><?php echo $caja_negra_ia; ?> A</h2>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class = "span4">
                                                            <h1><i class="icon-bolt"></i></h1>
                                                            <h4 class="text-muted">Corriente Fase B</h4>
                                                            <h2><?php echo $caja_negra_ib; ?> A</h2>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="span2">
                                                            <h1><i class="icon-bolt"></i></h1>
                                                            <h4 class="text-muted">Corriente Fase C</h4>
                                                            <h2><?php echo $caja_negra_ic; ?> A</h2>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>

                                            <table align = "center">
                                                <tr>
                                                    <td>
                                                        <div class="span2">
                                                            <h1><i class="icon-bolt"></i></h1>
                                                            <h4 class="text-muted">Voltaje Fase A</h4>
                                                            <h2><?php echo $caja_negra_va; ?> A</h2>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class = "span4">
                                                            <h1><i class="icon-bolt"></i></h1>
                                                            <h4 class="text-muted">Voltaje Fase B</h4>
                                                            <h2><?php echo $caja_negra_vb; ?> A</h2>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="span2">
                                                            <h1><i class="icon-bolt"></i></h1>
                                                            <h4 class="text-muted">Voltaje Fase C</h4>
                                                            <h2><?php echo $caja_negra_vc; ?> A</h2>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            <div class="row">
                                <div class="span4">
                                    <div class="widget">
                                        <div class="widget-header">
                                            <i class="icon-list-alt"></i>
                                            <h3> Biogás</h3>
                                        </div>
                                        <!-- /widget-header -->
                                        <div class="widget-content">
                                            <div class="widget-box">
                                                <div class="widget-content" style="text-align: center; margin: 15px;">
                                                    <h1><i class="icon-tint"></i></h1>
                                                    <h4 style="color: #777">Flujo</h4>
                                                    <h2><?php echo $biogas_flujo; ?> M&sup2;/s</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="span1">  </div>

                                <div class="span3">
                                    <div class="widget">
                                        <div class="widget-header">
                                            <i class="icon-list-alt"></i>
                                            <h3> Microturbina</h3>
                                        </div>
                                        <!-- /widget-header -->
                                        <div class="widget-content">
                                            <div class="widget-box">
                                                <div class="widget-content" style="text-align: center; margin: 15px;">
                                                    <h1><i class="icon-tint"></i></h1>
                                                    <h4 style="color: #777">Flujo</h4>
                                                    <h2><?php echo $microturbina_flujo; ?> M&sup2;/s</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
        
      </div>
      <!-- /row --> 
    </div>
    <!-- /container --> 
  </div>
  <!-- /main-inner --> 
</div>
<!-- /main -->

<script>
  function cerrar()
  {
    document.getElementById("modal").innerHTML="";
  }
</script>

<?php include 'foot.php'; ?>
 <div id="modal">
    <?php 
      if(isset($_GET['elegir']))
      {
      ?>
                      <div id="guide-welcome" class="guidely-guide" style="position: absolute; top: 75px; left: 50%; margin-left: -178px; display: block;">
                          <div class="guidely-popup">
                              <div class="guidely-guide-pad" align = "center">
                                <h4>Primero elige tu sitio y módulo!</h4>
                                        <table width = "100%"><tr>
                                          <td align = "center" width = "50%">
                                                      <li class="dropdown"><a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-globe"></i><span>Sitios</span> <b class="caret"></b></a>
                                                        <ul class="dropdown-menu">
                                                            <?php
                                                              //include 'connectionTools.php'; 
                                                              if(!$link) 
                                                              {
                                                                echo "<script>alert('no link!');</script>";
                                                              }
                                                              $queryStr = 'select sitio.nombre, sitio.id from sitio, usuario_sitio where usuario_sitio.sitio_id = sitio.id and usuario_sitio.usuario_id = '.$_SESSION['idbio'];
                                                              if(!$rs = mysqli_query($link, $queryStr))
                                                                echo "Error al ejecutar query sitios.".mysqli_error($link)."|".$queryStr;
                                                              else
                                                                for($i = 0; $fila = mysqli_fetch_array($rs); $i++)
                                                                  echo "<li><a href='".basename($_SERVER['SCRIPT_NAME'])."?sitio=".$fila['id']."&elegir=true'>".$fila['nombre']."</a></li>";
                                                            ?>
                                                        </ul>
                                                      </li>
                                          </td>
                                          <td align = "center">
                                                      <li class="dropdown"><a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-gears"></i><span>Módulos</span> <b class="caret"></b></a>
                                                          <ul class="dropdown-menu">
                                                             <?php
                                                                  if(isset($_SESSION['sitio']))
                                                                  {
                                                                    $queryStr = 'select nombre, id from modulo where sitio_id = '.$_SESSION['sitio'];
                                                                    if(!$rs = mysqli_query($link, $queryStr))
                                                                      echo "Error al ejecutar query modulos.".mysqli_error($link)."|".$queryStr."|";
                                                                    else
                                                                      for($i = 0; $fila = mysqli_fetch_array($rs); $i++)
                                                                        echo "<li onclick='cerrar();'><a href='".basename($_SERVER['SCRIPT_NAME'])."?modulo=".$fila['id']."'>".$fila['nombre']."</a></li>";
                                                                  }                        
                                                              ?>
                                                          </ul>
                                                      </li>
                                          </td>
                                        </tr></table>
                              </div>
                          </div>
                      </div>
      <?php
      }
    ?>
  </div>
</body>
</html>
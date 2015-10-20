<?php ?>

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
  <link href="css/pages/reports.css" rel="stylesheet">
  <link href="css/pages/dashboard.css" rel="stylesheet">

  <!-- JQuery -->
  <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
  <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
  <script>
  //Actualizaciones automáticas de la tabla anterior...
        var zelda = 'bin/microturbina.php';
        var ocarina = 'bin/cajaNegra.php';
        var noIndex = 0;
        var mMax = 10;
        window.onload = function(){
          $('#primeros').click(function(){
            for (var i = 1; i<=15;i++){
              var index = noIndex+i;
              $('#b'+index).hide();
            }
            noIndex = 0;
            for (var i = 1; i<=15;i++){
              var index = noIndex+i;
              $('#b'+index).show();
            }
          });
          $('#ultimos').click(function(){
            for (var i = 1; i<=15;i++){
              var index = noIndex+i;
              $('#b'+index).hide();
            }
            while(noIndex < mMax-15)
              noIndex += 15;
            for (var i = 1; i<=15;i++){
              var index = noIndex+i;
              $('#b'+index).show();
            }
          });
          $('#anteriores').click(function(){
            if  (noIndex == 0)
              return;
            for (var i = 1; i<=15;i++){
              var index = noIndex+i;
              $('#b'+index).hide();
            }
            noIndex -= 15;
            for (var i = 1; i<=15;i++){
              var index = noIndex+i;
              $('#b'+index).show();
            }
          });
          $('#siguientes').click(function(){
            if (noIndex +16 > mMax)
              return;
            for (var i = 1; i<=15;i++){
              var index = noIndex+i;
              $('#b'+index).hide();
            }
            noIndex += 15;
            for (var i = 1; i<=15;i++){
              var index = noIndex+i;
              $('#b'+index).show();
            }
          });
        }
        function actualizaMicroturbina(){
          $.post(zelda,
            function(data){
                var array = data.split("<");
                for (var i= 0;i<array.length;i++){
                    var temp = array[i].split(">");
                    if (temp.length >= 8)
                      for (var j = 0; j< 8;j++)
                        document.getElementById("m"+i+"c"+j).innerHTML= 
                          temp[j];
                }
            }
          );
        };
        function actualizaCajaNegra(){
          $.post(ocarina,
            function(data){
                var array = data.split("<");
                for (var i= 0;i<array.length;i++){
                    var temp = array[i].split(">");
                    if (temp.length >= 8)
                      for (var j = 0; j< 8;j++)
                        document.getElementById("c"+i+"c"+j).innerHTML= 
                          temp[j];
                }
            }
          );
        };

        function rellena(date_created){
          zelda = 'bin/microturbina.php?i=' + date_created;
          ocarina = 'bin/cajaNegra.php?i='+ date_created;
          actualizaMicroturbina();
          actualizaCajaNegra();
        };
      </script>
</head>
<body>

<?php 
  session_start(); 
  include 'connection.php'; 
  include 'head.php'; 

  $link = mysqli_connect($db_url, $db_user,$db_pass, $db_name, $db_port);
  if (!$link)
    echo "<!--- No se deja el link -->";
  $rs = mysqli_query($link, "select count(*) from controlador_microturbina where modulo_id = $modulo_usado;");
  if (!$rs)
    echo "<!--- No se deja la query... -->";
  $row = mysqli_fetch_array($rs);
  $total = $row[0];
  $filas = 10;
  $counter = 10;
  if (($total/$filas) < $counter)
    $counter = $total/$filas;
  if ($filas > $total)
    $filas = $total;
  echo "<!--- Páginas: $counter Filas por página: $filas Resultado de la query es:  $total -->";
  
  //$rs = mysqli_query($link, "select * from controlador_microturbina order by date_created desc limit 20;");

?>

<div class="main">
  <div class="main-inner">
    <div class="container">
      <div class="row">	      	
	      <div class="span12">


                    <div id="list-microturbina" class="content scaffold-list" role="main">
                        <h1>Microturbina</h1>
                        <ul class="nav nav-tabs nav-justified" role="tablist" id="microTab">
                            <li class="active"><a href="#sensores" role="tab" data-toggle="tab">Sensores</a></li>
                            <li class=""><a href="#cajaNegra" role="tab" data-toggle="tab">Controlador</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="sensores">
                                <div class="table-responsive">
                                    <table id="microturbinaTabla" class="table table-striped table-bordered">
                                        <tr>
                                            <th>Corriente lineal 1</th>
                                            <th>Voltaje lineal 1</th>
                                            <th>Corriente lineal 2</th>
                                            <th>Voltaje lineal 2</th>
                                            <th>Corriente lineal 3</th>
                                            <th>Voltaje lineal 3</th>
                                            <th>Flujo</th>
                                            <th>Hora de registro</th>
                                        </tr>
                                       <?php
                                            $rs = mysqli_query($link,"select ia,ib,ic,va,vb,vc,flujo,date_created from controlador_microturbina where modulo_id = $modulo_usado order by date_created desc limit 10");
                                            $j = 0;
                                            while($row = mysqli_fetch_array($rs)){
                                                echo "\n<tr>\n";
                                                for ($i = 0; $i<8;$i++)
                                                    echo "<td id = 'm".$j."c".$i."'>".$row[$i]."</td>";
                                                echo "\n</tr>";
                                                $j++;
                                            }
                                       ?>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="cajaNegra">
                                <div class="table-responsive">
                                    <table id="microturbinaTabla" class="table table-striped table-bordered">
                                        <tr>
                                            <th>Corriente lineal 1</th>
                                            <th>Voltaje lineal 1</th>
                                            <th>Corriente lineal 2</th>
                                            <th>Voltaje lineal 2</th>
                                            <th>Corriente lineal 3</th>
                                            <th>Voltaje lineal 3</th>
                                            <th>Hora de registro</th>
                                        </tr>
                                        <?php
                                            $rs = mysqli_query($link,"select ia,ib,ic,va,vb,vc,date_created from sensor_microturbina where modulo_id = $modulo_usado order by date_created desc limit 10");
                                            $j = 0;
                                            while($row = mysqli_fetch_array($rs)){
                                                echo "\n<tr>\n";
                                                for ($i = 0; $i<8;$i++)
                                                    echo "<td id = 'c".$j."c".$i."'>".$row[$i]."</td>";
                                                echo "\n</tr>";
                                                $j++;
                                            }
                                       ?>
                                    </table>
                                </div>
                            </div>
                                </div>
                        </div>

        </div>

      </div>
      <div name = "pagination" align = "center">
        <button class='btn btn-default' id = "primeros">Primeros</button>
        <button class='btn btn-default' id = "anteriores">...</button>
        <?php
          $rs = mysqli_query($link,
            "select date_created from controlador_microturbina where modulo_id = $modulo_usado order by date_created desc");
          $cuantasM = mysqli_num_rows($rs);
          $cuantasS = (($cuantasM+(10-($cuantasM%10)))/10);
          for ($i = 0; $i< $cuantasM-1; $i+=10){
            mysqli_data_seek($rs,$i);
            $row = mysqli_fetch_row($rs);
            $temp = $row[0];
            $j = 1+$i/10;
            echo "

            <button class='btn btn-default' id = 'b$j' onclick = \"rellena('$temp');\">$j</button>
            ";
          }
          echo "<script> 
            mMax = $cuantasS;
            for (var i = 16; i<= $cuantasS;i++)
              $('#b'+i).hide();

            </script>";

        ?>

        <button class='btn btn-default' id = "siguientes">...</button>
        <button class='btn btn-default' id = "ultimos">Últimos</button>
        <button class='btn btn-default' id = "refrescar" onclick = 'location.href="microturbina.php";'>Refrescar</button>


        
    </div>
      <!-- /row --> 
    </div>

<!-- /container --> 
  </div>
  <!-- /main-inner --> 
</div>
<!-- /main -->

<?php include 'foot.php'; ?>

</body>
</html>
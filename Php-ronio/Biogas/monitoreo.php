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
  <link href="css/pages/reports.css" rel="stylesheet">
  <link href="css/pages/dashboard.css" rel="stylesheet">
  <!-- JQuery -->
  <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
  <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
</head>
<body>

<?php 
    include 'head.php'; 


    session_start(); 
    include 'connection.php';
    $link = mysqli_connect($db_url, $db_user,$db_pass, $db_name, $db_port);
    if($rs = mysqli_query($link, "select * from controlador_biofiltro where modulo_id = $modulo_usado order by date_created desc limit 1;")){
      $row = mysqli_fetch_array($rs);
    ?>

    <script>
      var counter = 0; 
      setInterval(
        function(){
          $.post("bin/monitoreo.php",function( data ) {
            var array = data.split(">");
            if (array.length < 4)
              return;
            counter++;
            $( "#h2s" ).html( array[0] +"");
            $( "#o2" ).html ( array[1] +"");
            $( "#ch4" ).html( array[2] +"");
            $( "#co2" ).html( array[3] +"");
          });
        },2000
      );

    </script>

      <?php
    }

?>

<div class="main">
  <div class="main-inner">
    <div class="container">
      <div class="row">	      	
	      <div class="span12">  


            <div class="nav">
                  <div role="main">
                      <h1>Biofiltro - Monitoreo</h1>
                      <br>
                      <table class="table table-striped table-bordered">
                          <thead>
                              <tr>
                                  <th>H2S</th>
                                  <th>O2</th>
                                  <th>CH4</th>
                                  <th>CO2</th>
                              </tr>
                          </thead>
                          <tbody>
                                  <tr>
                                      <td id = "h2s"> <?php echo $row['h2s']; ?></td>
                                      <td id = "o2"> <?php echo $row['o2']; ?></td>
                                      <td id = "ch4"> <?php echo $row['ch4']; ?></td>
                                      <td id = "co2"> <?php echo $row['co2']; ?></td>
                                  </tr>
                              </g:each>
                          </tbody>
                      </table>
                  </div>
            </div>

            <br><br><br><br><br><br><br><br><br>

        </div>
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
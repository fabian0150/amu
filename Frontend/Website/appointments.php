<?php
	$web = true;
	include_once("scripts/config.php");
	include_once("scripts/secure.php");
?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Music Live AMU</title>

	<link rel="stylesheet" href="dist/datepickk.min.css">
    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">


    <!-- Custom styles for this template -->
    <link href="css/logo-nav.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">

  </head>

  <body>
	<script src="dist/datepickk.min.js"></script>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark nav-red fixed-top">
      <div class="container">
        <a class="navbar-brand" href="#">
          <img src="img/logo.png" width="150" height="30" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
			<?php include_once('includes/navbar.php'); ?>
        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <div class="container">
  
		
		<div class="row" id="#">
		
			<div class='col-md-12'>
			<h2 class="mt-5">Termine</h2>
			<hr>
				<table class="table">
				  <thead class="thead-blue">
					<tr>
					  <th scope="col">Band</th>
					  <th scope="col">Veranstaltungsort</th>
					  <th scope="col">Datum</th>
					  <th scope="col"></th>
					</tr>
				  </thead>
				  <tbody id="next_appointments">
					<!--<tr>
					  <th scope="row">Rocketbitter</th>
					  <td>Sporthalle Wels</td>
					  <td>15.01.2019 18:00</td>
					  <td><a href='#' class='btn btn-primary' onClick="">Absagen</a></td>
					</tr>-->
					
				  </tbody>
				</table>
			</div>
		</div>
	<?php include_once('includes/footer.php'); ?>
    </div>
    <!-- /.container -->
	
    <!-- Bootstrap core JavaScript -->
    <?php include_once('includes/js.php'); ?>
	<script src="js/appointments.js"></script>
  </body>
  
  <script>
	var session_user_id = <?php echo $_SESSION['session_user']; ?>;
	
	$(function() {
		init();
	});
	
	function init() { 
		$("#appointment").addClass("active");
		loadAppointments(session_user_id, 0);
		
	}
</script>
</html>

<?php
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

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/logo-nav.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">

  </head>

  <body>

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
    
       <div class="panel panel-default">
		<div class="panel-body">
			<h1 class="mt-5">Aktuelle Termine</h1>
			<div id="appointments"></div>
		</div>
	  </div>
    </div>
    <!-- /.container -->

    <!-- Bootstrap core JavaScript -->
    <?php include_once('includes/js.php'); ?>
	<script src="js/dashboard.js"></script>
  </body>
  <?php include_once('includes/footer.php'); ?>
  <script>
	$(function() {
		init();
	});
	
	function init() { 
		$("#dashboard").addClass("active");
		loadAppointments();
	}
</script>
</html>

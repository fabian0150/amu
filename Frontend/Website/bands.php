<?php
	$web = true;
	include_once("scripts/config.php");
	include_once("scripts/secure.php");
?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
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
		<h2 class="mt-5">Deine Bands</h2>
		<hr>
		<div class="row" id="user_bands"></div>
		
		<h2 class="mt-5">Band beitreten</h2>
		<hr>
		<div class="row row-centered" id="user_joinband">
				<div class='col-md-12'>
					<form>
						<div class="form-group">
							<img src='img/bands/testband.jpg' class='rounded-circle' alt='' height='150px' width='200px' id="band_image">
							<select class="form-control" id="bands_select" onChange="changeBandImage(this.value);">
							 
							</select>
							
						</div>
						
						<a href='#user_joinband' class='btn float-right' onClick="addUserToBand(<?php echo $_SESSION['session_user']; ?>);">Beitreten</a>
				  </form>
						<!--<div class='card card-inline'>
							<div class='card-body'>
								
								<h5 class='card-title' style='height: 80px;'>${obj_info[0].name}</h5>
								<div class='card-text'>
									<p><span class='span-bold'>Website: </span><br>
									<span class='span-bold'>Besitzer: </span>${obj_info[0].leader_username}</p>
								</div>
								
								<a href='#' class='btn btn-primary'>Verlassen</a>
							</div>
						</div>-->
				</div>
		
		</div>
		
		<h2 class="mt-5">Band erstellen</h2>
		<hr>
		<form>
		  <div class="form-group row">
			<label for="band_name" class="col-sm-2 col-form-label">Bandname</label>
			<div class="col-sm-10">
			  <input type="text" class="form-control" id="band_name" placeholder="Bandname">
			</div>
		  </div>
		  <div class="form-group row">
			<label for="website_url" class="col-sm-2 col-form-label">Website</label>
			<div class="col-sm-10">
			  <input type="text" class="form-control" id="website_url" placeholder="Website">
			</div>
		  </div>
		  <div class="form-group row">
			<label for="band_logo" class="col-sm-2 col-form-label">Bandlogo</label>
			<div class="col-sm-10">
			  <input type="text" class="form-control" id="band_logo" placeholder="Bandlogo">
			</div>
		  </div>

			<a href='#user_createband' class='btn float-right' onClick="createBand(<?php echo $_SESSION['session_user']; ?>);">Erstellen</a>
			
	
		</form>
		<?php include_once('includes/footer.php'); ?>
	</div>
		
    <!-- /.container -->
	
    <!-- Bootstrap core JavaScript -->
    <?php include_once('includes/js.php'); ?>
	<script src="js/bands.js"></script>
  </body>
  
  <script>
	var session_user_id = <?php echo $_SESSION['session_user']; ?>;
	
	$(function() {
		init();
	});
	
	function init() { 
		$("#groups").addClass("active");
		loadUserBands(session_user_id);
		loadBandsSelect();
	}
</script>
</html>

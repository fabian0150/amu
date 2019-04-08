
<!DOCTYPE html>
<html>
<head>
	<title>Music Live AMU</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">
</head>
<body>

	<div class="header">
		<img src="img/logo.png" height="40%" width="70%"/>
		<h2>Artist Manager Utility</h2>
		<h1>Login</h1>
	</div>
	
	<form action="" id="#loginForm" class="form-style">

		

		<div class="input-group">
			<label>Username</label>
			<input type="text" name="username" id="username">
		</div>
		<div class="input-group">
			<label>Passwort</label>
			<input type="password" name="password" id="password">
		</div>
		<div class="input-group" id="login_btn">
			<a  class="btn" name="login_user" id="login_user">Einloggen</a>
		</div>
		
		<!--<hr><p>
			Noch nicht registriert? <a href="register.php">Registrieren</a>
		</p>-->
		<div id="result">
		
		</div>
	</form>
	
	

</body>
<?php include_once('includes/footer.php'); ?>

 <?php include_once('includes/js.php'); ?>
<script>
	$(function() {
		init();
	});
	
	function init() { 
		$("#loginForm").submit(function() {
			return false;
		});
		
		$("#login_user").click(function() {
			login();
		});
	}

</script>



</html>
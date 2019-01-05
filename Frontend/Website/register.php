
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
		<h1>Registrieren</h1>
	</div>
	
	<form method="post" action="scripts/user/_login.php" id="registerForm" class="form-style">

	

		<div class="input-group">
			<label>Username</label>
			<input type="text" name="username" id="username">
		</div>
		<div class="input-group">
			<label>E-Mail</label>
			<input type="text" name="email" id="email">
		</div>
		<div class="input-group">
			<label>Passwort</label>
			<input type="password" name="password_1" id="password_1">
		</div>
		<div class="input-group">
			<label>Passwort wiederholen</label>
			<input type="password" name="password_2" id="password_2">
		</div>
		<div class="input-group" id="login_btn">
			<a  class="btn" name="register_user" id="register_user">Registrieren</a>
		</div>
		<hr>
		<p>
			Bereits registriert? <a href="index.php">Einloggen</a>
		</p>
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
		$("#registerForm").submit(function() {
			return false;
		});
		
		$("#register_user").click(function() {
			register();
		});
	}
</script>
</html>
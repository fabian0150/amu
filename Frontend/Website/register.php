
<!DOCTYPE html>
<html>
<head>
	<title>Music Live AMU</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">
</head>
<body>

	<div class="header">
		<img src="img/logo.png" height="40%" width="70%"/>
		<h2>Artist Manager Utility</h2>
		<h1>Registrieren</h1>
	</div>
	
	<form method="post" action="scripts/user/_login.php">

	

		<div class="input-group">
			<label>Username</label>
			<input type="text" name="username" >
		</div>
		<div class="input-group">
			<label>E-Mail</label>
			<input type="text" name="email">
		</div>
		<div class="input-group">
			<label>Passwort</label>
			<input type="password" name="password_1">
		</div>
		<div class="input-group">
			<label>Passwort wiederholen</label>
			<input type="password" name="password_2">
		</div>
		<div class="input-group">
			<button type="submit" class="btn" name="register_user">Registrieren</button>
		</div>
		<hr>
		<p>
			Bereits registriert? <a href="index.php">Einloggen</a>
		</p>
	</form>
	<div class="footer"> 
		<p>Copyright 2019 © Fabian Ortner & Robin Stehrlein</p>
	</div>

</body>
</html>
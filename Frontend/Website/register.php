
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
	
	<form method="post" action="scripts/user/_login.php" id="registerForm">

	

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
	<?php include_once('includes/footer.php'); ?>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
	
	function register() {
		//$('#result').addClass('loading');
		var username_str = document.getElementById("username").value;
		var email_str = document.getElementById("email").value;
		var password_1_str = document.getElementById("password_1").value;
		var password_2_str = document.getElementById("password_2").value;
		$('#result').empty();
		$('#result').removeClass("error");
		$('#result').removeClass("success");
		
		$.post("scripts/user/secure_register.php", {username: username_str, email: email_str, password_1: password_1_str, password_2: password_2_str}, function(data){
			if(data.length > 0) {
				var obj = jQuery.parseJSON(JSON.stringify(data));
				if(obj[0].code == 1) {
					console.log("Registrierung erfolgreich");
					
					var html_str = "<p>Registrierung erfolgreich</p><p>Weiterleitung zum Login...</p>";
					
					$('#result').html(html_str);
					$('#result').addClass("success");
					$('#login_btn').hide();
					setTimeout(function(){ window.location.replace("https://amu.tkg.ovh/index.php"); }, 2000);
				} else {
					console.log("Registrierung nicht erfolgreich");
					var html_str = "";
					for(var i = 0; i < obj.length; i++) {
						html_str += "<p>" + obj[i].error + "</p>";
					}
					$('#result').html(html_str);
					$('#result').addClass("error");
				}
			}
		});
    }

</script>
</html>
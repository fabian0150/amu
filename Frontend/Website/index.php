
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
		<h1>Login</h1>
	</div>
	
	<form action="" id="#loginForm">

		

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
		<hr>
		<p>
			Noch nicht registriert? <a href="register.php">Registrieren</a>
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
		$("#loginForm").submit(function() {
			return false;
		});
		
		$("#login_user").click(function() {
			login();
		});
	}
	
	function login() {
		//$('#result').addClass('loading');
		var username_str = document.getElementById("username").value;
		var password_str = document.getElementById("password").value;
		$('#result').empty();
		$('#result').removeClass("error");
		$('#result').removeClass("success");
		
		$.post("scripts/user/secure_login.php", {username: username_str, password: password_str}, function(data){
			if(data.length > 0) {
				var obj = jQuery.parseJSON(JSON.stringify(data));
				if(obj[0].code == 1) {
					console.log("Login erfolgreich");
				
					
					
					var html_str = "<p>" + obj[0].message + "</p><p>Weiterleitung zum Dashboard...</p>";
					
					$('#result').html(html_str);
					$('#result').addClass("success");
					$('#login_btn').hide();
					setTimeout(function(){ window.location.replace("https://amu.tkg.ovh/dashboard.php"); }, 1000);
				} else {
					console.log("Login nicht erfolgreich");
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
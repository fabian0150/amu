function login() {
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
				swal("Erfolgreich eingeloggt!", "Weiterleitung in 3 Sekunden...", "success");
				$('#login_btn').hide();
				setTimeout(function(){ window.location.replace("https://amu.tkg.ovh/dashboard.php"); }, 3000);
			} else {
				console.log("Login nicht erfolgreich");
				var html_str = "";
				var error_str = "";
				for(var i = 0; i < obj.length; i++) {
					html_str += "<p>" + obj[i].error + "</p>";
					error_str += obj[i].error + " \n ";
				}
				
	
				
				swal({
					title: "Fehler beim Einloggen!",
					icon: "error",
					content: {
						element: "span",
						attributes: { innerText: error_str }
					},
					confirmButtonText: 'Cancel',
					confirmButtonColor: "#DD6B55"
				});
				$('#result').html(html_str);
				$('#result').addClass("error");
			}
		}
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
				
				swal("Erfolgreich registriert!", "Weiterleitung in 3 Sekunden...", "success");
				setTimeout(function(){ window.location.replace("https://amu.tkg.ovh/index.php"); }, 3000);
			} else {
				console.log("Registrierung nicht erfolgreich");
				var html_str = "";
				var error_str = "";
				for(var i = 0; i < obj.length; i++) {
					html_str += "<p>" + obj[i].error + "</p>";
					error_str += obj[i].error + " \n ";
				}
				$('#result').html(html_str);
				$('#result').addClass("error");
				swal({
					title: "Fehler beim Registrieren!",
					icon: "error",
					content: {
						element: "span",
						attributes: { innerText: error_str }
					}
				});
			}
		}
	});
}

function logout() {
	$.ajax({url: "scripts/user/secure_logout.php"}).done(function( data ) {
			if(data.length > 0) {
				var obj = jQuery.parseJSON(JSON.stringify(data));
				console.log(obj);
				if(obj[0].code == 1) {
					swal("Ausgeloggt!", "Weiterleitung in 3 Sekunden...", "success");
					setTimeout(function(){ window.location.replace("https://amu.tkg.ovh/"); }, 3000);
					
				} else {
					swal("Hello world! ERROR");
				}
			}
	});
}

function getRandomColor() {
  var letters = '0123456789ABCDEF';
  var color = '#';
  for (var i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)];
  }
  return color;
}
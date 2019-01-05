function loadUserBands(php_user_id) {
	var user_id = php_user_id;
	var html_str = "";
	
	$('#user_bands').empty();
	$.ajax({url: "json/band/_getUserBands.php?id=" + user_id}).done(function( data ) {
		if(data.length > 0) {
			var obj = jQuery.parseJSON(JSON.stringify(data));
		
			if(obj[0].code == 1) {
			for(var i = 0; i < obj.length; i++) {
				//html_str += "<p>" + obj[i].appointment_date + " <br> " + obj[i].location_address + " <br> " + obj[i].location_address + "</p>";
			
				$.ajax({url: "json/band/_getBand.php?id=" + obj[i].band_id}).done(function( data_info ) {
				if(data_info.length > 0) {
				
					var obj_info = jQuery.parseJSON(JSON.stringify(data_info));
					
					if (obj_info[0].website_url == null) { obj_info[0].website_url = "Keine Website vorhanden"; }
					if (obj_info[0].leader_username == null) { obj_info[0].leader_username = "Keine Besitzer vorhanden"; }  					
					html_str += `
					<div class='col-sm'>
						<div class='card card-inline' style='width: 18rem;'>
							<div class='card-body'>
								
								<h5 class='card-title' style='height: 80px;'><img src='img/bands/${obj_info[0].logo_path}' class='rounded-circle' alt='${obj_info[0].name}' heigth='80px' width='80px'> ${obj_info[0].name}</h5>
								<div class='card-text'>
									<p><span class='span-bold'>Website: </span><a href='${obj_info[0].website_url}'>${obj_info[0].website_url}</a><br>
									<span class='span-bold'>Besitzer: </span>${obj_info[0].leader_username}</p>
								</div>`;
								
					if(php_user_id == obj_info[0].leader_id) {	
						html_str += `<a href='#' class='btn-blue btn-primary'>Verwalten</a>`;
					}			
					html_str += `			
								<a href='#' class='btn-blue btn-primary'>Termine</a>
								
								<a href='#' class='btn btn-primary' onClick='deleteUserFromBand(${php_user_id}, ${obj_info[0].ID});'>Verlassen</a>
							</div>
						</div>
					</div>
					`;
					
				}
				
				$('#user_bands').html(html_str);
				});
			}
			} else {
				$('#user_bands').html(`<div class='col-sm'>
										<div class='card card-inline' style='width: 18rem;'>
											<div class='card-body'>
												<h5 class='card-title' style='height: 80px;'>Keine Bands</h5>
												<div class='card-text'>
													<p>Du musst erst einer Band beitreten</p>
												</div>
											</div>
										</div>
									</div>`);
			}
		}
	});	
}

function loadBandsSelect() {
	$('#bands_select').empty();
	var html_str = "";
	$.ajax({url: "json/band/_getBands.php"}).done(function( data ) {
		if(data.length > 0) {
			var obj = jQuery.parseJSON(JSON.stringify(data));
		
			
			for(var i = 0; i < obj.length; i++) {
				//html_str += "<p>" + obj[i].appointment_date + " <br> " + obj[i].location_address + " <br> " + obj[i].location_address + "</p>";
			
				$('#bands_select').append($('<option>', {
					value: obj[i].ID,
					text: obj[i].name
				}));
			}
		}
	});	
}

function addUserToBand(php_user_id) {
	var var_user_id = php_user_id;
	var var_band_id = $("#bands_select").val();

	$.post("scripts/band/secure_addBandmember.php", {user_id: var_user_id, band_id: var_band_id}, function(data){
		if(data.length > 0) {
			var obj = jQuery.parseJSON(JSON.stringify(data));
			if(obj[0].code == 1) {
				console.log("erfolgreich");
				loadUserBands(var_user_id);
				swal("Band beigetreten!", "Der Band wurde erfolgreich beigetreten", "success");
				
			} else {
				console.log("nicht erfolgreich");
				var error_str = "";
				for(var i = 0; i < obj.length; i++) {
					error_str += obj[i].error + " \n ";
				}
			
				swal({
					title: "Fehler beim Beitreten!",
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

function deleteUserFromBand(php_user_id, php_band_id) {

	console.log("User: " + php_user_id + " Band: " + php_band_id);
	$.post("scripts/band/secure_deleteBandmember.php", {user_id: php_user_id, band_id: php_band_id}, function(data){
		if(data.length > 0) {
			var obj = jQuery.parseJSON(JSON.stringify(data));
			if(obj[0].code == 1) {
				console.log("erfolgreich");
				loadUserBands(php_user_id);
				swal("Band verlassen!", "Die Band wurde erfolgreich verlassen", "success");
				
			} else {
				console.log("nicht erfolgreich");
				var error_str = "";
				for(var i = 0; i < obj.length; i++) {
					error_str += obj[i].error + " \n ";
				}
			
				swal({
					title: "Fehler beim Verlassen!",
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

function loadAppointments(php_user_id, limit) {
	$('#next_appointments').empty();
	var user_id = php_user_id;

	$.ajax({url: "json/appointment/_getUserAppointments.php?id=" + user_id}).done(function( data ) {
			if(data.length > 0) {
				var obj = jQuery.parseJSON(JSON.stringify(data));
			
				var html_str = "";
				if(obj[0].code != 6) {
				
				for(var i = 0; i < obj.length; i++) {
					
													
					var now = new Date();
					var json_date = new Date(obj[i].appointment_date);
					var appointment_date = new Date(obj[i].appointment_date).toLocaleDateString();
					var appointment_time = new Date(obj[i].appointment_date).toLocaleTimeString();
					if(now > json_date) {
							
						html_str += `<tr class="old-appointment">
								  <th scope="row">${obj[i].band_name}</th>
								  <td>${obj[i].location_name} <br> ${obj[i].location_address}</td>
								  <td>${appointment_date} ${appointment_time}</td>
								
								</tr>`;

					} else {
						html_str += `<tr>
								  <th scope="row">${obj[i].band_name}</th>
								  <td>${obj[i].location_name} <br> ${obj[i].location_address}</td>
								  <td>${appointment_date} ${appointment_time}</td>
							
								</tr>`;

					}
								
				}
				} else {
						html_str += `<tr>
								  <th scope="row">Keine Termine</th>
								  <td></td>
								  <td></td>
								</tr>`;	
				}
				
				$('#next_appointments').html(html_str);
				
			}
	});
}

function loadBandAppointments(php_user_id, php_band_id, limit) {
	$('#next_appointments').empty();
	var user_id = php_user_id;

	$.ajax({url: "json/appointment/_getUserAppointments.php?id=" + user_id}).done(function( data ) {
			if(data.length > 0) {
				var obj = jQuery.parseJSON(JSON.stringify(data));
			
				var html_str = "";
				if(obj[0].code != 6) {
				
				for(var i = 0; i < obj.length; i++) {
					
													
					var now = new Date();
					var json_date = new Date(obj[i].appointment_date);
					if(obj[i].band_id == php_band_id) {
						
					if(now > json_date) {
						html_str += `<tr class="old-appointment">
								  <th scope="row">${obj[i].band_name}</th>
								  <td>${obj[i].location_name} <br> ${obj[i].location_address}</td>
								  <td>${obj[i].appointment_date}</td>
								  <td></td>
								</tr>`;

					} else {
						html_str += `<tr>
								  <th scope="row">${obj[i].band_name}</th>
								  <td>${obj[i].location_name} <br> ${obj[i].location_address}</td>
								  <td>${obj[i].appointment_date}</td>
								  <td></td>
								</tr>`;

					}
					}
								
				}
				} else {
						html_str += `<tr>
								  <th scope="row">Keine Termine</th>
								  <td></td>
								  <td></td>
								</tr>`;	
				}
				
				$('#next_appointments').html(html_str);
				
			}
	});
}

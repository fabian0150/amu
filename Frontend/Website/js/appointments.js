
function loadAppointments(php_user_id, limit) {
	var user_id = php_user_id;

	$.ajax({url: "json/appointment/_getUserAppointments.php?id=" + user_id}).done(function( data ) {
			if(data.length > 0) {
				var obj = jQuery.parseJSON(JSON.stringify(data));
			
				var html_str = "";
				for(var i = 0; i < obj.length; i++) {
					
													
					var now = new Date();
					var json_date = new Date(obj[i].appointment_date);
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
								  <td><a href='#' class='btn btn-primary' onClick="">Absagen</a></td>
								</tr>`;

					}
								
				}
				$('#next_appointments').html(html_str);
				
			}
	});
}

var calendar;
var date_arr;
var date_disabled_arr;
function loadAppointments(php_user_id) {
	var user_id = php_user_id;
	date_arr = [];
	date_disabled_arr = [];
	
	$.ajax({url: "json/appointment/_getUserAppointments.php?id=" + user_id + "&limit=5"}).done(function( data ) {
			if(data.length > 0) {
				var obj = jQuery.parseJSON(JSON.stringify(data));
			
				var html_str = "";
				for(var i = 0; i < obj.length; i++) {
					
					html_str += `<tr>
								  <th scope="row">${obj[i].location_name} <br> ${obj[i].location_address}</th>
								  <td>${obj[i].appointment_date}</td>
								  <td><a href='#' class='btn btn-primary' onClick="">Absagen</a></td>
								</tr>`;
								
					var now = new Date();
					var json_date = new Date(obj[i].appointment_date);
					if(now > json_date) {
						console.log(json_date);
						date_disabled_arr.push(json_date);
					} else {
					
						var tooltip = {
							date: json_date,
							text: obj[i].band_name,
						};
						
						var highlight = {
							start: json_date,
							end: json_date,
							backgroundColor: getRandomColor(),
							color: '#ffffff',
							legend: obj[i].location_name
						};
						
						date_arr.push(highlight);
					}
				}
				$('#next_appointments').html(html_str);
				calendar.highlight = date_arr;
				calendar.disabledDates = date_disabled_arr;
			}
	});
}


function initCalendar() {
	var now = new Date();
	
	calendar = new Datepickk({
		container: document.querySelector('#calendar'),
		inline:true,
		range: true,
		lang: 'de'
	});
	

	
	
}
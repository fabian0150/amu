var calendar;
var date_arr;
var date_disabled_arr;
function loadAppointments(php_user_id, limit) {
	var user_id = php_user_id;
	date_arr = [];
	date_disabled_arr = [];
	var json_url = "json/appointment/_getUserAppointments.php?id=" + user_id;
	if(limit > 0){
		json_url += "&limit=" + limit;
	}	
	
	$.ajax({url: json_url}).done(function( data ) {
			if(data.length > 0) {
				
				var obj = jQuery.parseJSON(JSON.stringify(data));
				if(obj[0].code != 6) {
				var html_str = "";
				for(var i = 0; i < obj.length; i++) {
					var now = new Date();
					var json_date = new Date(obj[i].appointment_date);
					var appointment_date = new Date(obj[i].appointment_date).toLocaleDateString();
					var appointment_time = new Date(obj[i].appointment_date).toLocaleTimeString();
					if(now < json_date) {
						html_str += `<tr>
								  <th scope="row">${obj[i].location_name} <br> ${obj[i].location_address}</th>
								  <td>${appointment_date} ${appointment_time}</td>
								
								</tr>`;
					}
								
					
					if(now > json_date) {
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
				} else {
					html_str += `<tr>
								  <th scope="row">Keine Termine</th>
								  <td></td>
								
								</tr>`;	
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
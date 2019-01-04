function loadAppointments() {
	var band_id = 1;
	$.ajax({url: "json/appointment/_getAppointments.php?band_id=" + band_id}).done(function( data ) {
			if(data.length > 0) {
				var obj = jQuery.parseJSON(JSON.stringify(data));
				console.log(obj);
				var html_str = "";
				for(var i = 0; i < obj.length; i++) {
					html_str += "<p>" + obj[i].appointment_date + " <br> " + obj[i].location_address + " <br> " + obj[i].location_address + "</p>";
				}
				$('#appointments').html(html_str);
			}
	});
}
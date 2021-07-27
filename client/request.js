// The object of sending requests to the server
let request = {
	"get": function(callback, data, url) {
		$.ajax({
			url: url,
			method: "GET",
			cache: false,
    		processData: false,
			data: data,
			success: function(data) {
				callback(data);
			},
			error: function(jqXHR) {
				callback(jqXHR.responseText);
			}
		});
	},
	"post": function(callback, data, url) {
		$.ajax({
			url: url,
			method: "POST",
			cache: false,
    		processData: false, 
			data: data, 
			success: function(data) {
				callback(data);
			},
			error: function(jqXHR) {
				callback(jqXHR.responseText);
			}
		});
	}
}
// Request object
app.request = {
	xhr: new XMLHttpRequest(),
	// Method of getting the desired page
	get_page: function(path) {
		app.request.xhr.open("GET", path, false);
		app.request.xhr.send();
		if(xhr_check(app.request.xhr.responseText))
			return app.request.xhr.responseText;
		else return false;
	},
	// Method for sending get requests
	get: function(callback, url, data=null) {
		let xhr = new XMLHttpRequest();
		xhr.open("GET", slash_check(url), true);
		xhr.send(data);
		xhr.onreadystatechange = function() {
			if(xhr.readyState != 4) return;
			callback(JSON.parse(xhr.responseText));
		}
	},
	// Method for sending post requests
	post: function(callback, url, data=null, type="application/json") {
		let xhr = new XMLHttpRequest();
		xhr.open("GET", slash_check(url), true);
		xhr.setRequestHeader("Content-Type", type);
		xhr.send(data);
		xhr.onreadystatechange = function() {
			if(xhr.readyState != 4) return;
			callback(JSON.parse(xhr.responseText));
		}

	}
}
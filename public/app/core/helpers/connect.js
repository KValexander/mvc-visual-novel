// Object with file connection methods
let connect = {
	xhr: new XMLHttpRequest(),
	// Connecting scripts
	script: function(array) {
		if(Array.isArray(array))
			array.forEach(path => connect.create(path, "script"))
		else connect.create(array, "script")
	},
	// Connecting styles
	style: function(array) {
		if(Array.isArray(array))
			array.forEach(path => connect.create(path, "link"))
		else connect.create(array, "link")
	},
	// Generating the connection code
	create: function(path, type) {
		if(!connect.check(path)) return;
		// Checking if a script exists
		if(document.getElementById(path)) return;
		element = document.createElement(type);
		if(type == "script") {
			element.type = "text/javascript";
			element.src = path;
		} else if(type == "link") {
			element.rel = "stylesheet";
			element.href = path;
		}
		element.id = path;
		document.querySelector("head").appendChild(element);
	},
	// Delete connection
	remove: function(path) {
		console.log(path);
		document.getElementById(path).remove();
	},
	// Checking for file existence
	check: function(path) {
		connect.xhr.open("GET", path, false);
		connect.xhr.send();
		return xhr_check(connect.xhr.responseText);
	}
}
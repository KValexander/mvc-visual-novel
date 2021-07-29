// The object of sending requests to the server
let request = {
	get: function(callback, data, url) {
		let xhr = new XMLHttpRequest();
		// Opening an asynchronous XMLHttpRequest request
		xhr.open("GET", url, true);
		// Asynchronous data loading
		xhr.onreadystatechange = function() {
			if (xhr.readyState != 4) return;
			if (xhr.status >= 200 && xhr.status < 300)
				callback(xhr.responseText);
			else callback(xhr.responseText);
		}
		// Headers
		xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
		// Sending data
		xhr.send(data);
	},
	post: function(callback, data, url, enctype) {
		let xhr = new XMLHttpRequest();
		// Opening an asynchronous XMLHttpRequest request
		xhr.open("POST", url, true);
		// Asynchronous data loading
		xhr.onreadystatechange = function() {
			if (xhr.readyState != 4) return;
			if (xhr.status >= 200 && xhr.status < 300)
				callback(xhr.responseText);
			else callback(xhr.responseText);
		}
		// Headers
		xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.setRequestHeader('X-CSRF-Token', '');
		// Sending data
		xhr.send(data);
	}
}
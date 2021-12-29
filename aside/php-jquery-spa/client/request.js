// The object of sending requests to the server
let request = {
	headers: function(xhr) {
		xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
		// 'Authorization' is not sent, so I will send an arbitrary header with a token
		xhr.setRequestHeader('Auth-Token', localStorage.getItem("token"));
	},
	get: function(callback, data, url) {
		let xhr = new XMLHttpRequest();
		// Opening an asynchronous XMLHttpRequest request
		xhr.open("GET", url, true);
		// Asynchronous data loading
		xhr.onreadystatechange = function() {
			if (xhr.readyState != 4) return;
			callback(xhr.responseText);
		}
		// Headers
		this.headers(xhr);
		// Sending data
		xhr.send(data);
	},
	post: function(callback, data, url, formData) {
		let xhr = new XMLHttpRequest();
		// Opening an asynchronous XMLHttpRequest request
		xhr.open("POST", url, true);
		// Asynchronous data loading
		xhr.onreadystatechange = function() {
			if (xhr.readyState != 4) return;
			callback(xhr.responseText);
		}
		// Headers
		this.headers(xhr);
		if (formData != true)
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.setRequestHeader('X-CSRF-Token', '');
		
		// Sending data
		xhr.send(data);
	}
}
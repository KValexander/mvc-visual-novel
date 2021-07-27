// Working with routes on the client side
let route = {
	path_to_file: "client/pages/",
	extension: "html",
	xhr: new XMLHttpRequest(),

	// Modifying Path Data and Extensions
	config: function(path, extension) {
		this.path_to_file = path;
		this.extension = extension;
	},

	// Checking the address bar
	check_pathname: function() {
		let pathname = location.pathname;
		if (/\/$/.test(pathname) && pathname.length > 1) pathname = pathname.replace(/.$/, "");
		if (pathname != "/") this.redirect(pathname.substr(1), false)
		else this.redirect("index", false);
	},

	// Redirect to the desired page
	redirect: function(page, state) {
		// Path and url formation
		let path = this.path_to_file + page + "." + this.extension;
		let url = "/" + page;
		// Checks
		if(page == "index") url = "/";
		// Url setting
		if(state == undefined) {
			url = url.replace(/\//g,(i => m => !i++ ? m : '-')(0));
			window.history.pushState(null, null, url); // history api
		}
		// Getting page
		this.get_page(path, url);
	},

	// Getting page
	get_page: function(path, url) {
		// XMLHttpRequest, the fastest
		this.xhr.open("GET", path, false);
		this.xhr.send();

		if (this.xhr.status == 200) {
			if (this.xhr.responseText.includes("<!DOCTYPE html>"))
				return route.redirect("404");
			$("#app").html(this.xhr.responseText);
		}
		else console.log(this.xhr);

		// Fetch request, the shortest
		// fetch(path).then(response => response.text())
		// .then(data => $("#app").html(data));
	},

	// Attach module
	attach_module: function(path, elem_id) {
		// XMLHttpRequest
		this.xhr.open("GET", path, false);
		this.xhr.send()

		if (this.xhr.status == 200) {
			if (this.xhr.responseText.includes("<!DOCTYPE html>"))
				return $("#" + element_id).html(`<h1>Ошибка 404</h1> <h3>Такого файла нет</h3>`);
			$("#" + elem_id).html(this.xhr.responseText);
		}
		else console.log(this.xhr);
	},
};

window.addEventListener("popstate", e => route.check_pathname())
// Working with routes on the client side
let route = {
	path_to_file: "client/pages/",
	extension: "html",
	url_id: -1,
	xhr: new XMLHttpRequest(),

	// Modifying Path Data and Extensions
	config: function(path, extension) {
		this.path_to_file = path;
		this.extension = extension;
	},

	// Checking the address bar
	check_pathname: function() {
		auth.auth_role();
		let pathname = location.pathname, id = -1, check;
		if (/\/$/.test(pathname) && pathname.length > 1) pathname = pathname.replace(/.$/, "");
		if (pathname != "/") {
			if (pathname.includes("-")) pathname = pathname.replace(/-/g, "/");
			check = pathname.split("/");
			if(check.length > 2 && !isNaN(check[check.length - 1])) {
				id = check[check.length - 1]; check.pop();
				pathname = check.join("/");
			} // console.log(id, pathname);
			this.redirect(pathname.substr(1), id, false);
		} else this.redirect("index", id, false);
	},

	// Redirect to the desired page
	redirect: function(page, id=-1, state=true) {
		// Path and url formation
		let path = "", url = "";
		path = this.path_to_file + page + "." + this.extension;
		if (id != -1) {
			url = "/" + page + "/" + id;
			this.url_id = id;
		} else url = "/" + page;

		// Checks
		if(page == "index") url = "/";

		// Url setting
		if(state) {
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
		} else console.log(this.xhr);

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
				return $("#" + elem_id).html(`<h1>Ошибка 404</h1> <h3>Такого файла нет</h3>`);
			$("#" + elem_id).html(this.xhr.responseText);
		} else console.log(this.xhr);
	},
};

window.addEventListener("popstate", e => route.check_pathname());
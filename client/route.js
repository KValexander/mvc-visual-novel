// Working with routes on the client side
let route = {
	path_to_file: "client/pages/",
	extension: "html",
	xhr: new XMLHttpRequest(),

	// Modifying Path Data and Extensions
	config: function(path, ext) {
		this.path_to_file = path;
		this.extension = ext;
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

		if (this.xhr.status == 200)
			$("#app").html(this.xhr.responseText);
		else console.log(this.xhr);

		// Fetch request, the shortest
		// fetch(path).then(response => response.text())
		// .then(data => $("#app").html(data));

		// Ajax request, just ajax request
		// $.ajax({
		// 	url: path, // path to file
		// 	success: function(data) {
		// 		$("#app").html(data);
		// 	},
		// 	error: function(jqXHR) {
		// 		console.log(jqXHR);
		// 	}
		// });

	},

	// Attach module
	attach_module: function(path, elem_id) {
		// XMLHttpRequest
		this.xhr.open("GET", path, false);
		this.xhr.send()

		if (this.xhr.status == 200)
			$("#" + elem_id).html(this.xhr.responseText);
		else console.log(this.xhr);
	},
};

window.addEventListener("popstate", e => route.check_pathname())
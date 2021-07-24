// Working with routes on the client side
let route = {
	path_to_file: "client/pages/",
	extension: "html",

	// Modifying Path Data and Extensions
	config: function(path, ext) {
		this.path_to_file = path;
		this.extension = ext;
	},

	// Redirect to the desired page
	redirect: function(page, id) {
		// Path and url formation
		let path = this.path_to_file + page + "." + this.extension;
		let url = "/" + page;
		// Checks
		if(id != undefined) url += "/" + id;
		if(page == "index") url = "/";
		// Getting page
		this.get_page(path, url);
	},

	// Getting page
	get_page: function(path, url) {
		// Ajax request
		$.ajax({
			url: path, // path to file
			// In case of success
			success: function(data) {
				$("#app").html(data);
			},
			// In case of failure
			error: function(jqXHR) {
				console.log(jqXHR);
			}
		});
	},

	// Attach module
	attach_module: function(path, elem_id) {
		// Ajax request
		$.ajax({
			url: path,
			success: function(data) {
				$("#" + elem_id).html(data);
			},
			error: function(jqXHR) {
				console.log(jqXHR);
			}
		});
	},
};
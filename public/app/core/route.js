// Displaying the current page if the page is loaded
app.route.current_url = () => {
	app.config.layout();
	app.route.search(location.pathname)
};

// Method for changing page url
app.route.change_url = (url) => window.history.pushState(null, null, url);

// Route controller call method
app.route.call = (params) => eval(`app.controllers.${params[0]}.${params[1]}()`);

// Search method for the desired route
app.route.search = (route) => {
	if(route == "") return;
	route = slash_check(route);
	app.route.change_url(route);
	if(params = app.route.processing(route)) {
		app.route.call(params);
	} else app.route.not_found();
}

// Route processing
app.route.processing = (route) => {
	app.route.var = {};
	
	// Simple coincidence
	if(result = app.route.routes[route])
		return result.split("@");

	// Checking for the existence of variables in a route
	let val_route, val_r, pattern;
	val_route = route.split("/");
	for(r in app.route.routes) {
		val_r = r.split("/");
		if(r.match("{.*?}") != null && val_route.length == val_r.length) {

			// Determining routes matching
			pattern = r.replace(/\{.*?\}/g, "(.*?)");
			if(r.match(pattern, route)) {

				// Retrieving values and keys of route variables
				for(let i = 0; i < val_route.length; i++)
					if(val_r[i].match("{.*?}"))
						app.route.var[val_r[i].replace(/\{|\}/g, "")] = val_route[i];

				// Returning parameters
				return app.route.routes[r].split("@");
			}
		}
	}

	return false;
}

// Displaying a message if there is no route
app.route.not_found = () => {
	app.template.get_template("error");
	app.template.set_value({
		"ERROR": "Error 404",
		"MESSAGE": "Page not found"
	});
	app.html.content.innerHTML = app.template.get_content();
}

window.addEventListener("popstate", e => app.route.current_url());
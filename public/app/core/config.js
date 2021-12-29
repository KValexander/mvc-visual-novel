// Configuration settings
// Name of the site
app.config.title = "Document";

// Path to the site logo
app.config.logo = "https://cdn.pixabay.com/photo/2018/03/27/15/05/logo-3266214_1280.png"; 

// Displaying the main site template
app.config.layout = function() {
	// Assigning a title to the page
	document.title = app.config.title;

	// Getting a header
	app.template.get_template("layout/header");
	app.template.set_value({
		"SRC": app.config.logo,
		"TITLE": app.config.title,
		"LINKS": app.config.menu.get()
	});
	header = app.template.get_content();

	// Getting a layout
	app.template.get_template("layout/layout");
	app.template.set_value("HEADER", header);

	// Main template output
	app.html.app.innerHTML = app.template.get_content();

	// Element containing content
	app.html.content = document.getElementById("content");
}

// Processing an item from a list into a link
app.config.menu.use = function(key, delimiter=null) {
	if(link = app.config.menu.list[key]) {
		link = `<a onclick="app.route.search('${link[1]}')">${link[0]}</a>`;
		if(delimiter != null) link += delimiter;
		return link;
	} return null;
}

// Issuing a menu
app.config.menu.get = function() {
	let html = "", count = 0;
	count = Object.keys(app.config.menu.list).length;
	for(key in app.config.menu.list) {
		html += app.config.menu.use(key);
		count--; if(count > 0) html += app.config.menu.delimiter;
	} return html;
}

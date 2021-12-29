// Template engine object
app.template = {
	values: {},
	html: "",
	count: 1,
	// Getting a template
	get_template: function(template=null, count=1) {
		if(template == null || template == "")
			return app.template.html = "<h1>Template not set</h1>";
		app.template.clear();

		if(response = app.request.get_page(`${root}templates/${template}.tpl`)) {
			app.template.html = ""; app.template.count = count;
			for(let i = 0; i < app.template.count; i++)
				app.template.html += response;
		} else app.template.html = "<h1>Template not found</h1>";
	},
	// Retrieving values for a template
	set_value: function(keys, val=null) {
		// If passed an object
		if(typeof keys === "object") {
			for(k in keys) {
				key = `{${k}}`;
				app.template.values[key] = keys[k];
			}
		// Otherwise the line
		} else {
			keys = `{${keys}}`;
			app.template.values[keys] = val;
		}
	},
	// Parsing a template
	parse_template: function() {
		if(app.template.count > 0) {
			for (let key in app.template.values)
				if (app.template.html.includes(key))
					app.template.html = app.template.html.replace(key, app.template.values[key]);
			app.template.count--;
		}
	},
	// Retrieving processed template data
	get_content: function() {
		app.template.parse_template();
		if (app.template.count <= 0) {
			app.template.html = app.template.html.replace(/\{.*?\}/g, '');
			return app.template.html;
		}
	},
	//Clearing template data
	clear: function() {
		app.template.html = "";
		app.template.count = 1;
		app.template.values = {};
	} 
}
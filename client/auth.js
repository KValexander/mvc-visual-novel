let auth = {
	// Authorization request
	authorization: function() {
		// Serialize
		let form = $("form").serialize();

		// Server request
		request.post((data) => {
			// Data parsing
			data = JSON.parse(data);
			$("input").removeClass("err");
			$("p.error").html("").removeClass("error_acc");

			// Handling a successful registration
			if(data.status == 200) {
				message.show(data.data.message);
				route.redirect("profile");
				route.attach_module("client/pages/modules/menu.html", "menu");
			// Handling errors
			} else this.errors(data);
		}, form, "api/login");
		return false;
	},

	// Registration request
	register: function() {
		// Serialize
		let form = $("form").serialize();

		// Server request
		request.post((data) => {
			// Data parsing
			data = JSON.parse(data);
			$("input").removeClass("err");
			$("p.error").html("").removeClass("error_acc");

			// Handling a successful registration
			if(data.status == 200) {
				message.show(data.data.message);
				route.redirect("index");
			// Handling errors
			} else this.errors(data);
		}, form, "api/register");
		return false;
	},

	// Authorization check
	check: function(callback) {
		request.get((data) => {
			data = JSON.parse(data);
			if (data.data == null) {
				message.show("Вы не авторизованы");
				return route.redirect("auth/login");
			} else callback(data.data)
		}, null, "api/role");
	},

	// Handling errors
	errors: function(data) {
		// Handling an authorization error
		if(data.status == 401) $("#login").html(data.data.message).addClass("error_acc");
		// Handling Validation Errors
		else if(data.status == 422) {
			for(key in data.data.errors) {
				let error = data.data.errors[key];
				$("#"+key).html(error).addClass("error_acc");
				$(`input[name=${key}]`).addClass("err");
			}
		}
	},

	// Logout
	logout: function() {
		request.get((data) => {
			route.attach_module("client/pages/modules/menu.html", "menu");
			route.redirect("index");
			message.show("Вы вышли");
		}, null, "api/logout");
	}
};
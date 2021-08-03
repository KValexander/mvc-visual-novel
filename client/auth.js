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
				// Saving a token
				localStorage.setItem("token", data.data.token);
				// Show message
				message.show(data.data.message);
				// Redirecting
				route.redirect("profile");
				route.attach_module("client/pages/modules/menu.html", "menu");
			// Handling errors
			} else this.errors(data);
		}, form, "api/login");

		// For form submit
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

		// For form submit
		return false;
	},

	// Authorization check
	check: function(callback, moder=false, admin=false) {
		request.post(data => {
			data = JSON.parse(data);
			if (data.data != true) {
				route.attach_module("client/pages/modules/menu.html", "menu");
				message.show("Вы не авторизованы");
				return route.redirect("auth/login");
			} else  {
				if (moder) {
					request.get(data => {
						data = JSON.parse(data);
						if (data.data.code == "moderator" || data.data.code == "admin") {
							if (admin) {
								if(data.data.code == "admin") callback();
								else {
									message.show("Доступ запрещён");
									return route.redirect("profile");
								}
							} else callback();
						} else {
							message.show("Доступ запрещён");
							return route.redirect("profile");
						}

					}, null, "api/role");
				} else callback();
			};
		}, null, "api/auth/check");
	},

	// Handling errors
	errors: function(data) {
		// Handling an authorization error
		if(data.status == 401) { 
			$("#login").html(data.data.message).addClass("error_acc");
			$(`input[name=login]`).addClass("err");
			$(`input[name=password]`).addClass("err");
		}
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
			localStorage.clear();
			route.attach_module("client/pages/modules/menu.html", "menu");
			route.redirect("index");
			message.show("Вы вышли");
		}, null, "api/logout");
	}
};
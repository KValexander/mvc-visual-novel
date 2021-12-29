// Object with requests to the server
let query = {
	// Displaying menu items
	menu: function() {
		// Role request
		request.get((data) => {
			// Data parsing
			data = JSON.parse(data);
			auth.role = (data.data == null) ? "guest" : data.data.code;
			// Menu output
			output.menu();
		}, null, "api/user/role");
	},

	// Retrieving user data
	get_user: function() {
		// Request to get data of an authorized user
		request.get((data) => {
			// Data parsing
			data = JSON.parse(data);
			localStorage.setItem("user_id", data.data.user_id);
			// Output personal inform
			output.personal_data_inform(data.data);
		}, null, "api/user");
	},

	// Updating profile photo
	update_user_avatar: function() {
		// Retrieving form data
		let formData = new FormData($("form#update_picture")[0]);
		request.post((data) => {
			data = JSON.parse(data);
			if(data.status == 200) {
				this.get_user();
				message.show(data.data.message);
			}
			else if(data.status == 422) {
				message.show(data.data.errors.picture);
			}
			$("input[name=picture]").val("");
		}, formData, "api/user/update/avatar", true);
	},

	// Retrieving users data
	get_users: function() {
		// Request to get users data
		request.get(data => {
			// Data parsing
			data = JSON.parse(data);
			// Output users data
			output.users_data(data.data);
		}, null, "api/moderation/users/get");
	},

	// Adding a novel
	add_novel: function(formData) {
		// Request to add a novel to the database
		request.post(data => {
			// Data parsing
			data = JSON.parse(data);
			// Clearing form fields
			$("p.error").html("").removeClass("error_acc");
			$("textarea").removeClass("err");
			$("select").removeClass("err");
			$("input").removeClass("err");
			// In case of success
			if (data.status == 200 || data.status == 204) {
				// Displaying a success message
				message.show(data.data);
				// Clearing form data
				$("form")[0].reset();
				// Clearing form data
				genres = []; screenshots = []; platforms = [];
				$(".genres").html(""); $(".screenshots").html(""); $(".platforms").html("");
			// In case of error
			} else if (data.status == 400) {
				message.show(data.data);
			// In case of error
			} else if(data.status == 422) {
				// Displaying validation errors
				for(key in data.data.errors) {
					let error = data.data.errors[key];
					$("#"+key).html(error).addClass("error_acc");
					$(`[name=${key}]`).addClass("err");
				}
			}
		}, formData, "api/add/novel", true);
	},

	// Retrieving all moderated novels
	get_all_moderated_novels: function() {
		// Request to get all moderated novels
		request.get(data => {
			// Data parsing
			data = JSON.parse(data);
			// Novels output
			output.search_novels(data.data, true, true);
		}, null, "api/moderation/novels/get");
	},

	// Receiving moderated user novels
	get_user_moderated_novels: function() {
		// Request to get moderated user novels
		request.get(data => {
			// Data parsing
			data = JSON.parse(data);
			// Novels output
			output.search_novels(data.data, true);
		}, null, "api/user/moderated_novels");
	},

	// Receiving moderated user novels
	get_user_approved_novels: function() {
		// Request to get approved user novels
		request.get(data => {
			// Data parsing
			data = JSON.parse(data);
			// Novels output
			output.search_novels(data.data, true);
		}, null, "api/user/approved_novels");
	},

	// Approve the novel
	approve_novel: function(id) {
		// Request for approval of the novel
		request.get(data => {
			// Data parsing
			data = JSON.parse(data);
			// In case of success
			if (data.status == 200) {
				message.show(data.data);
				this.get_user_moderated_novels();
			}
		}, null, "api/moderation/novel/"+id+"/approve");
	},

	// Retrieving all approdev novels
	get_novels: function() {
		// Request for all approved novels
		request.get(data => {
			data = JSON.parse(data);
			output.search_novels(data.data);
		}, null, "api/novels");
	},

	// Getting a novel
	get_novel: function() {
		// Request to receive data from the novel
		request.get(data => {
			// Data parsing
			data = JSON.parse(data);
			// In case of error
			if(data.data == null)
				return route.redirect('404');
			// Novel output
			output.novel_data_layout(data.data);
		}, null, "api/novel/"+route.url_id);
	},

	// Getting comments
	get_comments: function() {
		// Request to get comment data
		request.get(data => {
			// Data parsing
			data = JSON.parse(data);
			// Output comments data
			output.novel_data_comments(data.data.comments);
		}, null, `api/novel/${route.url_id}/comments`);
	},

	// Adding a comment to the novel
	add_comment: function() {
		// Serializing Form Data
		let form = $("form#add_comment").serialize();
		// Request to add a comment
		request.post(data => {
			// Data parsing
			data = JSON.parse(data);
			// Clear field
			$("#comment").html("").removeClass("error_acc");
			$(`[name=comment]`).removeClass("err");
			// Success
			if(data.status == 200) {
				// Clearing form data
				$("form#add_comment")[0].reset();
				// Displaying a Success Message
				message.show(data.data);
				// Updating comments
				query.get_comments();
			// Validation error
			} else if(data.status == 422) {
				// Error output
				$("#comment").html(data.data).addClass("error_acc");
				$(`[name=comment]`).addClass("err");
			}
		}, form, `api/novel/${route.url_id}/comment/add`);
		// To cancel submitting form data
		return false;
	},

	// Deleting a comment
	delete_comment: function(id) {
		request.get(data => {
			// Data parsing
			data = JSON.parse(data);
			// In case of success
			if(data.status == 200) {
				// Displaying a success message
				message.show(data.data);
				// Updating comments
				query.get_comments();
			// In case of error
			} else if (data.status == 403) message.show(data.data);

			console.log(data);
		}, null, `/api/novel/${route.url_id}/comment/delete?id=${id}`)
	},

	// Getting and output directories
	get_directories: function(elem) {
		// Request to get directories
		request.get(data => {
			// Data parsing
			data = JSON.parse(data);
			// Data concatenation
			out = `<option selected disabled>Жанры</option>`;
			if (data.data.genres.length != 0)
				data.data.genres.forEach(genre => out += `<option value="${genre.genre_id}">${genre.genre}</option>`);
			// Data output
			$(elem).html(out);
		}, null, "api/directory/get");
	},

	// Adding a genre
	add_genre: function() {
		// Serializing form data
		let form = $("#add_genre").serialize();
		// Request to add a genre to the database
		request.get(data => {
			// Displaying updated data
			this.get_directories("select[name=genre_id]");
			// Data parsing
			data = JSON.parse(data);
			// Validation error
			if (data.status == 422) {
				// Error message output
				message.show(data.data.errors.genre);
				$("#add_genre input[name=genre]").addClass("err");
			// In case of success
			} else {
				// Success message output
				message.show(data.data.message);
				$("#add_genre input[name=genre]").val("").removeClass("err");
			}
		}, null, "api/directory/genre/add?"+form);
		// To cancel submitting form data
		return false;
	},

	// Deleting a genre
	delete_genre: function() {
		// Serializing form data
		let form = $("#delete_genre").serialize();
		// Request to delete genre from database
		request.get(data => {
			// Displaying updated data
			this.get_directories("select[name=genre_id]");
			// Data parsing
			data = JSON.parse(data);
			// Success message output
			message.show(data.data.message);
		}, null, "api/directory/genre/delete?"+form);
		// To cancel submitting form data
		return false;
	},

}
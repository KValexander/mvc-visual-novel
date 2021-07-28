// Small notification display object
let message = {
	// Displaying a message
	show: function(message) {
		let self = this;
		$("#message").html(`<p>${message}</p><div></div>`);
		$("#message").fadeIn(200, function() {
			$("#message div").animate({width: "0px"}, 5000);
			$(this).click(() => self.hide());
		});
		setTimeout(this.hide, 5000);
	},
	// Hiding a message
	hide: function() {
		$("#message").fadeOut(200, () => $(this).html(""));
	}
};
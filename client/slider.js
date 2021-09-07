// Slider
let slider = {
	timer: "",
	// Slider start
	start: function() {
		this.flip();
		this.events();
	},
	// Flip slides
	flip: function(slide=0) {
		clearTimeout(this.timer);
		if(!$("div").is(".slider")) return;

		if (slide >= $(".slider .slides img").length) slide = 0;
		
		$(".slider .flip_wrap").animate({
			left: `-${$(".slider .slides img.slide_"+slide).position().left}`
		}, 300);

		$(".slider .images .outline").animate({
			left: `${$(".slider .images img.slide_"+slide).position().left - 2}`
		}, 300);

		this.timer = setTimeout(() => { slider.flip(++slide); }, 5000);

	},
	// Slider events
	events: function() {
		$(".slider .images img").click(function() {
			slider.flip($(this).index());
		});
	},
};
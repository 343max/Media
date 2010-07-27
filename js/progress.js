(function() {
	var animateProgressBars = function() {
		var current = 0;
		var speed = 2;

		window.setInterval(function() {
			current += speed;
			if(current >= 100) {
				current = 100;
				speed = -speed;
			} else if(current <= 0) {
				current = 0;
				speed = -speed;
			}

			$('p.progress span').css('width', current + '%');
		}, 500);
	};

	$('ul#nav li.convert').live('click', animateProgressBars);
})();
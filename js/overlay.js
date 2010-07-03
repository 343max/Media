function displayAsOverlay(element) {
	var background = $('<div>')
		.css('width', window.innerWidth - 1 + 'px')
		.css('height', window.innerHeight - 1 + 'px')
		.addClass('overlay');

	background.click(function() {
		background.remove();
	})

	background.append(element);
	$('body').append(background);
}
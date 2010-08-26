$().ready(function() {
	scrollable = document.querySelector('#nav');
	var scroller = new TouchScroll(scrollable, { elastic: true });

	$('.panel').each(function(){
		this.scroller = new TouchScroll(this, { elastic: true });
	});
});

$('ul#nav li').live('click', function() {
	$('ul#nav li').removeClass('active');
	$(this).addClass('active');

	$('div.panel').hide();
	var panel = $('div#' + $(this).attr('panel'));
	panel.show();
	window.setTimeout(function() {
		panel[0].scroller.setupScroller();
	}, 50);
	//var scroller = new TouchScroll(panel[0], { elastic: true });
});

$().ready(function() {
	$('ul#nav li:first-child').click();
	//$('ul#nav li.progress').click();
});
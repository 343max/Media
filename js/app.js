$().ready(function() {
	scrollable = document.querySelector('#nav');
	var scroller = new TouchScroll(scrollable, { elastic: true });

	$('.panel').each(function(){
		var scroller = new TouchScroll(this, { elastic: true });		
	});
});

$('ul#nav li').live('click', function() {
	$('ul#nav li').removeClass('active');
	$(this).addClass('active');

	$('div.panel').hide();
	$('div#' + $(this).attr('panel')).show();
});

$().ready(function() {
	$('ul#nav li:first-child').click();
	//$('ul#nav li.progress').click();
});
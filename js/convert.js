(function() {

	var convertMedia = function(o, url) {
		var li = o.parent();

		$.getJSON('ajax/processfile.php', {url: url}, function(data) {
			console.dir(data);
		});

		o.addClass('convertMedia');

		var srcPosition = o.offset();

		o.css('top', srcPosition.top + 'px');
		o.css('left', srcPosition.left + 'px');

		o.css('max-width', li.width() + 'px');
		o.css('height', li.height() + 'px');

		$('body').append(o);

		window.setTimeout(function() {
			o.addClass('animate');

			var targetPosition = $('ul#nav li.progress').offset();

			o.css('top', targetPosition.top + 'px');
			o.css('left', '1px');

			window.setTimeout(function() {
				o.remove();
			}, 5000);
		}, 50);
	}

	var showConvertableFiles = function() {
		$('ul#nav li.convert').die('click', showConvertableFiles);

		$.getJSON('ajax/externalfiles.php', function(data) {
			data = data.sort(function(a, b) {
				return b.timestamp - a.timestamp;
			});
			
			var ul = $('#convertList');

			$.each(data, function() {
				var a = $('<a>').addClass('convertable');
				a.text(unescape(this.url.replace(/.*\//g, '')));

				var url = this.url;

				var clickCount = 0;

				a.bind('click', function() {
					// TouchScroll sends click events twice, let's catch this

					if(clickCount != 0) return;
					clickCount++;

					convertMedia(a, url);
				});

				var li = $('<li>').append(a);

				ul.append(li);
			});
		});
	};

	$('ul#nav li.convert').live('click', showConvertableFiles);
})();
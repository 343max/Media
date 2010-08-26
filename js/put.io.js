(function() {

	var downloadVideo = function(o, url, name) {
		console.dir(url);
		$.getJSON('ajax/putio/download.php', {url: url, fileName: name}, function(data) {
			console.dir(data);
		});

		var c = o.clone();
		o.addClass('converting');

		var offset = o.offset();

		c.css('left', offset.left + 'px').css('top', offset.top + 'px').addClass('startFlying');

		$('body').append(c);
		
		window.setTimeout(function() {
			c.addClass('flying');
			
			var targetPosition = $('ul#nav li.progress').offset();

			c.css('top', (targetPosition.top - 40) + 'px');
			c.css('left', '1px');

			window.setTimeout(function() {
				c.remove();
			}, 5000);
		}, 50);
	}

	var fileList = function() {
		$('ul#nav li.putio').die('click', fileList);

		$.getJSON('ajax/putio/files/list.php', function(data) {
			console.dir(data);

			files = data.response.results.sort(function(a, b) {
				return parseInt(b.id) - parseInt(a.id);
			});

			var div = $('div#putio');

			$.each(files, function() {
				if(!this.content_type.match(/^video\//)) {
					continue;
				}

				//console.dir(this);
				var a = $('<a>').addClass('convertable');
				a.addClass('putioMovieFile').css('background-image', 'url(' + this.screenshot_url + ')');
				a.append($('<span>').text(this.name.replace(/[\._]/g, ' ')));

				var url = this.download_url;
				var name = this.name;

				var clickCount = 0;

				a.bind('click', function() {
					// TouchScroll sends click events twice, let's catch this

					if(clickCount != 0) return;
					clickCount++;

					downloadVideo(a, url, name);
				});

				div.append(a);
			});


			/*data = data.sort(function(a, b) {
				return b.timestamp - a.timestamp;
			});

			var ul = $('div#fileserver ul.convertList');

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
			});*/
		});
	};

	$('ul#nav li.putio').live('click', fileList);
})();
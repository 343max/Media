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

	var fileList = function(parentId) {
		$('ul#nav li.putio').die('click', fileList);

		var params = {};
		parentId = parseInt(parentId);
		if(parentId) params.parent_id = parentId;

		$.getJSON('ajax/putio/files/list.php', params, function(data) {
			files = data.response.results.sort(function(a, b) {
				return parseInt(b.id) - parseInt(a.id);
			});

			var div = $('div#putio');

			$.each(files, function() {
				if(this.type == 'folder') {
					fileList(this.id);
				}
				if(this.type != 'movie') {
					return;
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

		});
	};

	$('ul#nav li.putio').live('click', fileList);
})();


(function() {
	var loadTvShows = function() {
		$('ul#nav li.tvshows').die('click', loadTvShows);

		$.getJSON('ajax/tvshows.php', function(data) {
			$tvShowList = $('#tvShowList');

			$.each(data, function() {
				var show = this;

				var li = $('<li>').append($('<span>').addClass('label').text(show.title));
				if(show.banner) li.addClass('hasBanner').css('background-image', 'url(' + show.banner + ')');

				var lastClick = 0;

				li.bind('click', function() {
					// TouchScroll sends click events twice, let's catch this

					var now = (new Date()) - 0;
					if(now - 10 <= lastClick) return;
					lastClick = now;

					var $this = $(this);

					$this.addClass('active');

					var div = $('<div>').addClass('tvshow');

					div.append($('<h1>').text(show.title));

					var table = $('<table>').addClass('episodes');

					div.append(table);

					var episodeRows = {};
					var episodeIds = [];

					$.each(show.episodes, function() {
						var episode = this;

						var row = $('<tr>').append(
								$('<td>').addClass('id').text(episode.orderBy),
								$('<td>').addClass('title').text(episode.provisionalTitle),
								$('<td>').addClass('airDate')
							).click(function() {
								document.location.href = episode.url;
							});

						episodeRows[episode.id] = row;
						episodeIds.push(episode.id);

						table.append(row);
					});

					$.getJSON('ajax/episodeguide.php', {showname: show.title, episodes: episodeIds.join(';')}, function(episodeGuides) {
						$.each(episodeRows, function(episodeId, tableRow) {
							if(!episodeGuides[episodeId]) {
								return;
							}

							var episodeInfo = episodeGuides[episodeId];

							tableRow.find('.title').text(episodeInfo.title);
							tableRow.find('.airDate').text(episodeInfo.airDate.day + '.' + episodeInfo.airDate.month + '.' + episodeInfo.airDate.year);
						});
					})

					displayAsOverlay(div);
				});

				$tvShowList.append(li);
			});
		});
	};

	$('ul#nav li.tvshows').live('click', loadTvShows);
})();
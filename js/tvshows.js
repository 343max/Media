/**
 * Created by IntelliJ IDEA.
 * User: max
 * Date: 29.06.2010
 * Time: 23:39:53
 * To change this template use File | Settings | File Templates.
 */

$().ready(function() {
	$.getJSON('ajax/tvshows.php', function(data) {
		$tvShowList = $('#tvShowList');

		$.each(data, function() {
			var show = this;

			var li = $('<li>').append($('<span>').addClass('label').text(this.title));
			if(this.banner) li.addClass('hasBanner').css('background-image', 'url(' + this.banner + ')');

			var lastClick = 0;

			li.bind('click', function() {
				// TouchScroll sends click events twice, let's catch this

				var now = (new Date()) - 0;
				if(now - 10 <= lastClick) return;
				lastClick = now;

				var $this = $(this);

				$this.addClass('active');

				var table = $('<table>').addClass('episodes');

				var episodeRows = {};

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

					table.append(row);
				});

				$.getJSON('ajax/episodeguide.php', {showname: show.title}, function(episodeGuides) {
					$.each(episodeRows, function(episodeId, tableRow) {
						if(!episodeGuides[episodeId]) {
							return;
						}

						var episodeInfo = episodeGuides[episodeId];

						tableRow.find('.title').text(episodeInfo.title);
						tableRow.find('.airDate').text(episodeInfo.airDate.day + '.' + episodeInfo.airDate.month + '.' + episodeInfo.airDate.year);
					});
				})

				displayAsOverlay(table);
			});

			$tvShowList.append(li);
		});
	});
});
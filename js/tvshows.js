/**
 * Created by IntelliJ IDEA.
 * User: max
 * Date: 29.06.2010
 * Time: 23:39:53
 * To change this template use File | Settings | File Templates.
 */

$().ready(function() {
	$.getJSON('ajax/tvshows.php', function(data) {
		//console.dir(data);

		$tvShowList = $('#tvShowList');

		$.each(data, function() {
			var show = this;

			var li = $('<li>').append($('<span>').addClass('label').text(this.title));
			if(this.banner) li.addClass('hasBanner').css('background-image', 'url(' + this.banner + ')');

			li.click(function() {
				var $this = $(this);

				console.log('xxx');

				$this.addClass('active');

				var table = $('<table>').addClass('episodes');

				$.each(show.episodes, function() {
					//console.dir(this);

					var episode = this;

					table.append(
						$('<tr>').append(
							$('<td>').addClass('episodeId').text(episode.orderBy),
							$('<td>').addClass('episodeTitle').text(episode.title)
						).click(function() {
							document.location.href = episode.url;
						})
					);

				});

				$('body').append(table);

				return true;
			});

			$tvShowList.append(li);
		});
	});
});
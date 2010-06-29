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

			var li = $('<li>').append($('<span>').addClass('label').text(this.title));
			if(this.banner) li.addClass('hasBanner').css('background-image', 'url(' + this.banner + ')');

			var div = $('<div>').addClass('showDetails').text(this.title);
			if(this.banner) div.addClass('hasBanner').css('background-image', 'url(' + this.banner + ')');

			var table = $('<table>').addClass('episodes');

			$.each(this.episodes, function() {
				//console.dir(this);

				var episode = this;

				table.append(
					$('<tr>').append(
						$('<td>').addClass('episodeId').text(this.orderBy),
						$('<td>').addClass('episodeTitle').text(this.title)
					).click(function() {
						document.location.href = episode.url;
						/*
						var video = $('<video>');
						video.attr('src', episode.url);
						video.attr('autoplay', 'autoplay');
						video.attr('width', '640');
						video.attr('height', '480');
						$('body').append(
								$('<div>').addClass('videoplayer').append(video)			
						);
						*/
					})
				);
			});

			li.click(function() {
				var $this = $(this);

				$this.addClass('active');
				window.setTimeout(function() {
					$('*').live('click', function() {
						if(!jQuery.contains($this[0], this)) {
							console.log('meeep!');
							$('*').die('click');
							li.removeClass('active');
							return false;
						}
					});
				}, 50);
			});

			li.append(table);

			$tvShowList.append(li);
		});
	});
});
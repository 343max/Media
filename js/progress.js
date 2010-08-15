(function() {

	var formatTime = function(seconds) {
		if(seconds > 3900) {
			return Math.round(seconds / 3600) + ' hours';
		}

		if(seconds > 60) {
			var s = new String(seconds % 60);
			if(s.length < 2) s = '0' + s;
			return Math.floor(seconds / 60) + ':' + (s) +  ' minutes';
		}

		return seconds + ' seconds';
	}

	var inArray = function(item, array) {
		for(i=0; i < array.length; i++) {
			if (item == array[p]) return true;
		}
		return false;
	}

	//$('ul#nav li.convert').live('click', animateProgressBars);

	$().ready(function() {
		window.setInterval(function() {
			if($('li.progress.active').length == 1) {
				var activeProcesses = [];
				$.getJSON('ajax/processes.php', function(data) {
					$.each(data, function() {
						//console.dir(this);

						var li = $('#process_' + this.id);
						activeProcesses.push(this.id);

						if(li.length == 0) {
							li = $('<li>').attr('id', 'process_' + this.id);
							li.append(
								$('<p>').addClass('label'),
								$('<p>').addClass('progress').append($('<span>')),
								$('<p>').addClass('status')
							);
							$('ul.progress').prepend(li);
						};

						li.find('.progress span').css('width', this.percentDone + '%');
						var statusMessage = '';

						if(this.processName == 'convert') {
							statusMessage = 'Converting video';
							if(this.timeLeft >= 0) statusMessage += ' (' + this.currentSpeed + ' frames/second) – ' + formatTime(this.timeLeft) + ' remaining';
						} else if(this.processName == 'download') {
							statusMessage = 'Downloadings…';
							if(this.timeLeft >= 0) statusMessage = 'Downloaded ' + this.dataElapsed + ' of ' + this.totalData + ' (' + this.currentSpeed + '/second) – ' + formatTime(this.timeLeft) + ' remaining';
						}

						li.find('.label').text(this.processedObject);
						li.find('.status').text(statusMessage);
					});

					$('ul.progress>li').each(function() {
						var $this = $(this);

						if(!inArray($this.attr('id').replace(/[^_]+_/, ''), activeProcesses)) {
							$this.remove();
						}
					});
				});
			}
		}, 1000);
	});
})();
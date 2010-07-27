(function() {
	var showConvertableFiles = function() {
		$('ul#nav li.convert').die('click', showConvertableFiles);

		$.getJSON('ajax/externalfiles.php', function(data) {
			data = data.sort(function(a, b) {
				return b.timestamp - a.timestamp;
			});
			console.dir(data);
		});
	};

	$('ul#nav li.convert').live('click', showConvertableFiles);
})();
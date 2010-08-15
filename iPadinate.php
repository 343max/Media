#!/usr/bin/php5
<?php
/**
 * Created by IntelliJ IDEA.
 * User: max
 * Date: 17.05.2010
 * Time: 23:12:41
 */

require_once(dirname(__FILE__) . '/config.inc.php');

if(count($_SERVER['argv']) < 2) die($_SERVER['argv'][0] . ' movieFile');

for($i = 1; $i < count($_SERVER['argv']); $i++) {
	$fileName = $_SERVER['argv'][$i];

	if(!preg_match("/^\\//", $fileName)) {
		$fileName = realpath(getcwd() . '/' . $fileName);
	}

	$mp4name = preg_replace("/\\.[^\\.]+\$/", ".m4v", basename($fileName));

	if($tvShow = TvShowLib::getShowForFilename($fileName)) {
		$growlMessage = 'A new episode of "' . $tvShow->getTitle() . '" is ready for your viewing pleasure. Happy watchnig!';
		$mediadir = $tvShow->getPath() . '/';
	} else {
		$growlMessage = 'Moved "' . $mp4name . '" to your media folder. Happy watching!';
		$mediadir = mediadir;
	}

	if(!file_exists($fileName)) {
		echo "File not found: $fileName\n";
		continue;
	}

	# -e x264
	$cmd = 'HandBrakeCLI --optimize --cpu 8 -X 854 -Y 480 -i "' . $fileName . '" -o "' . tmpdir . $mp4name . '"';

	passthru($cmd);

	rename(tmpdir . $mp4name, $mediadir . $mp4name);

	$notification = new ProwlNotification(prowlUser, prowlPassword);
	$notification->send(sitename, 'Got one!', $growlMessage);
}

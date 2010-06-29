<?php

require_once('../config.inc.php');

$d = dir(tvshowsdir);

$tvShows = array();

function tvShowSort($a, $b) {
	return strcmp($a->title, $b->title);
}

function tvShowEpisodeSort($a, $b) {
	return strcmp($a->orderBy, $b->orderBy);
}

while (false !== ($entry = $d->read())) {
	$tvShowDir = tvshowsdir . $entry;
	$tvShowUrl = tvshowsurl . rawurlencode($entry);

	if(preg_match("/^\\./", $entry)) continue;
	if(!is_dir(tvshowsdir . $entry)) continue;
	$tvShow = (object)null;

	$tvShow->title = $entry;

	if(file_exists($tvShowDir . '/banner.jpg')) $tvShow->banner = $tvShowUrl . '/banner.jpg';
	if(file_exists($tvShowDir . '/poster.jpg')) $tvShow->poster = $tvShowUrl . '/poster.jpg';

	$subDir = dir($tvShowDir);

	$episodes = array();

	while (false !== ($entry = $subDir->read())) {
		if(preg_match("/^\\./", $entry)) continue;
		if(!preg_match("/\\.mp4\$/", $entry)) continue;

		$episode = (object)null;

		$episode->url = $tvShowUrl . '/' . rawurlencode($entry);
		$episode->title = $entry;
		$episode->orderBy = $entry;

		if(preg_match('/S([0-9]{1,2})E([0-9]{1,2})/', $entry, $match)) {
			$episode->season = (int)$match[1];
			$episode->episode = (int)$match[2];
			$episode->orderBy = $match[0];
		}

		if(preg_match('/(20[0-9]{2})\\.([0-9]{2})\\.([0-9]{2})/', $entry, $match)) {
			$episode->orderBy = $match[0];
			$episode->year = (int)$match[1];
			$episode->month = (int)$match[2];
			$episode->day = (int)$match[3];
		}

		$episodes[] = $episode;
	}

	usort($episodes, 'tvShowEpisodeSort');

	$tvShow->episodes = $episodes;

	$tvShows[] = $tvShow;
}

usort($tvShows, 'tvShowSort');

echo json_encode($tvShows);

?>
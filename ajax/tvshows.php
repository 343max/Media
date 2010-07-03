<?php

require_once('../config.inc.php');

function tvShowSort($a, $b) {
	return strcmp($a->title, $b->title);
}

function tvShowEpisodeSort($a, $b) {
	return strcmp($a->orderBy, $b->orderBy);
}

echo json_encode($tvShows);

?>
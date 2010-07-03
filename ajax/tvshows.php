<?php

require_once('../config.inc.php');

$tvShows = TvShowLib::getAllTvShows();

echo json_encode(TvShowLib::jsonPrepare($tvShows));

?>
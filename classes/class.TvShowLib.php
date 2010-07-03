<?php

class TvShowLib {

	static function tvShowSort($a, $b) {
		return strcmp($a->getTitle(), $b->getTitle());
	}

	static function getAllTvShows() {
		$showDir = dir(tvshowsdir);

		$tvShows = array();

		while (false !== ($tvShowFile = $showDir->read())) {
			if(preg_match("/^\\./", $tvShowFile)) continue;
			if(!is_dir(tvshowsdir . $tvShowFile)) continue;

			$tvShow = new TvShow($tvShowFile);

			$tvShows[] = $tvShow;
		}

		usort($tvShows, 'TvShowLib::tvShowSort');

		return $tvShows;
	}

	/**
	 * @static
	 * @param $tvShows TvShow[]
	 * @return void
	 */
	static function jsonPrepare($tvShows) {
		$jsonPrepare = array();
		
		foreach($tvShows as $tvShow) {
			$jsonPrepare[] = $tvShow->jsonPrepare();
		}

		return $jsonPrepare;
	}
}
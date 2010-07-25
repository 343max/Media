<?php

class TvShowLib {
	private static $tvShows = null;

	static function tvShowSort($a, $b) {
		return strcmp($a->getTitle(), $b->getTitle());
	}

	/**
	 * @static
	 * @return TvShow[]
	 */
	static function getAllTvShows() {
		if(is_array(self::$tvShows)) {
			return self::$tvShows;
		}

		$showDir = dir(tvshowsdir);

		self::$tvShows = array();

		while (false !== ($tvShowFile = $showDir->read())) {
			if(preg_match("/^\\./", $tvShowFile)) continue;
			if(!is_dir(tvshowsdir . $tvShowFile)) continue;

			$tvShow = new TvShow($tvShowFile);

			self::$tvShows[] = $tvShow;
		}

		usort(self::$tvShows, 'TvShowLib::tvShowSort');

		return self::$tvShows;
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

	static function getShowForFilename($fileName) {
		$tvShows = self::getAllTvShows();
		$cleanName = strtolower(preg_replace("/[^a-zA-Z0-9]/", "", basename($fileName)));


		foreach($tvShows as $tvShow) {
			if(strpos($cleanName, $tvShow->cleanName()) !== false) {
				return $tvShow;
			}
		}
	}
}
<?php

class TvShowLib {

	static function getAllTvShows() {
		$showDir = dir(tvshowsdir);

		$tvShows = array();

		while (false !== ($tvShowFile = $showDir->read())) {
			if(preg_match("/^\\./", $tvShowFile)) continue;
			if(!is_dir(tvshowsdir . $tvShowFile)) continue;

			$tvShow = new TvShow($tvShowFile);


			usort($episodes, 'tvShowEpisodeSort');

			$tvShow->episodes = $episodes;

			$tvShows[] = $tvShow;
		}

		usort($tvShows, 'tvShowSort');

	}
}
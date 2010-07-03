<?php
/**
 * Created by IntelliJ IDEA.
 * User: max
 * Date: 03.07.2010
 * Time: 14:03:39
 */

class TvShow {
	private $dirName = null;
	private $episodes = array();

	public function __construct($dirName) {
		$this->dirName = $dirName;
	}

	public function getTitle() {
		return $this->dirName;
	}

	public function getUrl() {
		return tvshowsurl . rawurlencode($this->dirName) . '/';
	}

	public function getPath() {
		return tvshowsdir . $this->dirName . '/';
	}

	private function getFileUrlIfExists($fileName) {
		if(!file_exists($this->getPath() . $fileName)) return null;
		return $this->getUrl() . $fileName;		
	}

	public function getBannerUrl() {
		return $this->getFileUrlIfExists('banner.jpg');
	}

	public function getPosterUrl() {
		return $this->getFileUrlIfExists('poster.jpg');
	}

	/**
	 * @return TvShowEpisode[]
	 */
	public function getEpisodes() {
		if(count($this->episodes) > 0) return $this->episodes;

		$showDir = dir($this->getPath());

		$this->episodes = array();

		while (false !== ($episodeFile = $showDir->read())) {
			if(preg_match("/^\\./", $episodeFile)) continue;
			if(!preg_match("/\\.(mp4|m4v)\$/", $episodeFile)) continue;

			$episode = new TvShowEpisode($episodeFile, $this->getPath(), $this->getUrl());
			$this->episodes[] = $episode;
		}

		return $this->episodes;
	}

	static function episodeSort($a, $b) {
		return strcmp($a->orderBy, $b->orderBy);
	}

	public function jsonPrepare() {
		$thisJson = (object)null;

		$thisJson->title = $this->getTitle();
		$thisJson->banner = $this->getBannerUrl();
		$thisJson->poster = $this->getPosterUrl();
		$thisJson->episodes = array();

		$episodes = $this->getEpisodes();

		foreach($episodes as $episode) {
			$thisJson->episodes[] = $episode->jsonPrepare();
		}

		usort($thisJson->episodes, 'TvShow::episodeSort');

		return $thisJson;
	}
}

?>
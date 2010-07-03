<?php
/**
 * Created by IntelliJ IDEA.
 * User: max
 * Date: 03.07.2010
 * Time: 14:03:39
 */

class TvShow {
	private $dirName = null;

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
		if(!file_exist($this->getPath() . $fileName)) return null;
		return $this->getUrl() . $fileName;		
	}

	public function getBannerUrl() {
		return $this->getFileUrlIfExists('banner.jpg');
	}

	public function getPosterUrl() {
		return $this->getFileUrlIfExists('poster.jpg');
	}

	public function getEpisodes() {
		$subDir = dir($this->getPath());

		$episodes = array();

		while (false !== ($episodeFile = $subDir->read())) {
			if(preg_match("/^\\./", $episodeFile)) continue;
			if(!preg_match("/\\.(mp4|m4v)\$/", $episodeFile)) continue;

			$episode = (object)null;

			$episode->url = $showUrl . '/' . rawurlencode($episodeFile);
			$episode->title = preg_replace('/\./', ' ', preg_replace('/\\.mp4$/', '', $episodeFile));
			$episode->orderBy = $episodeFile;

			// S01E01 format
			if(preg_match('/S([0-9]{1,2})E([0-9]{1,2})/', $episodeFile, $match)) {
				$episode->season = (int)$match[1];
				$episode->episode = (int)$match[2];
				$episode->orderBy = $match[0];
			}

			// 2010.06.30 format
			if(preg_match('/(20[0-9]{2})\\.([0-9]{2})\\.([0-9]{2})/', $episodeFile, $match)) {
				$episode->orderBy = $match[0];
				$episode->year = (int)$match[1];
				$episode->month = (int)$match[2];
				$episode->day = (int)$match[3];
			}

			//1of12 format
			if(preg_match('/([0-9]{1,2})of([0-9]{1,2})/', $episodeFile, $match)) {
				$episode->episode = (int)$match[1];
				$episode->orderBy = $match[0];
			}

			$episodes[] = $episode;
		}

	}

	public function toJson() {
		$thisJson = (object)null;

		$thisJson->title = $this->getTitle();
		$thisJson->banner = $this->getBannerUrl();
		$thisJson->poster = $this->getPosterUrl();
	}
}

?>
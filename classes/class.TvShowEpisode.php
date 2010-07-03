<?php

class TvShowEpisode {
	private $fileName;
	private $showPath;
	private $showUrl;

	function __construct($fileName, $showPath, $showUrl) {
		$this->fileName = $fileName;
		$this->showPath = $showPath;
		$this->showUrl = $showUrl;
	}

	function getUrl() {
		return $this->showUrl . rawurlencode($this->fileName);
	}

	function getFileName() {
		return $this->fileName;
	}

	function getProvisionalTitle() {
		return preg_replace('/\./', ' ', preg_replace('/\\.mp4$/', '', $this->getFileName()));
	}

	function jsonPrepare() {
		$thisJson = (object)null;

		$thisJson->url = $this->getUrl();
		$thisJson->provisionalTitle = $this->getProvisionalTitle();
		$thisJson->orderBy = $this->getFileName();

		// S01E01 format
		if(preg_match('/S([0-9]{1,2})E([0-9]{1,2})/', $this->getFileName(), $match)) {
			$thisJson->season = (int)$match[1];
			$thisJson->episode = (int)$match[2];
			$thisJson->orderBy = $match[0];
			$thisJson->id = (int)$match[1] . '-' . (int)$match[2];
		}

		// 2010.06.30 format
		if(preg_match('/(20[0-9]{2})\\.([0-9]{2})\\.([0-9]{2})/', $this->getFileName(), $match)) {
			$thisJson->id = $thisJson->orderBy = $match[0];
			$thisJson->year = (int)$match[1];
			$thisJson->month = (int)$match[2];
			$thisJson->day = (int)$match[3];
		}

		//1of12 format
		if(preg_match('/([0-9]{1,2})of([0-9]{1,2})/', $this->getFileName(), $match)) {
			$thisJson->episode = (int)$match[1];
			$thisJson->orderBy = $match[0];
			$thisJson->id = '1-' . (int)$match[1];
		}

		return $thisJson;

	}

}
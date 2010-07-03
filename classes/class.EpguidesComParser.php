<?php

class EpguidesComParser {
	var $showName;

	function __construct($showName) {
		$this->showName = $showName;
	}

	function getNormalizedName() {
		$normalizedName = strtolower($this->showName);
		$normalizedName = preg_replace('/[^a-z0-9]/', '', $normalizedName);
		$normalizedName = preg_replace('/^the/', '', $normalizedName);

		return $normalizedName;
	}

	function getShowInformationUrl() {
		return 'http://epguides.com/' . $this->getNormalizedName() . '/';
	}

	function getRawEpisodeInfo() {
		$html = file_get_contents($this->getShowInformationUrl());
		if(!$html) throw new Exception('Could not fetch Episode Info from server');
		$data = preg_replace('/<\\/pre>.*/s', '', preg_replace('/.*<pre>/s', '', $html));
		return $data;
	}

	function getEpisodeInfo() {
		if(!preg_match_all("/^([0-9]+)\\s+([0-9-]+)\\s*([0-9A-Za-z\\/]+).*href=[\'\"]([^\'\"]*)[^>]*>([^<]+)/m", $this->getRawEpisodeInfo(), $matches, PREG_SET_ORDER)) {
			throw new Exception('No Episode Information found');
		}

		#var_dump($matches);

		$episodes = array();

		foreach($matches as $match) {
			$episode = (object)null;

			$episode->number = $match[1];
			$episode->id = $match[2];
			$episode->airDate = $match[3];
			$episode->infoUrl = $match[4];
			$episode->title = $match[5];

			$episodes[$episode->id] = $episode;
		}

		return $episodes;
	}
}

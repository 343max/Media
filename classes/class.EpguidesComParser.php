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
		$data = WebCache::getUrl($this->getShowInformationUrl(), 24 * 3600);
		if(!$data) throw new Exception('Could not fetch Episode Info from server');

		$data = substr($data, strpos($data, '<pre>') + 5);
		$data = substr($data, 0, strpos($data, '</pre>'));

		return $data;
	}

	static function monthNum($monthName) {
		$monthNums = array(
			'Jan' => '01',
			'Feb' => '02',
			'Mar' => '03',
			'Apr' => '04',
			'Mai' => '05',
			'Jun' => '06',
			'Jul' => '07',
			'Aug' => '08',
			'Sep' => '09',
			'Oct' => '10',
			'Nov' => '11',
			'Dec' => '12'
		);

		return @$monthNums[$monthName];
	}

	private function getObjectFromRow($match) {
		$episode = (object)null;

		$text = $match[0];

		$episode->number = (int)trim(substr($text, 0, 7));
		$episode->productionNumber = trim(substr($text, 17, 10));

		$airDate = trim(substr($text, 27, 12));
		$year = (int)substr($airDate, 7,2);

		$episode->airDate = array(
			'raw' => $airDate,
			'day' => substr($airDate, 0, 2),
			'month' => self::monthNum(substr($airDate, 3,3)),
			'year' => ($year > 40 ? $year + 1900 : $year + 2000)
		);

		if(preg_match('/href=[\'\"]([^\'\"]*)[^>]*>([^<]+)/', substr($text, 39), $titleMatch)) {
			$episode->infoUrl = $titleMatch[1];
			$episode->title = $titleMatch[2];
		}

		if(!preg_match('/([0-9]+)-([0-9]+)/', trim(substr($text, 7, 10)), $idMatch)) {
			$id = trim(substr($text, 7, 10));
		} else {
			$id = (int)$idMatch[1] . '-' . (int)$idMatch[2];
		}

		$episode->id = $id;

		$episode->dateId = $episode->airDate['year'] . '.' . $episode->airDate['month'] . '.' . $episode->airDate['day'];

		return $episode;
	}

	function getEpisodeInfo($onlyEpisodes = null) {
		if(!preg_match_all("/^([0-9]+).*/m", $this->getRawEpisodeInfo(), $matches, PREG_SET_ORDER)) {
			throw new Exception('No Episode Information found');
		}

		$episodes = array();

		foreach($matches as $match) {
			$episode = $this->getObjectFromRow($match);

			if(!is_array($onlyEpisodes)) {
				$includeEpisode = true;
			} else {
				$includeEpisode = false;
				if(in_array($episode->id, $onlyEpisodes)) $includeEpisode = true;
				if(in_array($episode->dateId, $onlyEpisodes)) $includeEpisode = true;
			}

			if($includeEpisode) {
				$episodes[$episode->id] = $episode;
				$episodes[$episode->dateId] = $episode;
			}
		}

		return $episodes;
	}
}

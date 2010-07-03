<?php

class WebCache {



	static function getUrl($url, $maxCacheAge) {
		$cacheFilePath = cachepath . md5($url);

		if(file_exists($cacheFilePath)) {
			if(filemtime($cacheFilePath) > time() - $maxCacheAge) {
				return file_get_contents($cacheFilePath);
			}
		}

		$data = file_get_contents($url);

		file_put_contents($cacheFilePath, $data);

		return $data;
	}

}
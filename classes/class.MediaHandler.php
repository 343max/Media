<?php

class MediaHandler implements BackgroundWorker {
	private $fileUrl = null;

	public function __construct($fileUrl, $userName = null, $password = null) {
		if($userName != null) {
			$fileUrl = preg_replace("/https?:\\/\\//", "\\0" . $userName . ":" . $password . "@", $fileUrl);
		}

		$this->fileUrl = $fileUrl;
	}

	public function runThread() {
		$id = sha1($this->fileUrl);
		$downloader = new MediaDownloader($this->fileUrl, '', $id);
		$downloader->runThread();
		$downloader->synchronize();

		$converter = new VideoConverter($downloader->getDestinationPath(), '', $id);
		$converter->runThread();
		$converter->synchronize();

		$tmpPath = $converter->getDestinationPath();
		$fileName = basename($tmpPath);

		if($tvShow = TvShowLib::getShowForFilename($fileName)) {
			$growlMessage = 'A new episode of "' . $tvShow->getTitle() . '" is ready for your viewing pleasure. Happy watchnig!';
			$mediadir = $tvShow->getPath() . '/';
		} else {
			$growlMessage = 'Moved "' . $fileName . '" to your media folder. Happy watching!';
			$mediadir = mediadir;
		}

		rename($tmpPath, $mediadir . $fileName);

		$notification = new ProwlNotification(prowlUser, prowlPassword);
		$notification->send(sitename, 'Got one!', $growlMessage);
	}

	public function synchronize() {
		
	}

}
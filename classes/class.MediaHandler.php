<?php

class MediaHandler implements BackgroundWorker {
	private $fileUrl = null;
	private $fileName = null;
	private $userName = null;
	private $password = null;

	public function __construct($fileUrl, $fileName = null, $userName = null, $password = null) {
		$this->fileUrl = $fileUrl;
		$this->userName = $userName;
		$this->password = $password;
		$this->fileName = $fileName;
	}

	public function runThread() {
		$id = sha1($this->fileUrl);

		$downloader = new MediaDownloader($this->fileUrl, $this->fileName, $id, $this->userName, $this->password);
		$downloader->runThread();
		$downloader->synchronize();

		$converter = new VideoConverter($downloader->getDestinationPath(), '', $id);
		$converter->runThread();
		$converter->synchronize();

		$tmpPath = $converter->getDestinationPath();

		$tmpPath = '/tmp/media/Family.Guy.S08E21.PDTV.XviD-BiA.m4v';
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
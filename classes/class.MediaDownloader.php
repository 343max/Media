<?php

class MediaDownloader implements BackgroundWorker {
	private $fileUrl = '';
	private $fileName = '';

	private $id = null;

	public function __construct($fileUrl, $fileName = '', $id = '') {
		$this->fileUrl = $fileUrl;
		if($fileName == '') $fileName = basename($this->fileUrl);
		$this->fileName = $fileName;
		if($id == '') $id = sha1($fileUrl);
		$this->id = $id;
	}

	public function getDestinationPath() {
		return tmpdir . $this->fileName;
	}

	private function getCurlCommand() {
		return 'curl -o ' . $this->getDestinationPath() . ' ' . $this->fileUrl . ' 2>&1';
	}

	private function writeProgress($curlOutput) {
		$curlOutput = trim($curlOutput);

		if($curlOutput == '') return;

		$match = preg_split('/\s+/', $curlOutput);

		if(!$match) return;

		// do we have something numbery-percenty as the first column?
		if(!preg_match('/^[0-9]+$/', $match[0])) {
			return;
		}

		$progress = new ProcessStatus('download', $this->id, $this->fileName, $match[2]);

		$progress->setTotalData($match[1]);
		$progress->setDataElapsed($match[3]);
		$progress->setTotalTime(ProcessStatus::timeDelimitedByColonToSeconds($match[8]));
		$progress->setTimeElapsed(ProcessStatus::timeDelimitedByColonToSeconds($match[9]));
		$progress->setTimeLeft(ProcessStatus::timeDelimitedByColonToSeconds($match[10]));
		
		$progress->setCurrentSpeed($match[11]);

		$this->count++;

		$progress->toFile();
	}

	private function complete() {
		$progress = new ProcessStatus('download', $this->id, $this->fileName, 100);
		$progress->complete();
	}

	public function runThread() {
		//ignore_user_abort('1');
		set_time_limit(0);

		$handle = popen($this->getCurlCommand(), 'r');
		stream_set_blocking($handle, 0);

		while(!feof($handle)) {
			$data = fgets($handle);

			if(!$data) continue;
			$this->writeProgress($data);
		}

		fclose($handle);
		$this->complete();
	}

	public function synchronize() {
		
	}

}
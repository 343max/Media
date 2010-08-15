<?php

class VideoConverter implements BackgroundWorker {
	private $sourcePath = '';
	private $destinationPath = '';
	private $id = '';

	public function __construct($sourcePath, $destinationPath = '', $id = '') {
		$this->sourcePath = $sourcePath;

		if($destinationPath == '') $destinationPath = preg_replace("/\\.[^\\.]+\$/", ".m4v", $sourcePath);
		$this->destinationPath = $destinationPath;

		if($id == '') $id = sha1($sourcePath);
		$this->id = $id;
	}

	public function getDestinationPath() {
		return $this->destinationPath;
	}

	private function getCommand() {
		# -e x264
		return 'HandBrakeCLI --optimize --cpu 8 -X 854 -Y 480 -i "' . $this->sourcePath . '" -o "' . $this->destinationPath . '"';
	}

	private function writeProgress($processOutput) {
		$processOutput = trim($processOutput);
		if(!preg_match("/([0-9]+\\.[0-9]+)\\s?%([^0-9]+([0-9]+\\.[0-9]+) fps[^0-9]+avg ([0-9]+\\.[0-9]+) fps[^0-9]+([0-9]+h[0-9]+m[0-9]+s))?/", $processOutput, $match))
			return;

		$progress = new ProcessStatus('convert', $this->id, $this->sourcePath, (int)$match[1]);

		if($match[2]) {
			$progress->setCurrentSpeed($match[3]);
			$progress->setTimeLeft(ProcessStatus::timeDelimitedByHMSToSeconds($match[5]));
		}

		$progress->toFile();
	}

	private function complete() {
		$progress = new ProcessStatus('convert', $this->id, $this->sourcePath, 100);
		$progress->complete();
	}

	public function runThread() {
		ignore_user_abort('1');
		set_time_limit(0);

		$handle = popen($this->getCommand(), 'r');
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
<?php

class ProcessStatus {
	public $id = null;
	public $processedObject = null;
	public $percentDone = -1;
	public $processName = '';

	public $timeLeft = -1;
	public $totalTime = -1;
	public $timeElapsed = -1;

	public $currentSpeed = '';

	public $dataLeft = '';
	public $totalData = '';
	public $dataElapsed = '';

	public function __construct($processName, $id, $processedObject, $percentDone) {
		$this->processName = $processName;
		$this->id = $id;
		$this->processedObject = $processedObject;
		$this->percentDone = $percentDone;
	}

	public function setTimeLeft($timeLeft) {
		$this->timeLeft = $timeLeft;
	}

	public function setTotalTime($totalTime) {
		$this->totalTime = $totalTime;
	}

	public function setTimeElapsed($timeElapsed) {
		$this->timeElapsed = $timeElapsed;
	}

	public function setCurrentSpeed($currentSpeed) {
		$this->currentSpeed = $currentSpeed;
	}

	public function setDataLeft($dataLeft) {
		$this->dataLeft = $dataLeft;
	}

	public function setTotalData($totalData) {
		$this->totalData = $totalData;
	}

	public function setDataElapsed($dataElapsed) {
		$this->dataElapsed = $dataElapsed;
	}

	public function toFile() {
		file_put_contents($this->getFilepath(), $this->toJson());
	}

	public function complete() {
		if(file_exists($this->getFilepath())) unlink($this->getFilepath());
	}

	private function toJson() {
		return json_encode($this);
	}

	private function getFilepath() {
		return tmpdir . $this->id . '_process_status.json';
	}

	static function timeDelimitedByColonToSeconds($time) {
		$match = preg_split('/:/', $time);

		$l = count($match);

		$seconds = (int)$match[$l - 1];
		$seconds += (int)$match[$l - 2] * 60;
		$seconds += (int)$match[$l - 3] * 3600;

		return $seconds;
	}

	static function timeDelimitedByHMSToSeconds($time) {
		if(!preg_match("/([0-9]+)h([0-9]+)m([0-9]+)s/", $time, $match)) {
			return -1;
		}

		$seconds = (int)$match[1] * 3600 + (int)$match[2] * 60 + (int)$match[3];

		return $seconds;
	}
		
}
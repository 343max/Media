<?php

class RSS {
	var $title;
	var $link;
	var $description;
	var $language = "en-us";
	var $pubDate;
	var $items;
	var $tags;

	function RSS() {
		$this->items = array();
		$this->tags = array();
	}

	function addItem($item) {
		$this->items[] = $item;
	}

	function setPubDate($when) {
		if (strtotime($when) == false)
			$this->pubDate = date("D, d M Y H:i:s ", $when) . "GMT";
		else
			$this->pubDate = date("D, d M Y H:i:s ", strtotime($when)) . "GMT";
	}

	function getPubDate() {
		if (empty($this->pubDate))
			return date("D, d M Y H:i:s ") . "GMT";
		else
			return $this->pubDate;
	}

	function addTag($tag, $value) {
		$this->tags[$tag] = $value;
	}

	function out() {
		$out = $this->header();
		$out .= "<channel>\n";
		$out .= "<title>" . $this->title . "</title>\n";
		$out .= "<link>" . $this->link . "</link>\n";
		$out .= "<description>" . $this->description . "</description>\n";
		$out .= "<language>" . $this->language . "</language>\n";
		$out .= "<pubDate>" . $this->getPubDate() . "</pubDate>\n";

		foreach ($this->tags as $key => $val) $out .= "<$key>$val</$key>\n";
		foreach ($this->items as $item) $out .= $item->out();

		$out .= "</channel>\n";

		$out .= $this->footer();

		$out = str_replace("&", "&amp;", $out);

		return $out;
	}

	function serve($contentType = "application/xml") {
		$xml = $this->out();
		header("Content-type: $contentType");
		echo $xml;
	}

	function header() {
		$out = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
		$out .= '<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"
>' . "\n";
		return $out;
	}

	function footer() {
		return '</rss>';
	}
}

class RSSItem {
	var $title;
	var $link;
	var $description;
	var $pubDate;
	var $guid;
	var $tags;
	var $attachment;
	var $length;
	var $mimetype;

	function RSSItem() {
		$this->tags = array();
	}

	function setPubDate($when) {
		if (strtotime($when) == false)
			$this->pubDate = date("D, d M Y H:i:s ", $when) . "GMT";
		else
			$this->pubDate = date("D, d M Y H:i:s ", strtotime($when)) . "GMT";
	}

	function getPubDate() {
		if (empty($this->pubDate))
			return date("D, d M Y H:i:s ") . "GMT";
		else
			return $this->pubDate;
	}

	function addTag($tag, $value) {
		$this->tags[$tag] = $value;
	}

	function out() {
		$out = '';
		$out .= "<item>\n";
		$out .= "<title>" . $this->title . "</title>\n";
		$out .= "<link>" . $this->link . "</link>\n";
		$out .= "<description>" . $this->description . "</description>\n";
		$out .= "<pubDate>" . $this->getPubDate() . "</pubDate>\n";

		if ($this->attachment != "")
			$out .= "<enclosure url=\"{$this->attachment}\" length=\"{$this->length}\" type=\"{$this->mimetype}\" />";

		if (empty($this->guid)) $this->guid = $this->link;
		$out .= "<guid>" . $this->guid . "</guid>\n";

		foreach ($this->tags as $key => $val) $out .= "<$key>$val</$key\n>";
		$out .= "</item>\n";
		return $out;
	}

	function enclosure($url, $mimetype, $length) {
		$this->attachment = $url;
		$this->mimetype = $mimetype;
		$this->length = $length;
	}
}
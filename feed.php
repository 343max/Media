<?php
/**
 * Created by IntelliJ IDEA.
 * User: max
 * Date: 17.05.2010
 * Time: 21:46:00
 */

require_once('config.inc.php');

require_once('class.rss.php');
	/* E X A M P L E -----------------------------------------------
			$feed = new RSS();
			$feed->title       = "RSS Feed Title";
			$feed->link        = "http://website.com";
			$feed->description = "Recent articles on your website.";

			$db->query($query);
			$result = $db->result;
			while($row = mysql_fetch_array($result, MYSQL_ASSOC))
			{
				$item = new RSSItem();
				$item->title = $title;
				$item->link  = $link;
				$item->setPubDate($create_date);
				$item->description = "<![CDATA[ $html ]]>";
				$feed->addItem($item);
			}
			echo $feed->serve();
		---------------------------------------------------------------- */

$feed = new RSS();
$feed->title = sitename;
$feed->link = mediahost;
$feed->description = 'some coole media files';

$d = dir(mediadir);

while (false !== ($entry = $d->read())) {
	if(preg_match("/^\\./", $entry)) continue;

	$filePath = mediadir . $entry;

	$item = new RSSItem();
	$item->title = $entry;
	$item->link = mediaurl . rawurlencode($entry);
	//$item->description = 'file';
	$item->setPubDate(filemtime($filePath));

	$item->guid = sha1($filePath);

	$item->enclosure(mediaurl . rawurlencode($entry), 'video/mp4', filesize($filePath));
	$feed->addItem($item);

	#echo $entry."\n";
}
$d->close();

echo $feed->serve();
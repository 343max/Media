<?php
/**
 * Created by IntelliJ IDEA.
 * User: max
 * Date: 03.07.2010
 * Time: 14:52:56
 */

require_once('../config.inc.php');

$tvShowName = $_GET['showname'];
$onlyEpisodes = preg_split('/;/', @$_GET['episodes']);

$episodeGuide = new EpguidesComParser($tvShowName);

echo json_encode($episodeGuide->getEpisodeInfo($onlyEpisodes));

?>
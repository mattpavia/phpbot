<?php

include_once('include/class/bzfquery.php');
$server = $command[1];
$query = bzfquery($server);
//print_r($query);
$host = strstr($server, ':');
if (is_numeric($host)) {
	$hostsrv = str_replace($host, '', $server);
} else {
	$hostsrv = $command[1];
}
if (strtolower($hostsrv) == strtolower($server) && !isset($query['player'])) {
	$message = "Unable to connect to server '".$query['host'].":".$query['port']."'.";
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $message);
	return;
}
elseif (isset($query['player']) && count($query['player']) == 0 && strtolower($hostsrv) !== strtolower($server)) {
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $query['host'].":".$query['port']." -- No players.");
	return;
} else {
	$message = $query['host'].":".$query['port']." (".$query['numPlayers']." players) -- ";
	foreach ($query['player'] as $player) {
		$message .= $player['sign']." ".($player['won'] - $player['lost'])."(".$player['won']."-".$player['lost'].") ";
	}
	/*if (strlen($message >= 444)) {
		$message = str_replace(substr($message, -1, -3), '...', $message);
	}*/
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $message);
}

?>
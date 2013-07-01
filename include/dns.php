<?php
if ($command[1] == '') {
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "No nameserver or IP specified.");
	return;
}
if (!ip2long($command[1])) {  
	$host = $command[1];
	$ip = gethostbyname($host);
	if ($host == $ip) {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "I can't find '".$host."' in DNS");
		return;
	}
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $host." is ".$ip);
} else {
	$ip = $command[1];
	$host = gethostbyaddr($ip);
	if (gethostbyaddr($ip) === false) {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "I can't find '".$ip."' in DNS");
		return;
	}
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $ip." is ".$host);
}
?>
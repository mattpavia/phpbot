<?php

$file = fopen('data/joins.txt', 'r');
$join_ser = fgets($file);
fclose($file);
$join = unserialize($join_ser);
if ($command[1] == 'list' || $command[1] == '') {
	if (!admin_identify($data->host)) { include('include/other.php'); return; }	
	$file = fopen('data/joins.txt', 'r');
	$join_ser = fgets($file);
	fclose($file);
	$join = unserialize($join_ser);
	$joinstr = 'Current users with an onjoin set: ';
	$count = count($join);
	$i = 0;
	foreach ($join as $chan => $stuff) {
		foreach ($stuff as $user => $stuff) {
			$i++;
			if ($i != $count) {
				$joinstr .= $user.', ';
			} else {
				$joinstr .= $user.'.';
			}
		}
	}
	$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, $joinstr);
	include('include/other.php');
	return;
}
$more_command = explode(' ', $command[1], 3);
$nick = $more_command[0];
$chan = $more_command[1];
$message = $more_command[2];
if ($nick == '') { $irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "No user specified."); return; }
elseif (substr($nick, 0, 1) !== '-' && isset($message)) {
	if (substr($chan, 0, 1) !== '#') { $irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "Invalid channel name."); return; }
	$join[$chan][$nick]['message'] = $message;
	$join_ser = serialize($join);
	$file = fopen('data/joins.txt', 'w') or die("Error!");
	fwrite($file, $join_ser);
	fclose($file);
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "Onjoin for '".$nick."' has been turned ON and set to '".$message."'");
	return;
}
elseif (substr($nick, 0, 1) == '-') {
	$nick = substr_replace($nick, '', 0, 1);
	foreach ($join as $chan => $stuff) {
		foreach ($stuff as $user => $msg) {
			if (strtolower($nick) == strtolower($user)) {
				$exists = true;
			}
		}
	}
	if (!$exists) {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "There is no onjoin set for ".$nick.".");
		return;
	}
	unset($join[$chan][$nick]);
	$join_ser = serialize($join);
	$file = fopen('data/joins.txt', 'w') or die("Error!");
	fwrite($file, $join_ser);
	fclose($file);
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "Onjoin for '".$nick."' has been turned OFF.");
	return;
}
foreach ($join as $chan => $stuff) {
	foreach ($stuff as $user => $stuff1) {
		if (strtolower($nick) == strtolower($user)) {
			foreach ($stuff1 as $msg) {
				$joinmsg = $msg;
			}
		}
	}
}
if (substr($nick, 0, 1) !== '-' && !isset($message)) {
	if (!isset($joinmsg)) {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "No onjoin set for ".$nick.".");
		return;
	}
	else {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "Onjoin for ".$nick.": '".$joinmsg."'.");
		return;
	}
}

?>
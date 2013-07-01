<?php

$file = fopen('data/joins.txt', 'r');
$join_ser = fgets($file);
fclose($file);
$join = unserialize($join_ser);
foreach ($join as $chan => $stuff) {
	if (strtolower($chan) == strtolower($data->channel)) {
		foreach ($stuff as $user => $stuff) {
			if (strtolower($user) == strtolower($data->nick)) {
				foreach ($stuff as $msg) {
					$message = $msg;
				}
			}
		}
	}
}
if (!isset($message)) {
	return;
}
else {
	$who = $command[1];
	if ($command[1] == '') {
		$who = $data->nick;
	}
	$message = str_replace('$day', date('l'), $message);
	$message = str_replace('$who', $who, $message);
	$message = str_replace('$chan', $data->channel, $message);
	$message = str_replace('$randnick', $randnick, $message);
	$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $message);
}

?>

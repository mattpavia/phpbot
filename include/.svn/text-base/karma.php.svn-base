<?php

if ($command[0] == 'karma') {
	if (strtolower(substr($command[1], 0, 3)) == 'set') {
		if (!admin_identify($data->host)) { include('include/other.php'); return; }
		$file = fopen('data/karma.txt', 'r');
		$karma_ser = fgets($file);
		fclose($file);
		$karma = unserialize($karma_ser);
		$more_command = explode(' ', $command[1], 3);
		$nick = strtolower($more_command[1]);
		$points = $more_command[2];
		$karma[$nick]['points'] = $points;
        	$karma_ser = serialize($karma);
       		$file = fopen('data/karma.txt', 'w') or die("Error!");
		fwrite($file, $karma_ser);
		fclose($file);
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "Karma updated.");
		return;
	}
	if (strtolower(substr($command[1], 0, 3)) !== 'set') {
		$file = fopen('data/karma.txt', 'r');
		$karma_ser = fgets($file);
		fclose($file);
		$karma = unserialize($karma_ser);
		$who = $command[1];
		if ($who == '') { $who = $data->nick; }
		foreach ($karma as $user => $stuff) {
			if (strtolower($user) == strtolower($who)) {
				foreach ($stuff as $karmapoints) {
					$points = $karmapoints;
				}
			}
		}
		if (!isset($points) || $points == '') {
			$points = 0;
		}
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "Karma for '".$who."' is ".$points.".");
	}
}
elseif (substr($command[0], -2, 2) == '++') {
	$nick = str_replace(substr($command[0], -2, 2), '', $command[0]);
	$nick = strtolower($nick);
	if (strtolower($data->nick) == $nick) { return; }
	$file = fopen('data/karma.txt', 'r');
	$karma_ser = fgets($file);
	fclose($file);
	$karma = unserialize($karma_ser);
	foreach ($karma as $user => $stuff) {
		if (strtolower($user) == strtolower($nick)) {
			$exists = true;
		}
	}
	if ($exists) {
		foreach ($stuff as $karmapoints) {
			$points = $karmapoints;
		}
		$karma[$nick]['points'] = $points += 1;
        $karma_ser = serialize($karma);
        $file = fopen('data/karma.txt', 'w') or die("Error!");
        fwrite($file, $karma_ser);
        fclose($file);
		return;
	}
	elseif (!isset($exists)) {
		$points = 0;
		$karma[$nick]['points'] = $points += 1;
        $karma_ser = serialize($karma);
        $file = fopen('data/karma.txt', 'w') or die("Error!");
        fwrite($file, $karma_ser);
        fclose($file);
		return;
	}
}
elseif (substr($command[0], -2, 2) == '--') {
	$nick = str_replace(substr($command[0], -2, 2), '', $command[0]);
	$nick = strtolower($nick);
	if (strtolower($data->nick) == $nick) { return; }
	$file = fopen('data/karma.txt', 'r');
	$karma_ser = fgets($file);
	fclose($file);
	$karma = unserialize($karma_ser);
	foreach ($karma as $user => $stuff) {
		if (strtolower($user) == strtolower($nick)) {
			$exists = true;
		}
	}
	if ($exists) {
		foreach ($stuff as $karmapoints) {
			$points = $karmapoints;
		}
		$karma[$nick]['points'] = $points -= 1;
        $karma_ser = serialize($karma);
        $file = fopen('data/karma.txt', 'w') or die("Error!");
        fwrite($file, $karma_ser);
        fclose($file);
		return;
	}
	elseif (!isset($exists)) {
		$points = 0;
		$karma[$nick]['points'] = $points -= 1;
        $karma_ser = serialize($karma);
        $file = fopen('data/karma.txt', 'w') or die("Error!");
        fwrite($file, $karma_ser);
        fclose($file);
		return;
	}
}
?>

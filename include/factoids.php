<?php

$factoid_file = fopen('data/factoids.txt', 'r');
$factoid_array = fgets($factoid_file);
fclose($factoid_file);
$factoid_array_un = unserialize($factoid_array);

if ($command[0] == 'factinfo') {
	if ($command[1] == '') {
		if (!admin_identify($data->host)) { include('include/other.php'); return; }
		$i = 0;
		$factoids = '';
		foreach (unserialize($factoid_array) as $trigger => $stuff) {
			$factoids .= $trigger.' ';
			$i++;
		}
		$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, "There are currently ".$i." factoid(s):");
		$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, $factoids);
	}
	foreach (unserialize($factoid_array) as $trigger => $stuff) {
		$factoid_array_un = unserialize($factoid_array);
		foreach ($stuff as $cat => $cat_info) {
			if ($cat == 'date') { $creation_date = $cat_info; }
			if ($cat == 'creatornick') { $creator_nick = $cat_info; }
			if ($cat == 'creatorident') { $creator_ident = $cat_info; }
			if ($cat == 'creatorhost') { $creator_host = $cat_info; }
			if ($cat == 'requests')    { $requests = $cat_info; }
		}
		if (strtolower($command[1]) == $trigger) {
			$message = "created by ".$creator_nick." <".$creator_ident."@".$creator_host.">";
			$message .= " on ".$creation_date.";";
			$message .= " Requested ".$requests." times.";
			$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "Factoid info for '".$command[1]."': ".$message);
			return;
		}
	}
}

if ($command[0] == 'literal') {
	foreach (unserialize($factoid_array) as $trigger => $stuff) {
		foreach ($stuff as $cat => $cat_info) {
			if ($cat == 'define') { $define = $cat_info; }
		}
		if (strtolower($command[1]) == $trigger) {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "Factoid '".$command[1]."' is '".$define."'.");
			return;
		}
	}
}
if ($command[0] == 'forget') {
	$forget_factoid = strtolower($command[1]);
	$exists = false;
	foreach (unserialize($factoid_array) as $trigger => $stuff) {
		if ($forget_factoid == $trigger) {
			$exists = true;
		}
	}
	if (!$exists) {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "No such factoid '".$command[1]."'.");
		return;
	}
	$factoid_array_un = unserialize($factoid_array);
	$ident = trim($data->ident, "*@");
	if (admin_identify($data->host)) {
		unset($factoid_array_un[$forget_factoid]);
		$factoid_array = serialize($factoid_array_un);
		$factoid_file = fopen('data/factoids.txt', 'w') or die("Error!");
		fwrite($factoid_file, $factoid_array);
		fclose($factoid_file);
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "Factoid '".$command[1]."' forgot.");
		return;
	}
	elseif ($factoid_array_un[$forget_factoid]['creatorhost'] != $data->host) {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "Unable to forget factoid '".$command[1]."'. Please contact AHA.");
		return;
	}
	else {
		unset($factoid_array_un[$forget_factoid]);
		$factoid_array = serialize($factoid_array_un);
		$factoid_file = fopen('data/factoids.txt', 'w') or die("Error!");
		fwrite($factoid_file, $factoid_array);
		fclose($factoid_file);
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "Factoid '".$command[1]."' forgot.");
		return;
	}
}
elseif ($command[0] == 'no' || $command[0] == 'no,') { //allows for factoid modification
	$more_command = explode(' ', $command[1], 4);
	$mod_factoid = strtolower($more_command[0]);
	$is = strtolower($more_command[1]);
	$mod_factoid_type = $more_command[2];
	$mod_factoid_msg = $more_command[3];
	$exists = false;
	foreach (unserialize($factoid_array) as $trigger => $stuff) {
		if ($mod_factoid == $trigger) {
			$exists = true;
		}
	}
	if (!$exists) {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "Factoid '".$mod_factoid."' does not exist.");
		return;
	}
	if ($is !== 'is') {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "Incorrect syntax.");
		return;
	}
	$factoid_array_un = unserialize($factoid_array);
	$ident = trim($data->ident, "*@");
	if (admin_identify($data->host)) {
		$factoid_array_un[$mod_factoid]['factoid'] = $mod_factoid_msg;
		$factoid_array_un[$mod_factoid]['type'] = $mod_factoid_type;
		$factoid_array_un[$mod_factoid]['define'] = $mod_factoid_type." ".$mod_factoid_msg;
		$factoid_array = serialize($factoid_array_un);        
		$factoid_file = fopen('data/factoids.txt', 'w') or die("Error!");
		fwrite($factoid_file, $factoid_array);
		fclose($factoid_file);
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $data->nick.": OK.");
		return;
	}
	elseif ($factoid_array_un[$mod_factoid]['creatorhost'] != $data->host) {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "Unable to modify factoid '".$trigger."'. Please contact AHA.");
		return;
	}
	else {
		$factoid_array_un[$mod_factoid]['factoid'] = $mod_factoid_msg;
		$factoid_array_un[$mod_factoid]['type'] = $mod_factoid_type;
		$factoid_array_un[$mod_factoid]['define'] = $mod_factoid_type." ".$mod_factoid_msg;
		$factoid_array = serialize($factoid_array_un);        
		$factoid_file = fopen('data/factoids.txt', 'w') or die("Error!");
		fwrite($factoid_file, $factoid_array);
		fclose($factoid_file);
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $data->nick.": OK.");
		return;
	}
}

foreach (unserialize($factoid_array) as $trigger => $stuff) {
    if ($command[0] == $trigger && substr(strtolower($command[1]), 0, 2) !== 'is') {
		foreach ($stuff as $cat => $cat_info) {
			if ($cat == 'factoid') { $message = $cat_info; }
			if ($cat == 'requests') { $requests = $cat_info; }
			if ($cat == 'type') { $type = $cat_info; }
			if ($cat == 'define') { $define = $cat_info; }
	    }
		$more_command = explode(' ', $command[1], 5);
		$usercount = 0;
		foreach($irc->channel[$recip]->users as $user) {
			$users[] = $user->nick;
			$usercount++;
		}
		$rand = rand(0,$usercount-1);
		$randnick = $users[$rand];
		$message = str_replace('$day', date('l'), $message);
		$message = str_replace('$who', $data->nick, $message);
		$message = str_replace('$chan', $data->channel, $message);
		$message = str_replace('$randnick', $randnick, $message);
		$message = str_replace('$1-', $command[1], $message);
		$message = str_replace('$1', $more_command[0], $message);
		$message = str_replace('$2', $more_command[1], $message);
		$message = str_replace('$3', $more_command[2], $message);
		$message = str_replace('$4', $more_command[3], $message);
		$message = str_replace('$5', $more_command[4], $message);
		if (strtolower($type) == '<reply>') { 
			$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $message);
		} elseif (strtolower($type) == '<action>') {
			$irc->message(SMARTIRC_TYPE_ACTION, $recip, $message);
		} elseif (strtolower($type) == '<alias>') {
			foreach (unserialize($factoid_array) as $trigger => $stuff) {
				if ($trigger == $message) {
					foreach ($stuff as $cat => $cat_info) {
						if ($cat == 'factoid') { $message = $cat_info; }
						if ($cat == 'type') { $type = $cat_info; }
						if ($cat == 'define') { $define = $cat_info; }
				    }
					$more_command = explode(' ', $command[1], 5);
					$usercount = 0;
					foreach($irc->channel[$recip]->users as $user) {
						$users[] = $user->nick;
						$usercount++;
					}
					$rand = rand(0,$usercount-1);
					$randnick = $users[$rand];
					$message = str_replace('$day', date('l'), $message);
					$message = str_replace('$who', $data->nick, $message);
					$message = str_replace('$chan', $data->channel, $message);
					$message = str_replace('$randnick', $randnick, $message);
					$message = str_replace('$1', $more_command[0], $message);
					$message = str_replace('$2', $more_command[1], $message);
					$message = str_replace('$3', $more_command[2], $message);
					$message = str_replace('$4', $more_command[3], $message);
					$message = str_replace('$5', $more_command[4], $message);
					if (strtolower($type) == '<reply>') { 
						$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $message);
					} elseif (strtolower($type) == '<action>') {
						$irc->message(SMARTIRC_TYPE_ACTION, $recip, $message);
					} else {
						$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $trigger." is ".$define);
					}
				}
			}
		} /*elseif (strtolower($type) == '<do>') { //$message = action to be executed
			if (!admin_identify($data->host)) { include('include/other.php'); return; }
			$message = strtolower($message);
			$message = explode(' ', $message);
			
			if ($message[0] == 'op') {
				if ($message[1] == '') {
					$irc->op($recip, $data->nick);
				} else {
					$irc->op($recip, $message[1]);
				}
			} elseif ($message[0] == 'deop') {
				if ($message[1] == '') {
					$irc->deop($recip, $data->nick);
				} else {
					$irc->deop($recip, $message[1]);
				}
			} elseif ($message[0] == 'voice') {
				if ($message[1] == '') {
					$irc->voice($recip, $data->nick);
				} else {
					$irc->voice($recip, $message[1]);
				}
			} elseif ($message[0] == 'devoice') {
				if ($message[1] == '') {
					$irc->devoice($recip, $data->nick);
				} else {
					$irc->devoice($recip, $message[1]);
				}
			} elseif ($message[0] == 'join') {
				if ($message[1] == '') {
					$irc->join($message[1]);
				}
			} elseif ($message[0] == 'part') {
				if ($message[1] == '') {
					$irc->part($recip);
				} else {
					$irc->part($message[1]);
				}
			} else {
				$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $trigger." is ".$define);
			}
		}*/ else {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $trigger." is ".$define);
		}
        $factoid_array_un = unserialize($factoid_array);
        $factoid_array_un[$trigger]['requests'] = $requests + 1;
        $factoid_array = serialize($factoid_array_un);
        $factoid_file = fopen('data/factoids.txt', 'w') or die("Error!!");
        fwrite($factoid_file, $factoid_array);
        fclose($factoid_file);
		return;
    }
}
foreach (unserialize($factoid_array) as $trigger => $stuff) {
	if ($command[0] == $trigger && substr(strtolower($command[1]), 0, 2) == 'is') {
		$exists = true;
	}
}
if ($exists) {
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "Factoid already exists.");
	return;
}
elseif (!isset($exists) && substr(strtolower($command[1]), 0, 2) == 'is') {
	$more_command = explode(' ', $command[1], 3);
	$new_factoid = strtolower($command[0]);
	$is = strtolower($more_command[0]);
	$new_factoid_type = $more_command[1];
	$new_factoid_msg = $more_command[2];
	$ident = trim($data->ident, "*@");
	$factoid_array_un = unserialize($factoid_array);
	$factoid_array_un[$new_factoid]['define'] = $new_factoid_type." ".$new_factoid_msg;
	$factoid_array_un[$new_factoid]['date'] = gmdate('D\, M j\, Y \a\\t H:i:s \G\M\T');
	$factoid_array_un[$new_factoid]['creatornick'] = $data->nick;
	$factoid_array_un[$new_factoid]['creatorident'] = $ident;
	$factoid_array_un[$new_factoid]['creatorhost'] = $data->host;
	$factoid_array_un[$new_factoid]['requests'] = 0;
	$factoid_array_un[$new_factoid]['type'] = $new_factoid_type;
	$factoid_array_un[$new_factoid]['factoid'] = $new_factoid_msg;
	$factoid_array = serialize($factoid_array_un);
	$factoid_file = fopen('data/factoids.txt', 'w') or die("Error!");
	fwrite($factoid_file, $factoid_array);
	fclose($factoid_file);
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $data->nick.': OK.');
	return;
}

else { //if the user does not call a factoid or a premade function goto the "other.php" file which includes the match functions
	include('include/other.php');
	return;
}
	
?>

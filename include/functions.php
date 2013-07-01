<?php

global $admins,$bot_name,$channels,$connect_time,$bot_real_name,$bot_ident,$bot_password;

if ($command[0] == '') { //if no command is specified, do nothing
	return;
}

elseif ($command[0] == 'join') {
	if (!admin_identify($data->host)) { include('include/other.php'); return; }
	$more_command = explode(' ', $command[1], 2);
	$chan = $more_command[0];
	$option = $more_command[1];
	if ($chan == ''){ $irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $data->nick.': You did not specify a channel to join.'); return; }
	if (substr($chan, 0, 1) !== '#') { $irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "Invalid channel name."); return; }
	foreach ($channels as $channel) {
		if (strtolower($chan) == strtolower($channel)) {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "I am already on that channel.");
			return;
		}
	}
	if ($option == '') {
		$irc->join($chan);
		$irc->message(SMARTIRC_TYPE_CHANNEL, $chan, "I have been asked to join by ".$data->nick.".");
	} elseif (strtolower($option) == 'silent') {
		$irc->join($chan);
	} else {
		$irc->join($chan);
		$irc->message(SMARTIRC_TYPE_CHANNEL, $chan, "I have been asked to join by ".$data->nick.".");
	}
	global $channels;	
	array_push($channels, strtolower($chan));
}

elseif ($command[0] == 'part') {
	if (!admin_identify($data->host)) { include('include/other.php'); return; }
	$more_command = explode(' ', $command[1], 2);
	$chan = $more_command[0];
	$option = $more_command[1];
	if (substr($chan, 0, 1) !== '#') { $chan = $data->channel; $message = $more_command[0]; }
	if ($option == '') {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $chan, "I have been asked to leave by ".$data->nick.".");
		$irc->part($chan);
	} elseif (strtolower($option) == 'silent') {
		$irc->part($chan);
	} else {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $chan, "I have been asked to leave by ".$data->nick.".");
		$irc->part($chan);
	}
	$irc->message(SMARTIRC_TYPE_CHANNEL, $chan, "I have been asked to leave by ".$data->nick.".");
	$irc->part($chan, $message);
	global $channels;
	foreach ($channels as $channel) {		
		if ($channel == $chan) {
			array_splice($channels, array_search(strtolower($chan), $channels), 1);
		}
	}
}

elseif ($command[0] == 'quit') {
	if (!admin_identify($data->host)) { include('include/other.php'); return; }
	$message = $command[1];
	if ($message == '') {
		$message = "I was asked to quit by ".$data->nick.".";
	}
	$irc->quit($message);
}

elseif ($command[0] == 'kick') {
	if (!admin_identify($data->host)) { include('include/other.php'); return; }
	$more_command = explode(' ', $command[1], 2);
	$reason = $more_command[1];
	$nick = $more_command[0];
	$irc->kick($recip, $nick, $reason);	
}

elseif ($command[0] == 'ban') {
	if (!admin_identify($data->host)) { include('include/other.php'); return; }
	$user = $command[1];
	/*foreach ($irc->channel[$recip]->users as $blah => $nick) {
		if (strtolower($user) == strtolower($nick->nick)) {
			$irc->ban($recip, $nick->host);
			return;
		}
	}*/
	$irc->ban($recip, $user);
}

elseif ($command[0] == 'unban') {
	if (!admin_identify($data->host)) { include('include/other.php'); return; }
	$user = $command[1];
	/*foreach ($irc->channel[$recip]->users as $blah => $nick) {
		if (strtolower($user) == strtolower($nick->nick)) {
			$irc->unban($recip, $nick->host);
			return;
		}
	}*/
	$irc->unban($recip, $user);
}

elseif ($command[0] == 'op') {
	if (!admin_identify($data->host)) { include('include/other.php'); return; }
	$user = $command[1];
	if ($user == '' || $user == 'me') {
		$irc->op($recip, $data->nick);
	} else {
		$irc->op($recip, $user);
	}
}

elseif ($command[0] == 'deop') {
	if (!admin_identify($data->host)) { include('include/other.php'); return; }
	$user = $command[1];
	if ($user == '' || $user == 'me') {
		$irc->deop($recip, $data->nick);
	} else {
		$irc->deop($recip, $user);
	}
}

elseif ($command[0] == 'voice') {
	if (!admin_identify($data->host)) { include('include/other.php'); return; }
	$user = $command[1];
	if ($user == '' || $user == 'me') {
		$irc->voice($recip, $data->nick);
	} else {
		$irc->voice($recip, $user);
	}
}

elseif ($command[0] == 'devoice') {
	if (!admin_identify($data->host)) { include('include/other.php'); return; }
	$user = $command[1];
	if ($user == '' || $user == 'me') {
		$irc->devoice($recip, $data->nick);
	} else {
		$irc->devoice($recip, $user);
	}
}

elseif ($command[0] == 'topic') {
	if (!admin_identify($data->host)) { include('include/other.php'); return; }
	$topic = $command[1];
	$irc->settopic($recip, $topic);
}

elseif ($command[0] == 'nick') {
	if (!admin_identify($data->host)) { include('include/other.php'); return; }
	$nick = $command[1];
	$irc->changeNick($nick);
	$irc->login($nick, $nick, 0, $nick, $bot_password);
}

elseif ($command[0] == 'invite') {
	if (!admin_identify($data->host)) { include('include/other.php'); return; }
	$nick = $command[1];
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $nick.' has been invited to '.$data->channel.'.');	
	$irc->invite($nick, $data->channel);
}

elseif ($command[0] == 'seen') {
	if ($command[1] == '') {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "No user specified.");
		return; 
	}
	$user_file = fopen('data/users.txt', 'r');
	$user_array = fgets($user_file);
	fclose($user_file);
	$user_found = false;
	$default_user = $command[1];
	$chansOn = "";
	foreach ($channels as $chan) {
		foreach ($irc->channel[$chan]->users as $user => $stuff) {
			if (strtolower($command[1]) == $user) {
				$chansOn .= $chan." ";
			}
		}
	}
	
	if ($chansOn != "") {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $command[1]." is currently on ".$chansOn."."); // TODO: display all the channels I am on with that person, not just the first one in the array
		return;
	}
	
	foreach (unserialize($user_array) as $user => $stuff) {
		foreach ($stuff as $cat => $cat_info) {
			if ($cat == "last_seen") { $last_seen = $cat_info; }
			if ($cat == "last_message") { $last_message = $cat_info; }
			if ($cat == "last_channel") { $last_channel = $cat_info; }
		}
		if ($user == strtolower($command[1])) {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $default_user." was last seen in channel ".$last_channel.", ".$last_seen.", saying: '".$last_message."'.");
			$user_found = true;			
			return;
		}
	}
	if (!$user_found && $command[1] !== $bot_name) {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "The user you specified (".$command[1].") either does not exist, or has not said anything.");
		return;
	} else {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "Hello, ".$data->nick.".");
		return;	
	}
}

elseif ($command[0] == 'eightball' || $command[0] == '8ball') {
	$eightball = array(
		"Signs point to yes.",
		"Yes.",
		"Most likely.",
		"Without a doubt.",
		"Yes, definitely.",
		"As I see it, yes.",
		"You may rely on it.",
		"Outlook good",
		"It is certain.",
		"It is decidedly so.",
		"Reply hazy, try again.",
		"Better not tell you now.",
		"Ask again later.",
		"Concentrate and ask again.",
		"Cannot predict now.",
		"My sources say no.",
		"Very doubtful.",
		"My reply is no.",
		"Outlook not so good.",
		"Don't count on it."
	);
	$count = count($eightball);
	$rand = (rand(0,$count-1));	
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $eightball[$rand]);
}

elseif ($command[0] == 'channels' || $command[0] == 'chaninfo') {
	$channel_string = "";
	$count = count($channels);
	$i = 0;
	$users = 0;
	foreach($channels as $chan) {
		$i++;
		if ($i != $count) {
			$channel_string .= $chan.'['.count($irc->channel[$chan]->users).'], ';
			$users += count($irc->channel[$chan]->users);
		} else {
			$channel_string .= $chan.'['.count($irc->channel[$chan]->users).']';
			$users += count($irc->channel[$chan]->users);
		}
	}
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "I'm currently on ".count($channels)." channel(s) with a total of ".$users." user(s): ".$channel_string.".");
}

elseif ($command[0] == 'driveby') {
	if (!admin_identify($data->host)) { include('include/other.php'); return; }
	$more_command = explode(' ', $command[1], 2);
	$channel = $more_command[0];
	$message = $more_command[1];
	if (strtolower($channel) == strtolower($recip)) { $irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "I cannot driveby the current channel."); return; }
	if ($channel == '' || $message == '') { $irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "Incorrect syntax."); return; }
	$irc->join($channel);
	$irc->message(SMARTIRC_TYPE_CHANNEL, $channel, $message);
	$irc->part($channel, "DRIVEBY!!!");
}
elseif ($command[0] == 'updated') {
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "Last updated: ".date ("F d\, Y", filemtime('include/functions.php'))." at ".date ("H:i:s \E\S\T\.", filemtime('include/functions.php')));
}

elseif ($command[0] == 'say') {
	$message = $command[1];
	if ($message == '') { $irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "You did not supply a message to send."); return; }
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $message);
}

elseif ($command[0] == 'action' || $command[0] == 'me') {
	$message = $command[1];
	if ($message == '') { $irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "You did not supply an action to send."); return; }
	$irc->message(SMARTIRC_TYPE_ACTION, $recip, $message);
}

elseif ($command[0] == 'help') {
	$help = "You can address me by using one exclamation point (!), my name followed by a colon and a space (".$bot_name.": ), my name followed by a camma and a space (".$bot_name.", ) or my name followed by just a space (".$bot_name." ).";
	$help1 = "If you supply a nonexistant command, I will return a random string. You can make that command valid by making it into a factoid.";
	$help2 = "For help with factoids, type '!factoids' on a channel I am on.";
	$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, $help);
	$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, $help1);
	$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, $help2);
}

elseif ($command[0] == 'about') {
	$about = "I'm ".$bot_name.". I was created by AHA. For more help, type: '!help'. If you have any suggestions, questions, spelling errors, general errors, or if you find a command that doesn't work, contact AHA via IRC, or e-mail him at: bzheartattack[at]gmail[dot]com.";
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $about);
}

elseif ($command[0] == 'info' || $command[0] == 'stats') {
	include_once('include/class/duration.php');
	include_once('include/class/dirsize.php');
	$dirsize = directorysize('.',TRUE);
	global $connect_time;
	$current_time = mktime();
	$uptime = $current_time - $connect_time;
	$uptime = Duration::toString($uptime); // makes the uptime nice and pretty using the duration.php class
	$data = shell_exec('uptime'); // executes 'uptime'
	$sysuptime = explode(' up ', $data); // get rid of all the stuff we don't need
	$sysuptime = explode(',', $sysuptime[1]);
	$days = explode(':', $sysuptime[1]);
	$day1 = explode(' ', $days[0]);
	$sysuptime = $sysuptime[0].', '.$day1[2].' hours and '.$days[1].' minutes';
	$info = "I am currently on ".count($channels)." channel(s). Last updated: ".date("F d\, Y \a\\t g:i A T", filemtime('include/functions.php')).". Last core update: ".date("F d\, Y \a\\t g:i A T", filemtime('main.php')).". Current bot uptime: ".$uptime.". Current system uptime: ".$sysuptime.". I am currently using about ".$dirsize." of disk space.";
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $info);
}

elseif ($command[0] == 'rejoin' || $command[0] == 'hop') {
	if (!admin_identify($data->host)) { include('include/other.php'); return; }
	$channel = $command[1];
	if ($channel == "") { $channel = $data->channel; }
	$irc->part($channel);
	$irc->join($channel);
}

elseif ($command[0] == 'coinflip') {
	$coinflip = array(
		"Heads!",
		"Tails!",
	);
	$count = count($coinflip);
	$randflip = rand(0,$count-1);
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $coinflip[$randflip]);
}

elseif ($command[0] == 'gender') {
	$user = $command[1];
	if ($user == '' || $user == 'me') {
		$user = $data->nick;
	}
	$gender = array(
		"My sources say ".$user." is male.",
		"My sources say ".$user." is female.",
		"I don't know what gender ".$user." is.",
		"Error. There is no information on the gender of ".$user."."
	);
	$count = count($gender);
	$randgender = rand(0,$count-1);
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $gender[$randgender]);
}

elseif ($command[0] == 'gaynessmeter' || $command[0] == 'gaymeter') {
	$user = $command[1];
	if ($user == '' || $user == 'me') {
		$user = $data->nick;
	}
	if (strtolower($user) == 'aha') {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $user." is NOT gay.");
		return;
	}
	$randgayness = rand(0,110);
	if ($randgayness == 0) {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $user." is NOT gay.");
	} elseif ($randgayness > 100) {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $user." is so gay they broke the damn meter!");
	} else {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $user." is ".$randgayness."% gay.");
	}
}

elseif ($command[0] == 'access') { // NOTE: bot needs to have access on the channel in order to perform this function. TODO: spit out an error if he is not on the access list
	if (!admin_identify($data->host)) { include('include/other.php'); return; }
	$more_command = explode(' ', $command[1], 3);
	$what = $more_command[0];
	$who = $more_command[1];
	$level = $more_command[2];
	if ($who == '' || $what == '') { $irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "Incorrect syntax."); return; }
	if (strtolower($what) == 'del' || strtolower($what) == 'delete' || strtolower($what) == 'rem' || strtolower($what) == 'remove') {
		$irc->message(SMARTIRC_TYPE_QUERY, ChanServ, "access ".$recip." del ".$who);
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $who." removed from access list for ".$recip.".");
	}
	if (strtolower($what) == 'add') {
		$irc->message(SMARTIRC_TYPE_QUERY, ChanServ, "access ".$recip." add ".$who." ".$level);
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $who." has been added to access list for ".$recip." with level of: ".$level.".");
	}
}

elseif ($command[0] == 'eval') {
	if (!admin_identify($data->host)) { include('include/other.php'); return; }
	$stuff = $command[1];
	$premessage = "try{ ";
	$postmessage = " } catch(Exception \$e){ \$irc->message(SMARTIRC_TYPE_CHANNEL, \$recip, \$e->getMessage()); }";	
	eval($premessage."\$stuff = $stuff;".$postmessage);
}

elseif ($command[0] == 'ignore') {
	if (!admin_identify($data->host)) { include('include/other.php'); return; }
	if ($command[1] == 'list') {
		$file = fopen('data/ignoreusers.txt', 'r');
		$ignoreusers_ser = fgets($file);
		fclose($file);
		$ignoreusers = unserialize($ignoreusers_ser);
		$ignorestr = 'Current users on the ignore list: ';
		$count = count($ignoreusers);
		$i = 0;
		// there is probably an easier way to do this, but oh well
		foreach ($ignoreusers as $user) {
			$i++;
			if ($i != $count) {
				$ignorestr .= $user.', ';
			} else {
				$ignorestr .= $user.'.';
			}
		}
		$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, $ignorestr);
		include('include/other.php');
		return;
	}
	$user = strtolower($command[1]);
	if ($user == '') { $irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "No user specified."); return; }
	$file = fopen('data/ignoreusers.txt', 'r');
	$ignoreusers_ser = fgets($file);
	fclose($file);
	$ignoreusers = unserialize($ignoreusers_ser);
	foreach ($ignoreusers as $ignoreuser) {
		if ($user == $ignoreuser) {
			$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $command[1]." is already on the ignore list.");
			return;
		}
	}
	array_push($ignoreusers, $user);
	$ignoreusers_ser = serialize($ignoreusers);
	$ignorefile = fopen('data/ignoreusers.txt', 'w') or die("Error!!");
	fwrite($ignorefile, $ignoreusers_ser);
	fclose($ignorefile);
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $command[1]." added to the ignore list.");
}

elseif ($command[0] == 'unignore') {
	if (!admin_identify($data->host)) { include('include/other.php'); return; }	
	$user = strtolower($command[1]);
	if ($user == '') { $irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "No user specified."); return; }
	$file = fopen('data/ignoreusers.txt', 'r');
	$ignoreusers_ser = fgets($file);
	fclose($file);
	$ignoreusers = unserialize($ignoreusers_ser);
	if (!in_array($user, $ignoreusers)) {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $command[1]." is not on the ignore list.");
		return;
	}
	array_splice($ignoreusers, array_search($user, $ignoreusers), 1);
	$ignoreusers_ser = serialize($ignoreusers);
	$ignorefile = fopen('data/ignoreusers.txt', 'w') or die("Error!!");
	fwrite($ignorefile, $ignoreusers_ser);
	fclose($ignorefile);
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $command[1]." removed from the ignore list.");
}

elseif ($command[0] == 'uptime') {
	include_once('include/class/duration.php');
	global $connect_time;
	$current_time = mktime();
	$uptime = $current_time - $connect_time;
	$uptime = Duration::toString($uptime);
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "Current uptime: ".$uptime.".");
}

elseif ($command[0] == 'update') { // NOTE: there is no real purpose in this function. It is only useful when the bot is lagging a lot
	if (!admin_identify($data->host)) { include('include/other.php'); return; }
	$irc->disconnect("I have been asked to update by ".$data->nick.".");
	$irc->connect('irc.freenode.net', 6667);
	$irc->login($bot_name, $bot_real_name, 0, $bot_ident, $bot_password);
	$irc->join($channels);
	$irc->listen();
}

elseif ($command[0] == 'hexdec') {
	$decip = hexdec($command[1]);
	$ip = long2ip($decip);
	if ($ip == "0.0.0.0" || $ip == "0") {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "Error in converting '".$command[1]."' to a dotted quad IP.");
		return;
	}
	elseif ($ip == "255.255.255.255") {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $command[1]." is already a dotted IP.");
		return;
	}
	else {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $command[1]." is ".$ip." (".gethostbyaddr($ip).")");
	}
}

elseif ($command[0] == 'roulette') {
	$rand = rand(0,10);
	if ($rand < 7) {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "*CLICK*");
	}
	elseif ($rand >= 7) {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "*BANG*");
		$irc->kick($recip, $data->nick, "YOU'RE DEAD BITCH");
	}	
}

elseif ($command[0] == 'udict') {
	include('include/udict.php');
}

elseif ($command[0] == 'chucknorris') {
	include('include/chucknorris.php');
}

elseif ($command[0] == 'dns') {
	include('include/dns.php');
}

elseif ($command[0] == 'lart') {
	include('include/lart.php');
}

elseif ($command[0] == 'karma' || substr($command[0], -2, 2) == '++' || substr($command[0], -2, 2) == '--') {
	include('include/karma.php');
}

elseif ($command[0] == 'bzquery' || $command[0] == 'bzfquery') { //BZFlag functions
	include('include/bzquery.php');
}

elseif ($command[0] == 'onjoin') {
	include('include/onjoin.php');
}

elseif ($command[0] == 'spell') {
	include('include/spell.php');
}

elseif ($command[0] == 'ipinfo') {
	include('include/ipinfo.php');
}

else {
	include('include/factoids.php');
}

?>

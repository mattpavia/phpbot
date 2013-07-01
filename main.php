<?php
require_once('Net_SmartIRC-1.0.2/SmartIRC.php');
include('config/config.php');
$irc = &new Net_SmartIRC();

function admin_identify($host) {
	global $admins;
	$admin = false;
	foreach ($admins as $adminhost) {    
		if (strtolower($host) == strtolower($adminhost)) {
			$admin = true;
		}
	}	
	return $admin;
}

class bot {

function kick(&$irc, &$data) {
	global $bot_name, $channels;
	$nick = $data->rawmessageex[3];
	$chan = $data->rawmessageex[2];
	if (strtolower($nick) == strtolower($bot_name)) {
		foreach ($channels as $channel) {		
			if ($channel == $chan) {
				array_splice($channels, array_search($chan, $channels), 1);
			}
		}
	}
}

function hug(&$irc, &$data) {
	global $bot_name;
	$message = explode(" ", $data->message, 4);
	if (strtolower($message[1]) == "hugs" && strtolower(substr($message[2], 0, strlen($bot_name))) == strtolower($bot_name)) {
		$irc->message(SMARTIRC_TYPE_ACTION, $data->channel, "hugs ".$data->nick);
	}
}


function invite(&$irc, &$data) {
	global $bot_name;
	$nick = $data->rawmessageex[2];
	$chan = $data->rawmessageex[0];
	if (strtolower($nick) == strtolower($bot_name)) {
		$irc->join($chan);
		global $channels;	
		array_push($channels, $chan);
	}
}

function say(&$irc, &$data) {
	if (!admin_identify($data->host)) { return; }
	$file = fopen('data/ignoreusers.txt', 'r');
	$ignoreusers = fgets($file);
	fclose($file);
	$ignoreusers_un = unserialize($ignoreusers);
	foreach ($ignoreusers_un as $user) {
		if (strtolower($data->nick) == $user || strtolower($data->host) == $user) {
			return;
		}
	}
	$command = explode(' ', substr($data->message, 4), 2);
	$channel = $command[0];
	$message = $command[1];
	if (substr($command[0], 0, 1) !== '#') {
		$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, "Invalid channel.");
		return;
	}
	if ($command[1] == '') {
		$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, "No text to send.");
		return;
	}
	$irc->message(SMARTIRC_TYPE_CHANNEL, $channel, $message);
}

/*function sendMessage($to, $msg) {
    global $irc;
    $irc->message(SMARTIRC_TYPE_CHANNEL, $to, $msg);
}

function sendAction($to, $msg) {
    global $irc;
    $irc->message(SMARTIRC_TYPE_ACTION, $to, $msg);
}

function sendQuery($to, $msg) {
    global $irc;
    $irc->message(SMARTIRC_TYPE_QUERY, $to, $msg);
}*/

function onjoin(&$irc, &$data) {
	include('include/join.php');
}

function main(&$irc, &$data) {
	/*$logfile = fopen('logs/'.$data->channel.date('\-M\-j').'.txt', 'a');
	foreach ($irc->channel[$data->channel]->ops as $op => $stuff) {
		if ($op == $data->nick) {
			$nick = "@".$op;
		}
	}
	foreach ($irc->channel[$data->channel]->voices as $voice => $stuff) {
		if ($voice == $data->nick) {
			$nick = "+".$voice;
		}
	}
	if (!isset($nick)) {
		$nick = $data->nick;
	}
	fwrite($logfile, "[".date("G:i:s")."] <".$nick."> ".$data->message."\n");
	fclose($logfile);*/
	$recip = $data->channel;
	$msgtome = false;
	global $triggers;
	foreach ($triggers as $prefix) {
		if (strtolower(substr($data->message, 0, strlen($prefix))) == $prefix ) {
			$msgtome = true;
			$msgcmd = substr($data->message, strlen($prefix));
		}
	}
	$user_file = fopen('data/users.txt', 'r');
	$user_array = fgets($user_file);
	fclose($user_file);
	$lower_nick = strtolower($data->nick);
	foreach (unserialize($user_array) as $user => $stuff) {
		$requests = 0;
		$talk_count = 0;
		foreach ($stuff as $cat => $cat_info) {
			if ($cat == "requests") { $requests = $cat_info; }
			if ($cat == "talk_count") { $talk_count = $cat_info; }
		}
		if ($lower_nick == $user) {
			$user_array_un = unserialize($user_array);
			if ($msgtome) {
				$user_array_un[$user]['requests'] = $requests + 1;
			}
			$user_array_un[$user]['talk_count'] = $talk_count + 1;
			$user_array = serialize($user_array_un);
		}
	}
	if(strtolower(substr($data->message, 0, 1)) == "s") {
		include('include/replace.php');
		return;
	}
	$user_array_un = unserialize($user_array);
	$user_array_un[$lower_nick]['last_seen'] = date('h:i:s A \o\n l\, F dS\, Y');
	$user_array_un[$lower_nick]['last_message'] = $data->message;
	$user_array_un[$lower_nick]['last_channel'] = $recip;
	$user_array = serialize($user_array_un); 
	$user_file = fopen('data/users.txt', 'w') or die("Error!!!");
	fwrite($user_file, $user_array);
	fclose($user_file);

	if ($msgtome === true) {
		$file = fopen('data/ignoreusers.txt', 'r');
		$ignoreusers = fgets($file);
		fclose($file);
		$ignoreusers_un = unserialize($ignoreusers);
		foreach ($ignoreusers_un as $user) {
			if (strtolower($data->nick) == $user || strtolower($data->host) == $user) {
				return;
			}
		}
   		$command = explode(' ', $msgcmd, 2);
		$command[0] = strtolower($command[0]);
		include('include/functions.php');
	}
}

}

$bot = new bot();
$irc = &new Net_SmartIRC();
$irc->registerActionhandler(SMARTIRC_TYPE_QUERY, '^say', $bot, 'say');
$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '.*', $bot, 'main');
$irc->registerActionhandler(SMARTIRC_TYPE_INVITE, '.*', $bot, 'invite');
$irc->registerActionhandler(SMARTIRC_TYPE_JOIN, '.*', $bot, 'onjoin');
$irc->registerActionhandler(SMARTIRC_TYPE_KICK, '.*', $bot, 'kick');
$irc->registerActionhandler(SMARTIRC_TYPE_ACTION, '.*', $bot, 'hug');
$irc->setUseSockets(TRUE);
$irc->setChannelSyncing(TRUE);
$irc->setDebug(SMARTIRC_DEBUG_IRCMESSAGES);
$irc->connect('irc.freenode.net', 6667);
$irc->login($bot_name, $bot_real_name, 0, $bot_ident, $bot_password);
$connect_time = mktime();
$irc->join($channels);
$irc->listen();
$irc->disconnect();
?>

<?php
$more_command = explode('/', $data->message);
$s = $more_command[0];
$origword = $more_command[1];
$replaceword = $more_command[2];
if ($origword == '') {
	return;
}
$user_file = fopen('data/users.txt', 'r');
$user_array = fgets($user_file);
fclose($user_file);
foreach (unserialize($user_array) as $user => $stuff) {
	if ($user == strtolower($data->nick)) {
		foreach ($stuff as $cat => $cat_info) {
			if ($cat == "last_message") { $last_message = $cat_info; }
		}
		if (!strstr($last_message, $origword)) {
			return;
		}
		elseif ($more_command[3] == 'g') {
			$newphrase = str_replace($origword, $replaceword, $last_message);
			$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $data->nick." meant: ".$newphrase);
		}
		elseif ($more_command[3] == '') {
			$newphrase = substr_replace($last_message, $replaceword, strpos($last_message, $origword), strlen($origword));
			$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $data->nick." meant: ".$newphrase);
		}
	}
}

?>
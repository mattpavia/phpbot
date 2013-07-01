<?php

include_once('include/class/math.php');
$expression = str_replace(' ', '', $msgcmd);
$output = "";
list($result,$error) = evaluate($expression);
if (strlen($error) > 0) {
	$output = $error;
} else {
	$output = $result;
}

if ($output == "Syntax error." || $output == "Division by zero.") {
	global $random;
	if ($random === false) {
		return;
	}
	$other = array(
		"Stop speaking Canadian... I can't understand you.",
		"Yeah, That made a lot of sense...",
		"L0LROFLLOLZZZ!!!!ONE!!111!1!ELEVEN!11!!111!",
		"You really should stop sniffing glue.",
		"Hitting random keys does not make you look smart.",
		"What do you think I am, stupid?",
		"Okay... Whatever you say, Einstein.",
		"That's what SHE said!!!",
		"Hahahaha j00 f41l n00b!",
		"Ahh, the sweet sound of stupidity!"
	);
	$count = count($other);
	$randother = rand(0,$count-1);
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $other[$randother]);
} else {
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $output);
}
?>
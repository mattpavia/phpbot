<?php

include_once('include/class/aspell.php');
$spell = new Aspell("en");
$spell->setMode(ASPELL_ULTRA);
$suggestions = $spell->suggest($command[1]);
if (!ctype_alpha($command[1])) {
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "Only one word containting only alphabetic characters are allowed.");
	return;
} else {
	$suggestedwords = implode(', ', $suggestions);
	if ($suggestedwords == '') {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "'".$command[1]."' may be spelled correctly.");
		return;
	} else {
		$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "Spelling suggestions for '".$command[1]."': ".implode(', ', $suggestions).".");
	}
}

?>
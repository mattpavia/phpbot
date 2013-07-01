<?php

include_once('include/class/math.php');
$expression = trim($data->message, " ");
$output = "";
if (strlen($expression) > 0) {
	list($result,$error) = evaluate($expression);
	if (strlen($error) > 0) {
		$output = $error;
	} else {
		$output = $result;
	}
}
if ($output == "Syntax error.") {
	include('include/other.php');
	return;
}
$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $output);
	
?>
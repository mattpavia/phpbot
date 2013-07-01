<?php

$more_command = explode(' ', $command[1], 2);
$word_search = $more_command[1];
$define_number = $more_command[0];
if ($define_number == '') {
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "No word specified.");
	return;
}
if ($word_search == '' && is_numeric($define_number)) {
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "Please specify a word to look up.");
	return;
}
if (!is_numeric($define_number)) {
	if ($more_command[1] == '') {
		$word_search = $more_command[0];
		$define_number = 1;
	} else {
		$word_search = $more_command[0]." ".$more_command[1];
		$define_number = 1;
	}
} else {
	$word_search = $more_command[1];
	$define_number = $more_command[0];
}
require_once('SOAP/Client.php');
$soap = new SOAP_Client('http://api.urbandictionary.com/soap');
$soapoptions = array('namespace' => 'urn:UrbanDictionarySearch', 'trace' => 0);
$params = array(
	'key' => 'c63b95cddcdf81dd98599a7402cb5902',
	'term' => $word_search
);
$result = $soap->call('lookup', $params, $soapoptions);
if (PEAR::isError($result)) {
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "There was an error in looking up your word: ".$result->getMessage());
	return;
} else {
	if (count($result) == 0) {
		$message = "Urban Dictionary '".$params['term']."': No definition";
	} else {
		$replace = array("\r\n", "\r", "\n");
		$message = "Urban Dictionary '".$params['term']."' (".$define_number." of ".count($result)."): ";
		$message .= str_replace($replace, ' ', $result[$define_number-1]->definition);
	}
	if (strlen($message) >= 500) {
		$message = substr($message, 0, 496).' ...';
	}
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $message);
	return;
}

?>
<?php

include_once('include/class/geoip.php');

$geoip = Net_GeoIP::getInstance("include/class/countrydb/geoip.dat");

if ($command[1] == '') {
	$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, "No nameserver or IP specified.");
	return;
}

$country = $geoip->lookupCountryName(gethostbyname($command[1]));

$irc->message(SMARTIRC_TYPE_CHANNEL, $recip, $country);

?>
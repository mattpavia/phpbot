<?php

$user = $command[1];
if ($user == '' || $user == 'me') {
	$user = $data->nick;
}
$larts = array(
	"pours itch powder into ".$user."'s pants.",
	"urinates on ".$user."'s computer.",
	"grabs ".$user."'s baby powder and replaces it with itch powder.",
	"tapes ".$user." to the front of a bus.",
	"--purges ".$user,
	"beats ".$user." senseless with a 50lb Unix manual.",
	"cats /dev/urandom into ".$user."'s ear.",
	"chops ".$user." in half with a free AOL CD.",
	"chops ".$user." in half with a free Solaris 7 CD.",
	"decapitates ".$user." conan the destroyer style.",
	"does a little 'renice 20 -u ".$user."'.",
	"drops a truckload of VAXen on ".$user.".",
	"duct-tapes ".$user." to the floor and drools on him.",
	"frags ".$user." with his BFG9000.",
	"holds ".$user." to the floor and spanks him with a cat-o-nine-tails.",
	"judo chops ".$user.".",
	"pours hot grits down the front of ".$user."'s pants.",
	"pulls out his louisville slugger and uses ".$user."'s head as batting practice.",
	"pushes a wall down onto ".$user." whilst whistling innocently.",
	"resizes ".$user."'s terminal to 40x24.",
	"rm -rf's ".$user.".",
	"stabs ".$user.".",
	"steals ".$user."'s mojo.",
	"strangles ".$user." with a doohicky mouse cord.",
	"urinates on ".$user.".",
	"whacks ".$user." with the cluebat.",
	"whips out a sword and chops ".$user." in half.",
	"whips out his power stapler and staples ".$user."'s genitalia to the ground.",
	"DDoSes ".$user.".",
	"grabs a foam pool toy and whacks ".$user." profusely.",
	"dusts off a kitchen towel and slaps it at ".$user."."
);
$count = count($larts);
$rand = (rand(0,$count-1));
$irc->message(SMARTIRC_TYPE_ACTION, $recip, $larts[$rand]);

?>

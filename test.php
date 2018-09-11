<?php
$whitelist = file_get_contents("whitelist.txt");

$whitelisted = explode(PHP_EOL, $whitelist);

foreach($whitelisted as $admins) {
	echo $admins;
}
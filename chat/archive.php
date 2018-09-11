<?php
require_once __DIR__.'/../Config.php';

if(isset($_SESSION['user'])) {
	$room = isset($_GET['room']) ? $_GET['room'] : 1;
	$Chat->retrieveAllMessages(1, $room);
}
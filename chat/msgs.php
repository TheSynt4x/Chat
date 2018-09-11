<?php
require_once __DIR__.'/../Config.php';

if(isset($_GET['room'])) {
	$Chat->retrieveAllMessages(0, $_GET['room']);
} elseif(isset($_GET['archived'], $_GET['room'])) {
	$Chat->retrieveAllMessages(1, $_GET['room']);
} else {
	$Chat->retrieveAllMessages(0, 1);
}

if(!isset($_SESSION['user']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest'){
 echo "<script>window.location.reload()</script>";
}

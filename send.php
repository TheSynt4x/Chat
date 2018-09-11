<?php
include("config.php");

if(!isset($_SESSION['user']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest'){
 die("<script>window.location.reload()</script>");
}

if(isset($_SESSION['user'], $_POST['msg'])) {
	$room = (isset($_GET['room'])) ? $_GET['room'] : 1;
	$user = $DB->query('SELECT * FROM users WHERE name = ?')->bind(1, $_SESSION['user'])->single();
	$Chat->sendMessage($user->name, $user->nick, $_POST['msg'], $room);
}
?>

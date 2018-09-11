<?php
require_once 'config.php';

if(isset($_SESSION['user'])){
	$Users->isLoggedIn($_SESSION['user']);
}


<?php
session_start();

require_once 'functions/ParseBBCode.php';
require_once 'functions/StartsWith.php';
require_once 'functions/Contains.php';
require_once 'functions/SmileyParser.php';
require_once 'functions/Escape.php';


spl_autoload_register(function($class) {
	require_once 'classes/' . $class . '.php';
});

Config::set('Host', '127.0.0.1');
Config::set('User', 'root');
Config::set('Pass', '');
Config::set('DB', 'newchat');

$DB = new DB(Config::get('User'), Config::get('Pass'), Config::get('DB'));
$Users = new Users($DB);
$Chat = new Chat($DB);

if(isset($_SESSION['user'])){
	$Users->isLoggedIn($_SESSION['user']);
}



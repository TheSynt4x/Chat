<?php
class Users {
	public $DB;

	public function __construct($db) {
		$this->DB = $db;
	}

	public function loginUser($name) {
		$name = htmlspecialchars($name);
		$name = str_replace(" ", "_", $name);
		$name = strtolower($name);

		if(strlen($name) < 5) {
			die("{$name} is too short");
		}

		if($name != null && !is_null($name)) {	
			$query = $this->DB->query('SELECT name FROM users WHERE name = ?')->bind(1, $name);
			
			if($query->num_rows() > 0) {
				return false;
			} else {
				$_SESSION['user'] = $name;
				$whitelist = file_get_contents("whitelist.txt");

				$whitelisted = explode(PHP_EOL, $whitelist);

				if(in_array($name, $whitelisted)) {
					$_SESSION['admin'] = 1;

					$sql = $this->DB->query("INSERT INTO users (name, nick, seen, ban, admin) VALUES (?, ?, NOW(), 0, 1)")->bind(1, $name)->bind(2, $name)->execute();
				} else {
					$sql = $this->DB->query("INSERT INTO users (name, nick, seen, ban, admin) VALUES (?, ?, NOW(), 0, 0)")->bind(1, $name)->bind(2, $name)->execute();
				}

				
			}
		}
	}
	

	public function isLoggedIn($name) {
		$query = $this->DB->query('SELECT * FROM users WHERE name = ?')->bind(1, $name);

		if($query->num_rows() > 0) {
			$update = $this->DB->query('UPDATE users SET seen = NOW() WHERE name = ?')->bind(1, $name)->execute();
		} else {
			$insert = $this->DB->query("INSERT INTO users (name, seen) VALUES (?, NOW())")->bind(1, $name)->execute();
		}

		$allUsers = $this->DB->query('SELECT * FROM users')->resultset();
  
		foreach($allUsers as $user) {
			$curtime = strtotime(date("Y-m-d H:i:s", strtotime('-25 seconds', time())));
			if(strtotime($user->seen) < $curtime) {
				$delete = $this->DB->query('DELETE FROM users WHERE name = ?')->bind(1, $user->name)->execute();
			}
		}
		
	}

	public function logoutUser($name) {
		$sql = $this->DB->query("DELETE FROM users WHERE name = ?")->bind(1, $name)->execute();
		session_destroy();
		header("Location: index.php");
	}
}
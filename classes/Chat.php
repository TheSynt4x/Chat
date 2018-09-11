<?php
class Chat {
	public $DB, $isBanned, $exc;

	public function __construct($db) {
		$this->DB = $db;

	}

	public function retrieveAllMessages($isArchived, $room = null) {
		if(isset($room)) {
			$query = $this->DB->query('SELECT * FROM messages WHERE room = ? AND archived = ? ORDER BY id ASC')->bind(1, $room)->bind(2, $isArchived);
		} else {
			$query = $this->DB->query('SELECT * FROM messages WHERE archived = ? ORDER BY id ASC')->bind(1, $isArchived);
		}

		if($query->num_rows() > 0) {
			$resultset = $query->resultset();



			foreach($resultset as $result) {
				$result->msg = stripslashes(escape($result->msg));
				$result->msg = str_replace("&gt;", ">", $result->msg);				
				$BBMessage = ParseBBCode(ParseSmiley($result->msg));
				
				$avatarPicture = null;
				$backgroundColor = null;
				$usernameColor = null;
				$rankColor = null;
				$rank = null;
				$textColor = null;

				$users = $this->DB->query('SELECT * FROM users WHERE nick = ?')->bind(1, $result->nick);
				if($users->num_rows() > 0) {
					$user = $users->single();


					if(contains("http://", $user->avatar) OR contains('https://', $user->avatar) && !empty($user->avatar)) {
						$user->avatar = str_replace("http://2.bp.blogspot.com/-4G-jNG9Yr3s/Tv9Rrz9lMJI/AAAAAAAAAlc/f2PmgzhpYds/s400/unsure%2Bemoticon.png", ":/", $user->avatar);
						$avatarPicture .= "<img src='{$user->avatar}' style='float:left;margin-left:5px;margin-right:5px;' width='22' height='22'></img>";
					}
	 				
	 				if(contains("http://", $user->background) OR contains("https://", $user->background) && !empty($user->background)) {
						$backgroundColor = "style='background-image: url({$user->background});background-attachment: fixed;background-position: center;'";
	 				} else {
						$backgroundColor = "style='background-color:{$user->background};'";
	 				}

	 				if(!empty($user->username_color)) {
	 					$usernameColor = "style='-webkit-text-stroke: 1px {$user->username_color};'";
	 				} else {
	 					$usernameColor = "";
	 				}

	 				if(!empty($user->rank_color)) {
	 					$rankColor .= "style='margin-right:10px;float:right;-webkit-text-stroke: 1px {$user->rank_color};'";
	 				}

	 				if(!empty($user->rank)) {
	 					$rank = $user->rank;
	 				}

					if(startsWith($result->msg, ">")) {
	 					$textColor = "style='color:#789922'";
	 				}

	 				if(!empty($user->text_color)) {
	 					$textColor = "style='color:{$user->text_color};'";
	 				}

				}
				 									echo "<div {$backgroundColor} class='msg' title='{$result->posted}'>{$avatarPicture} <span {$usernameColor} class='name'>{$result->nick}</span> : <span {$textColor} class='msgc'>{$BBMessage} <text {$rankColor}>{$rank}</text></span></div>";

				}
	
		} else {
			return false;
		}
	}

	public function ban($name, $ban) {
		$query = $this->DB->query('SELECT * FROM users WHERE nick = ?')->bind(1, $name);

		if($query->num_rows() > 0) {
			$ban = $this->DB->query('UPDATE users SET ban = ? WHERE nick = ? AND nick <> admin')->bind(1, $ban)->bind(2, $name);

			if($ban->num_rows() > 0) {
				$ban->execute();
				return 2;
			} else {
				return 1;
			}

		} else {
			return 0;
		}
	}

	public function roll($user, $msg, $rolled) {
		$msg = "[b]Rolled:[/b] {$rolled}";

		if($rolled >= 100) {
			$msg = "User [b]{$user}[/b] rolled {$rolled} AND WON!!";
		}
		
		$this->insert($user, stripslashes(escape($msg)));

	}

	public function changeProperty($user, $splitted) {
		if($splitted[1] == 'avatar' or $splitted[1] == 'background' or $splitted[1] == 'username_color' or $splitted[1] == 'text_color' or $splitted[1] == 'rank' or $splitted[1] == 'rank_color')

		$query = $this->DB->query('SELECT * FROM users WHERE name = ?')->bind(1, $user)->num_rows();	
		
		if($query) {
			return $this->DB->query("UPDATE users SET {$splitted[1]} = '{$splitted[2]}' WHERE name = '{$user}'");
		} else {
			$this->insert("System", "User [b]{$user}[/b] does not exist!");
		}

	}

	public function insert($user, $msg, $room = 1) {
		$this->DB->query("INSERT INTO messages (name, nick, msg, posted, room) VALUES (?, ?, ?, NOW(), ?)")->bind(1, $user)->bind(2, $user)->bind(3, $msg)->bind(4, $room)->execute();
		exit;
	}

	public function checkCommands($user, $msg, $room) {
		$split_params = null;
		if($msg[0] == '/') {
			if(strpos($msg, ' ')) {
				$split_params = explode(' ', $msg);
			} else {
				$split_params[0] = $msg;
			}
		}

		if(isset($_SESSION['admin'])) {
			switch(strtolower($split_params[0])) {
				case '/ban':
					if(isset($split_params[1])) {
						$name = stripslashes(escape($split_params[1]));
						
						$ban = $this->ban($name, 1);

						switch($ban) {
							case 0:
								$msg = "User [b]{$name}[/b] not found!";
								break;
							case 1:
								$msg = "Did you try to ban admin [b]{$name}[/b]?";
								break;
							case 2:
								$msg = "[b]Banned:[/b] {$name}!";
								break;
						}
					} else {
						$msg = "Incorrect command syntax! Use /ban username";
					}
					
					$this->insert($user, $msg);
					break;
				case '/unban':
					$msg = null;

					if(isset($split_params[1])) {
						$name = stripslashes(escape($split_params[1]));
						$unban = $this->ban($name, 0);

						switch($unban) {
							case 0:
								$msg = "User [b]{$name}[/b] not found!";
								break;
							case 1:
								$msg = "Did you try to unban admin [b]{$name}[/b]?";
								break;
							case 2:
								$msg = "[b]Unbanned:[/b] {$name}!";
								break;
						}
					
					} else {
						$msg = "Incorrect command syntax! Use /unban username";						
					}

					$this->insert($user, $msg);
					break;
				case '/roll':
					if(isset($split_params[1])) {
						$this->roll($user, $msg, $split_params[1]);
					} elseif(!isset($split_params[1])) {
						$this->roll($user, $msg, rand(1, 100));
					} else {
						$this->insert($user, "Incorrect command syntax! Use /roll");
					}
					break;
				case '/clear':
					$this->DB->query('UPDATE messages SET archived = 1 WHERE room = ?')->bind(1, $room)->execute();
					$this->insert($user, "Messages got archived in room {$room}! [b]Check [url=../chat/archive.php?room={$room}]the archive[/url].[/b]");
					exit;
					break;
				case '/truncate':
					$this->DB->query('TRUNCATE messages')->execute();
					exit;
					break;
				case '/nick':
					if(isset($split_params[1], $split_params[2])) {
						$oldname = stripslashes(escape($split_params[1]));
						$newname = stripslashes(escape($split_params[2]));

						$query = $this->DB->query('SELECT * FROM users WHERE nick = ?')->bind(1, $oldname);
						
						if($query->num_rows() > 0) {
							$this->DB->query('UPDATE users SET nick = ? WHERE name = ?')->bind(1, $newname)->bind(2, $oldname)->execute();
							$this->DB->query('UPDATE messages SET nick = ? WHERE name = ?')->bind(1, $newname)->bind(2, $oldname)->execute();
							$this->insert($user, "{$oldname}'s nickname has been changed!", $room);
						} else {
							$this->insert($user, "{$oldname} was not found!");
						}
					} else {
						$this->insert($user, "Incorrect command syntax! Use /nick originalusername newname");
					}

					break;
				case '/set':
					if(isset($split_params[1], $split_params[2], $split_params[3])) {
						$key = $split_params[1];
						$value = $split_params[2];

						if($this->changeProperty($split_params[3], [1 => $key, 2 => $value])->num_rows() > 0) {
							$this->insert($user, 'Updated [b]' . $key . '[/b] for user: [b]' . $split_params[3] . '[/b]');
						} else {
							$this->insert($user, 'Could not update [b]' . $key . '[/b] for user: [b]' . $split_params[3] . '[/b]');
						}
						//$this->insert($user, 'Did not update the user property');
					} elseif(isset($split_params[1], $split_params[2]) or !isset($split_params[3])) {
						$key = $split_params[1];
						$value = $split_params[2];

						if($this->changeProperty($user, [1 => $key, 2 => $value])->num_rows() > 0) {
							$this->insert($user, 'Updated [b]' . $key . '[/b] for user: [b]' . $user . '[/b]');
						} else {
							$this->insert($user, 'Could not update [b]' . $key . '[/b] for user: [b]' . $user . '[/b]');
						}
						

					}

					break;
				case '/reset':
					if(isset($split_params[1])) {
						$name = $split_params[1];
						$query = $this->DB->query('SELECT * FROM users WHERE nick = ?')->bind(1, $name);
						
						if($query->num_rows() > 0) {
							$this->DB->query("UPDATE users SET username_color = '', background = '', avatar = '', rank_color = '', rank  = '', text_color = '' WHERE name = '{$split_params[1]}'")->execute();
							$this->insert($user, "Reset user: [b]{$split_params[1]}[/b]");
						} else {
							if($split_params[1] != '') {
								$this->insert($user, "Could not find user [b]{$split_params[1]}[/b]");
							} else {
								$this->insert($user, "Invalid syntax! No spaces allowed.");
							}
						}
					} else {
						$this->DB->query("UPDATE users SET username_color = '', background = '', avatar = '', rank_color = '', rank  = '', text_color = '' WHERE name = '{$user}'")->execute();
						$this->insert($user, "Reset user: [b]{$user}[/b]");
					}
					break;

				}				 
			}

			if(isset($_SESSION['user'])) {
				switch(strtolower($split_params[0])) {
					case '/roll':
						$this->roll($user, $msg, rand(1, 100));
						break;
				}
			}
	}

	public function sendMessage($user, $nick, $msg, $room) {
		$msg = stripslashes(escape($msg));

		$ban = $this->DB->query('SELECT ban FROM users WHERE name = ?')->bind(1, $user)->single();
		

		if($msg != "" && $msg != null && strlen($msg) < 200 && $ban->ban == 0) {
			$this->checkCommands($user, $msg, $room);
			$this->DB->query("INSERT INTO messages (name, nick, msg, room, posted) VALUES (?, ?, ?, ?, NOW())")->bind(1, $user)->bind(2, $nick)->bind(3, $msg)->bind(4, $room)->execute();
		}
		
		if($ban->ban == 1) {
			$_SESSION['banned'] = 1;
			header('../');
		} 
	}

	public function retrieveAllUsers() {
		echo "<h2>Users</h2>";
		$resultset = $this->DB->query("SELECT nick FROM users")->resultset();

		foreach($resultset as $result) {
			$result->nick = stripslashes(htmlspecialchars($result->nick));
			echo "<div class='user'>{$result->nick}</div>";
		}
	}
}
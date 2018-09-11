<?php
class Example {
	public $id, $name;

	public function __construct($id = null, $name = null) {
		$this->id = $id;
		$this->name = $name;
	}

}

class Message {
	public $id, $name, $message;

	public function __construct($id, $name, $message) {
		$this->id = $id;
		$this->name = $name;
		$this->message = $message;
	}	
}

class API {
	public $connection;

	public function __construct() {
		$this->connection = new MySQLi('127.0.0.1', 'root', '', 'chat');
	}

	public function getUsers() {
		$query = $this->connection->query("SELECT * FROM users ORDER BY joinedTime");
		$arr = [];
		
		while($row = $query->fetch_object()) {
			//$example = new Example($row['id'], $row['name']);
			$arr[] = $row;
		}

		return json_encode($arr);
	}

	public function getMessages($lastid = null) {
		$query = $this->connection->query("SELECT * FROM messages WHERE id > {$lastid}");
		$arr = [];
		
		while($row = $query->fetch_assoc()) {
			$example = new Message($row['id'], $row['name'], $row['message']);
			$arr[] = $example;
		}

		return json_encode($arr);
	}

	public function insertData($name) {
		$query =  $this->connection->query("SELECT * FROM users WHERE name = '{$name}'");

		if($query->num_rows <= 0) {
			$query1 = $this->connection->query("INSERT INTO users (id, name) VALUES (NULL, '{$name}')");

			echo '{"status":1}';
		} else {
			echo '{"status":0}';
		}
	}

	public function insertMessage($name, $message) {
		$query = $this->connection->query("INSERT INTO messages (id, name, message) VALUES (NULL, '{$name}', '{$message}')");
	}

	public function disconnect($name) {
		$query = $this->connection->query("DELETE FROM users WHERE name = '{$name}'");
	}
}

$api = new API();

$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));

switch ($method) {
  case 'POST':
    if($request[0] == 'users') {
    	$user = json_decode(file_get_contents('php://input'));

    	if(is_string($user->name)) {
    		$api->insertData($user->name);
    	} else {
    		http_response_code(400);
    	}
    } elseif($request[0] == 'messages') {
    	$message = json_decode(file_get_contents('php://input'));
    	
    	if(is_string($message->name) && is_string($message->message)) {
    		$api->insertMessage($message->name, $message->message);
			echo '{"status":1}';
    	} else {
    		http_response_code(400);
    	}
    	
    } 
    break;
  case 'GET':
    //rest_get($request);

  	if($request[0] == 'disconnect') {
  		if(is_string($request[1])) {
  			$api->disconnect($request[1]);
  		} else {
  			http_response_code(400);
  		}
  	}

    if($request[0] == 'users') {
    	echo $api->getUsers();
    } elseif($request[0] == 'messages') {
    	$value = (!is_numeric($request[1]) && !is_int($request[1])) ? -1 : $request[1];

    	echo $api->getMessages($value);
    }
    break;
  default:
    //rest_error($request);  
    break;
}

//echo $_POST['username'] . "<br/>";
//echo $_POST['password'] . "<br/>";
//echo $_POST['content'] . "<br/>";


<?php
$ermsg = null;

if(isset($_POST['name']) && !isset($display_case)){
	$user = $Users->loginUser(stripslashes(escape($_POST['name'])));

	if($user == false) {
		$ermsg = "<h2 class='error'>Name taken. <a href='index.php'>Try another name.</a></h2>";
	}
	
} elseif(isset($display_case)) {
	if(!isset($ermsg)) {
?>
 <h2>Name needed for chatting</h2>
 You must provide a name for chatting. This name will be visible to other users.<br/><br/>
 <form autocomplete="off" action="index.php" method="POST">
  <div>Your Name : <input name="name" placeholder="A Name Please"/></div>
  <button>Submit & Start Chatting</button>
 </form>
<?php
} else {
  echo $ermsg;
 }
}
?>

<?php
require_once __DIR__.'/../Config.php';
if(isset($_SESSION['user'])){
?>

 <div class='msgs'>
 
  <?php 
  	require_once 'msgs.php';
  ?>
 </div>
 <form method="POST" autocomplete="off" id="msg_form">
  <input name="msg" size="30" type="text"/>
  <button>Send</button>
 </form>
<?php
}
?>
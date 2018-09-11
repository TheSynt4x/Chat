<?php require_once 'config.php'; require_once 'login.php'; 
$room = (isset($_GET['room'])) ? $_GET['room'] : 1;
$archived = (isset($_GET['archived'])) ? $_GET['archived'] : 0;
?>
<!DOCTYPE html>
<html>
 <head>
  <script src="//code.jquery.com/jquery-latest.js"></script>
  <script>
function scTop(){
 $(".msgs").animate({scrollTop:$(".msgs")[0].scrollHeight});
}
function load_new_stuff(){
 localStorage['lpid']=$(".msgs .msg:last").attr("title");
 $(".msgs").load("chat/msgs.php?room=<?php echo $room; ?>&archived=<?php echo $archived; ?>",function(){
  if(localStorage['lpid']!=$(".msgs .msg:last").attr("title")){
   scTop();
  }
 });
 $(".users").load("users.php");
}
$(document).ready(function(){
 scTop();
 $("#msg_form").on("submit", function(){
  t=$(this);
  val=$(this).find("input[type=text]").val();
  if(val!=""){
   t.after("<span id='send_status'>Sending.....</span>");
   $.post("send.php?room=<?php echo $room; ?>",{msg:val},function(){
    load_new_stuff();
    $("#send_status").remove();
    t[0].reset();
   });
  }
  return false;
 });
});
setInterval(function(){
 load_new_stuff();
},5000);

  </script>
  <link href="chat.css" rel="stylesheet"/>
  <title>PHP Group Chat</title>
 </head>
 <body>
  <div id="content" style="margin-top:10px;height:100%;">
   <center><h1>Group Chat In PHP</h1></center>
   <div class="chat">
    <div class="users">
     <?php include("users.php");?>
    </div>
    <div class="chatbox">
     <h2><?php  if(isset($_SESSION['user'])) { echo "Room " . $room; ?></h2>
      <a style="right: 20px;top: 20px;position: absolute;cursor: pointer;" href="logout.php">Log Out</a>
      <?php } ?>
     <?php
     if(isset($_SESSION['user'])){

            include("chat/chatbox.php");

     
     }else{
      $display_case = true;
      include("login.php");
     }
     ?>
    </div>
   </div>
  </div>
 </body>
</html>

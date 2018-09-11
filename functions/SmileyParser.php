<?php
function ParseSmiley($msg) {
	$smiles = array(
	  '&lt;3' => 'http://3.bp.blogspot.com/-yH4Y8RvQvqc/TvjOQgHW6eI/AAAAAAAAAic/X5vEH21CXEA/s400/heart%2Bemoticon.png',
	  '&gt;:)'	=> 'http://3.bp.blogspot.com/-h2ErlFczszQ/TvirzZanEUI/AAAAAAAAAh4/rghgvQxSXmQ/s400/devil%2Bemoticon.png',
	  ':(('	=> 'http://3.bp.blogspot.com/-2xtlB3F7l4U/UZFOZ1FWvrI/AAAAAAAADoI/9P9JWS-ZIEI/s1600/facebook-cry-emoticon-crying-symbol.png',
	  ":'(" => 'http://3.bp.blogspot.com/-2xtlB3F7l4U/UZFOZ1FWvrI/AAAAAAAADoI/9P9JWS-ZIEI/s1600/facebook-cry-emoticon-crying-symbol.png',
	  ':*'	=> 'http://4.bp.blogspot.com/-iadOX6ehRnw/TvjNY8KWmJI/AAAAAAAAAiQ/Jg6rpG5_r_Y/s400/kiss%2Bemoticon.png',
	  ':))'	=> 'http://2.bp.blogspot.com/-OsnLCK0vg6Y/UZD8pZha0NI/AAAAAAAADnY/sViYKsYof-w/s1600/big-smile-emoticon-for-facebook.png',
	  ':D'	=> 'http://2.bp.blogspot.com/-OsnLCK0vg6Y/UZD8pZha0NI/AAAAAAAADnY/sViYKsYof-w/s1600/big-smile-emoticon-for-facebook.png',
	  ':-D'	=> 'http://2.bp.blogspot.com/-OsnLCK0vg6Y/UZD8pZha0NI/AAAAAAAADnY/sViYKsYof-w/s1600/big-smile-emoticon-for-facebook.png',
	  ':)'	=> 'http://4.bp.blogspot.com/-ZgtYQpXq0Yo/UZEDl_PJLhI/AAAAAAAADnk/2pgkDG-nlGs/s1600/facebook-smiley-face-for-comments.png',
	  ':-)'	=> 'http://4.bp.blogspot.com/-ZgtYQpXq0Yo/UZEDl_PJLhI/AAAAAAAADnk/2pgkDG-nlGs/s1600/facebook-smiley-face-for-comments.png',
	  ':('	=> 'http://2.bp.blogspot.com/-rnfZUujszZI/UZEFYJ269-I/AAAAAAAADnw/BbB-v_QWo1w/s1600/facebook-frown-emoticon.png',
	  ':-('	=> 'http://2.bp.blogspot.com/-rnfZUujszZI/UZEFYJ269-I/AAAAAAAADnw/BbB-v_QWo1w/s1600/facebook-frown-emoticon.png',
	  ';)'	=> 'http://1.bp.blogspot.com/-lX5leyrnSb4/Tv5TjIVEKfI/AAAAAAAAAi0/GR6QxObL5kM/s400/wink%2Bemoticon.png',
	  ';-)'	=> 'http://1.bp.blogspot.com/-lX5leyrnSb4/Tv5TjIVEKfI/AAAAAAAAAi0/GR6QxObL5kM/s400/wink%2Bemoticon.png'
	);

	foreach($smiles as $key => $img) {
		$msg = str_replace($key, "[img]{$img}[/img]", $msg);
	}
	return $msg;
}

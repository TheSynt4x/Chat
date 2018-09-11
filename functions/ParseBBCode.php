<?php
function ParseBBCode($string){
	 $font_bbcodes = array('b','i','u');
	 
	 $color_bbcodes = array('red','green','lime','blue','darkgreen','black','white','yellow','orange','maroon','grey','AliceBlue');
	 
     $string = stripslashes($string);
     $string = htmlspecialchars($string);
 
     for($i = 0;$i <= (count($font_bbcodes)-1); $i++){
     $string = preg_replace("/\[".$font_bbcodes[$i]."\](.+?)\[\/".$font_bbcodes[$i]."\]/is",'<'.$font_bbcodes[$i].'>\1</'.$font_bbcodes[$i].'>', $string); 	 
	 }
	 
	 for($i = 0;$i <= (count($color_bbcodes)-1); $i++){
     $string = preg_replace("/\[".$color_bbcodes[$i]."\](.+?)\[\/".$color_bbcodes[$i]."\]/is",'<span style="color:'.$color_bbcodes[$i].';">\1</span>', $string); 	 
	 }
	 
	 $string = preg_replace("/\[url=(.+?)\](.+?)\[\/url\]/is",'<a href=$1 target="_blank">$2</a>', $string); 	 
	 
	 $string = preg_replace("/\[img](.+?)\[\/img\]/is",'<img src="$1" width="16" height="16">', $string); 	 
	 
	 return $string;	 
}

<?php
function escape($string) {
   $trans_tbl = get_html_translation_table(HTML_ENTITIES);
   $trans_tbl = array_flip ($trans_tbl);
   return strtr($string, $trans_tbl);
}
?>
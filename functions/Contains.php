<?php
function contains($needle, $haystack) {
    if (strpos($haystack, $needle) !== false) {
        return true;
    }
    return false;
}
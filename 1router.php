<?php

//$domain = substr($_GET["q"], 1); 
//$domain = parse_url($domain);

$url = 'http://www.gabrielweinberg.com/blog/2011/07/nginx-json-hacks.html';

print_r(parse_url($url));

echo parse_url($url, PHP_URL_PATH);

//$clear = $here[5][0].".".$here[6][0];







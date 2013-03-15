<?php
/*
usage whois_query("example.com");
*/

$domain = $_GET["q"];
echo (nl2br(whois_query("$domain")));
function whois_query($domain) {

    require "ext_list.php";
    require "server_list.php";

	$domain = substr($_GET["q"], 1); 
    // Check and tidy domain name
    $domain = strtolower(trim($domain));
    $domain = preg_replace('/^http:\/\//i', '', $domain);
    $domain = preg_replace('/^www\./i', '', $domain);
    $domain = explode('/', $domain);
    $domain = trim($domain[0]);
    $dp = explode(".", $domain);
    $dp = @$dp[1];

    $error = NULL;

    // Must be in the format eregi(domainText[dot]extensionText{b/w 2-4 removed for int'l... todo})
    if ((!preg_match("/^[a-zA-Z0-9-]+\.([a-zA-Z]{2,4})$/i", $domain)) &&
        (!preg_match("/^[a-zA-Z0-9-]+\.([a-zA-Z]{2,4})+\.([a-zA-Z]{2,4})$/i", $domain)) &&
        (!preg_match("/^[a-zA-Z0-9-]+\.(travel)$/i", $domain))

        ){
        $error = 'Domain name appears to be invalid.';
    }
    
    // If function called and domain not set
    if (!isset($domain)) $error = 'Please enter a valid domain name.';

  
    if (!in_array("$dp", $available)) $error = 'Domain extension either invalid or unavailable.';
    // enter further errors here if you require them...

    

    $_domain = explode('.', $domain);
    $lst = count($_domain)-1;
    $ext = $_domain[$lst];
 
     // Connect to server and get whois information
        if (isset($servers[$ext]) && $error == FALSE ) {
            $nic_server = $servers[$ext];
            $output = '';
            // connect to whois server: //43
            if ($conn = @fsockopen ($nic_server, 43, $errno, $errstr, 1)) {
                    if (!$conn) {
                        $output = $error;
                    }
                    else {
                        fputs($conn, $domain."\r\n");
                        while(!feof($conn)) {
                            $output .= fgets($conn,128);
                        }
                        fclose($conn);
                    }
        }
        else { 
            $output = 'Error: Could not connect to ' . $nic_server . '!'; 
        }

    
    return $output;

	}
   return $error;
}
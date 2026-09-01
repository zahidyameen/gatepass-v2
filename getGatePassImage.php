<?php
// include 'tokenVerification.php';
include 'serverConnection.php';

// Changed from $_POST to $_GET
if(isset($_GET['fileName'])){
    $name    = $_GET['fileName'];
    $url     = $url . $name;
    
    $c = curl_init($url);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($c, CURLOPT_USERPWD, $authString2);
    
    $content = curl_exec($c);
    $contentType = curl_getinfo($c, CURLINFO_CONTENT_TYPE);
    
    // Pass the image content type and raw bytes back to the client
    header('Content-Type: ' . $contentType);
    print $content;
    
    curl_close($c);
} 
?>
<?php
include 'connection.php';
include 'serverConnection.php';
if( isset($_POST['file'])
){
    $name    = $_POST['file'];
    $url    = $url2.$name ;
    $c = curl_init($url);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($c, CURLOPT_USERPWD, $authString2);
    $content = curl_exec($c);
    $contentType = curl_getinfo($c, CURLINFO_CONTENT_TYPE);
    header('Content-Type:'.$contentType);
    print $content;
    curl_close($c);
}else{
    echo  "";
}
$conn=null;

?>
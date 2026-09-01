<?php
$tns = " 
(DESCRIPTION =
    (ADDRESS_LIST =
      (ADDRESS = (PROTOCOL = TCP)(HOST = 10.1.0.0)(PORT = 1521))
    )
    (CONNECT_DATA =
      (SERVICE_NAME = orcl)
    )
  )
       ";
$db_username = "APPS";
$db_password = "SYSORANET786";
try{
 
    $conn = new PDO("oci:dbname=".$tns,$db_username,$db_password,
    array(
      PDO::ATTR_TIMEOUT => 180, // in seconds
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  ));			
}catch(PDOException $e){
    echo ($e->getMessage());
}
?>

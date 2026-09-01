<?php
include 'connection.php';
include('serverConnection.php');
if (isset($_FILES['filename']) && isset($_POST['docId'])
    ){
    $localFile = $_FILES['filename']['tmp_name'];
    $fp = fopen($localFile, 'r');
    $docId = $_POST['docId'];
    $name = $_POST['name'];
    $imageUrl="smb://10.1.0.9/igp_pic/$name";
    $urlToSave="\\\\10.1.0.9\\igp_pic\\$name";
    try {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERPWD, $authString2);
        curl_setopt($ch, CURLOPT_URL, $imageUrl);
        curl_setopt($ch, CURLOPT_UPLOAD, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 86400);
        curl_setopt($ch, CURLOPT_INFILE, $fp);
        curl_setopt($ch, CURLOPT_NOPROGRESS, false);
        curl_setopt($ch, CURLOPT_BUFFERSIZE, 128);
        curl_setopt($ch, CURLOPT_INFILESIZE, filesize($localFile));
        curl_exec($ch);
        if (curl_errno($ch)) {
            $msg = false;
        } else {
            $msg = true;
        }
        curl_close($ch);

        $q="UPDATE  EXP_OGP_M  A  SET  A.IMAGE_PATH = '$urlToSave' WHERE A.OGPNO ='$docId'";

        $statement = $conn->prepare($q);
        $statement->execute();
        if ($msg && $statement) {
            $return =['isUploaded' => true, "name" => $name];
        } else {
             $return =['isUploaded' => false , "name" => $name];
       }
        echo json_encode($return);
    } catch (Exception $e) {
        //display custom message
       $return =['isUploaded' => false, "name" => $name];
        echo json_encode($return);
    } catch (PDOException $e) {
        //display custom message
          $return =['isUploaded' => false, "name" => $name];
        echo json_encode($return);
    }
} 
else{
    echo  "";
  }
$conn=null;
$count=0;


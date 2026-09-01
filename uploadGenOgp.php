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

        $q="UPDATE  MAIN_GATE_PASS_M A 
                SET A.STATUS = 1, 
                A.PIC_PATH = '$urlToSave',
                A.GATE_TIME = SYSDATE,
                A.GATE_DATE = SYSDATE ,
                A.GATE_USER = 'GHULAM RAZA' 
                WHERE  A.DOC_ID = '$docId' AND A.DOC_TYPE = 'OGP' ";

        // $q="UPDATE  MAIN_GATE_PASS_M A SET A.STATUS = 1, A.PIC_PATH = '$urlToSave' WHERE  A.DOC_ID = '$docId' AND A.DOC_TYPE = 'OGP' ";

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


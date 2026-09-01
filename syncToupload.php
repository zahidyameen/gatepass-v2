<?php
include 'connection.php';
include('serverConnection.php');
if (isset($_FILES['filename']) && isset($_POST['name']) && isset($_POST['user'])
    ){
    $localFile = $_FILES['filename']['tmp_name'];
    $fp = fopen($localFile, 'r');
    $name = $_POST['name'];
    $user = $_POST['user'];
    $date = $_POST['date'];
    $unit = $_POST['unit'];
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

        $q = "INSERT INTO MAIN_GATE_PASS_M (DOC_NO,DOC_ID,DOC_TYPE,DATED,STATUS,INS_USER,PIC_PATH,INTIME,UNIT)
              SELECT NVL(MAX(TO_NUMBER(SUBSTR(M.DOC_NO,1,LENGTH(M.DOC_NO)-3))),0)+1||TO_CHAR(SYSDATE,'-YY') ,NVL(MAX(M.DOC_ID),0)+1,'IGP',
              TRUNC(TO_DATE('$date', 'DD-MM-YYYY HH:MI:SS AM')),0,'tab','$urlToSave',
              TO_CHAR(SYSDATE,'HH24:MI'), $unit
              FROM MAIN_GATE_PASS_M M
              WHERE TO_CHAR(M.DATED,'YY')=TO_CHAR(SYSDATE,'YY')";

        $statement = $conn->prepare($q);
        $statement->execute();
        if ($msg && $statement) {
            $return =['isUploaded' => true, "name" => $name];
        } else {
            $return =  $return =['isUploaded' => false , "name" => $name];
       }
        echo json_encode($return);
    } catch (Exception $e) {
        //display custom message
        $return =  $return =['isUploaded' => false, "name" => $name];
        echo json_encode($return);
    } catch (PDOException $e) {
        //display custom message
        $return =  $return =['isUploaded' => false, "name" => $name];
        echo json_encode($return);
    }
} 
else{
    echo  "";
  }
$conn=null;
$count=0;


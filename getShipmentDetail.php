<?php
include 'connection.php';
try{
    if(isset($_POST['docNo']))
    {
    $docNo= $_POST['docNo'];
    $q = "SELECT A.*,(SELECT B.ART_NAME FROM P_ARTICLE B WHERE A.ART_ID = B.ART_ID)ARTICLE FROM EXP_OGP_D  A WHERE A.OGPNO = '$docNo'";
    $statement2 = $conn->query($q);
    $data2=$statement2->fetchAll(PDO::FETCH_ASSOC);	
    echo json_encode($data2);
    }
} catch (PDOException $e) {
echo ($e->getMessage());
} finally{
$conn=null;
$count=0;
}
?>

<?php
include 'connection.php';
try{
    if(isset($_POST['docNo']))
    {
    $docNo= $_POST['docNo'];

    // $docNo = "251011632";
    $q = "SELECT A.OGPNO,A.REV_NO,A.DATED,A.REMARKS,A.OGPNO ||'-'||A.REV_NO PDF_NAME fROM EXP_OGP_REV A WHERE A.OGPNO = '$docNo' ORDER BY A.REV_NO DESC";
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

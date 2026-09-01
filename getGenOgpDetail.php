<?php
include 'connection.php';
try{
    if(isset($_POST['docNo']))
    {
    $docNo= $_POST['docNo'];
    $q = "select A.ITEMCODE,B.DESCRIPTION,A.REMARKS,A.QTY from main_gate_pass_D A,V_GATE_PASS_ITEM B  WHERE  A.ITEMCODE = B.INVDETAILCODE
            AND  A.MDOC_ID = '$docNo'";
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

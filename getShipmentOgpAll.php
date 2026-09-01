<?php
include 'connection.php';
try{
    if(isset($_POST['name']))
    {
    $q = "SELECT 
            A.OGPNO,
            LISTAGG(X.ORDERNO, ', ') WITHIN GROUP (ORDER BY X.ORDERNO) AS ORDERS,
            A.DATED,
            A.CUST_CODE,
            A.DRIVER,
            A.VEHICLE_NO,
            A.ADDRESS,
            A.REMARKS,
            A.INS_USER,
            TO_CHAR(A.INS_TIMESTAMP,'DD-MON-YYYY HH24:MI:SS') AS INS_TIMESTAMP,
            A.INS_TERMINAL,
            A.UPD_USER,
            A.UPD_TERMINAL,
            A.UPD_TIMESTAMP,
            A.GP_TYPE,
            A.COM_INVOICE_NO,
            A.POSTED,
            A.SHIPSTATUS,
            A.DRIVERPHONE,
             TO_CHAR(A.POST_DATE,'DD-MON-YYYY HH24:MI:SS') AS POST_DATE,
            A.POST_USER,
            A.RETURNABLE,
            A.CARTONNO,
            A.TRACKINGNUM,
            A.PARTY_NAME,
            A.PARTY_CODE,
            A.VEHICLE_TYPE,
            A.HOD_APP,
            TO_CHAR(A.HOD_APP_DATE,'DD-MON-YYYY HH24:MI:SS') AS HOD_APP_DATE,
            A.STATUS,
            A.IMAGE_PATH
        FROM EXP_OGP_M A
        LEFT JOIN (
            SELECT DISTINCT OGPNO, ORDERNO
            FROM EXP_OGP_D
        ) X ON A.OGPNO = X.OGPNO
        WHERE A.DATED >= DATE '2025-10-13'
        AND A.HOD_APP = 'T'
        AND A.STATUS <> -1
        ---AND A.OGPNO = 251011666
        GROUP BY 
            A.OGPNO, A.DATED, A.CUST_CODE, A.DRIVER, A.VEHICLE_NO, A.ADDRESS, 
            A.REMARKS, A.INS_USER, A.INS_TIMESTAMP, A.INS_TERMINAL, 
            A.UPD_USER, A.UPD_TERMINAL, A.UPD_TIMESTAMP, A.GP_TYPE, 
            A.COM_INVOICE_NO, A.POSTED, A.SHIPSTATUS, A.DRIVERPHONE, 
            A.POST_DATE, A.POST_USER, A.RETURNABLE, A.CARTONNO, A.TRACKINGNUM, 
            A.PARTY_NAME, A.PARTY_CODE, A.VEHICLE_TYPE, A.HOD_APP, 
            A.HOD_APP_DATE, A.STATUS, A.IMAGE_PATH
            ORDER BY  A.DATED DESC";
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

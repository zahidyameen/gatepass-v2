<?php
header('Content-Type: application/json');
include 'connection.php';

try {
    // Safely retrieve the unit parameter (will be null if not sent)
    $unit = isset($_POST['unit']) ? trim($_POST['unit']) : null;

    // 1. Base query without the unit filter
    $q = "SELECT A.DOC_ID,
                A.DATED,
                A.PARTY_CODE,
                A.DRIVER,
                A.VEHICLE_NO,
                A.ADDRESS,
                A.REMARKS,
                A.INS_USER,
                A.INS_TERMINAL,
                TO_CHAR(A.INS_TIMESTAMP,'DD-MON-YYYY HH24:MI:SS') AS INS_TIMESTAMP,
                A.UPD_USER,
                A.UPD_TERMINAL,
                TO_CHAR(A.UPD_TIMESTAMP,'DD-MON-YYYY HH24:MI:SS') AS UPD_TIMESTAMP,
                A.DOC_TYPE,
                A.DOC_NO,
                A.STATUS,
                A.PARTY_NAME,
                A.REF_OGP_NO,
                A.TRANS_TYPE,
                A.BYHAND,
                A.REF_OGP_ID,
                A.REF_DOC_NO,
                A.INTIME,
                A.BUILTY,
                A.EMP_ID,
                A.SUB_DEPT_ID,
                A.T_PERSON,
                A.REF_COM_OGP,
                A.PIC_PATH,
                A.GATE_TIME,
                TO_CHAR(A.GATE_DATE,'DD-MON-YYYY HH24:MI:SS') AS GATE_DATE,
                A.GATE_USER,
                A.UNIT,
                C.EMPNAME,
                (SELECT B.SUB_DEPTNAME
                    FROM HR_SUB_DEPARTMENT B
                    WHERE A.SUB_DEPT_ID = B.SUB_DEPT_ID) DEPARTMENT
            FROM MAIN_GATE_PASS_M A, HR_EMPLOYEES C
            WHERE A.STATUS <> -1
            AND A.DOC_TYPE = 'OGP'
            AND A.DATED > '13-OCT-2025'
            AND A.EMP_ID = C.EMPID(+)";

    $params = [];

    // 2. Dynamically append Unit condition
    if (!empty($unit)) {
        if ($unit == '3') {
            // If unit is 3, show ONLY unit 3
            $q .= " AND A.UNIT = :unit";
            $params[':unit'] = $unit;
        } elseif ($unit == '1') {
            // If unit is 1, show unit 1 AND NULL units
            $q .= " AND (A.UNIT = :unit OR A.UNIT IS NULL)";
            $params[':unit'] = $unit;
        } else {
            // Catch-all for any other specific unit number
            $q .= " AND A.UNIT = :unit";
            $params[':unit'] = $unit;
        }
    }
    // Note: If $unit is empty or null, the above block is skipped, returning ALL records

    // 3. Append ordering
    $q .= " ORDER BY A.DATED DESC";

    // 4. Prepare and execute securely
    $statement = $conn->prepare($q);
    $statement->execute($params);
    
    $data = $statement->fetchAll(PDO::FETCH_ASSOC); 
    echo json_encode($data);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    $conn = null;
}
?>
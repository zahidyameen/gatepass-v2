<?php
header('Content-Type: application/json');
include 'connection.php';

try {
    // Safely retrieve parameters
    $date = isset($_POST['date']) ? trim($_POST['date']) : null;
    $unit = isset($_POST['unit']) ? trim($_POST['unit']) : null;

    // 1. Base query containing common columns and static conditions
    $q = "SELECT 
            TO_CHAR(M.DATED, 'DD-MM-YYYY') || ' ' || M.INTIME DATED,
            TO_CHAR(M.DATED, 'DD-MM-YYYY') DT,
            M.PARTY_CODE, M.DRIVER, M.VEHICLE_NO, M.ADDRESS, M.REMARKS,
            M.DOC_ID, M.INS_USER, M.INS_TERMINAL,
            TO_CHAR(M.INS_TIMESTAMP, 'DD-MM-YYYY HH:MI:SS AM') INS_TIMESTAMP,
            M.UPD_USER, M.UPD_TERMINAL,
            TO_CHAR(M.UPD_TIMESTAMP, 'DD-MM-YYYY HH:MI:SS AM') UPD_TIMESTAMP,
            M.DOC_NO, M.STATUS, M.PARTY_NAME, M.REF_OGP_NO, M.TRANS_TYPE,
            M.BYHAND, M.REF_OGP_ID, M.REF_DOC_NO, M.INTIME, M.BUILTY,
            M.EMP_ID, M.SUB_DEPT_ID, M.T_PERSON, M.REF_COM_OGP, M.PIC_PATH
          FROM MAIN_GATE_PASS_M M 
          WHERE M.PIC_PATH IS NOT NULL 
          AND M.DOC_TYPE = 'IGP'";

    $params = [];

    // 2. Dynamically append Date condition if provided
    if (!empty($date)) {
        $q .= " AND TO_CHAR(M.DATED, 'DD-MON-YY') = :date";
        $params[':date'] = $date;
    }else {
        $q .= " AND M.DATED >= TRUNC(SYSDATE) - 60";
    }

    // 3. Dynamically append Unit condition if provided
    if (!empty($unit)) {
        if ($unit == '1') {
            // If unit is 1, show unit 1 AND NULL units
            $q .= " AND (M.UNIT = :unit OR M.UNIT IS NULL)";
            $params[':unit'] = $unit;
        } else {
            // If unit is anything else (like 3), match it exactly
            $q .= " AND M.UNIT = :unit";
            $params[':unit'] = $unit;
        }
    }

    // 4. Append ordering
    $q .= " ORDER BY M.DATED DESC, M.INTIME DESC";

    // 5. Prepare and execute the dynamic query safely
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
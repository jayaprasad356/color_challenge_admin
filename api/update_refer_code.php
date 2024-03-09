<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include_once('../includes/crud.php');

$db = new Database();
$db->connect();

$sql = "SELECT id, referred_by FROM users WHERE basic = 1 OR premium = 1 OR lifetime = 1";
$db->sql($sql);
$res = $db->getResult();

foreach ($res as $row) {
    $referred_by = $row['referred_by'];
    
    $sql = "SELECT referred_by FROM users WHERE refer_code = '$referred_by' AND refer_code != ''";
    $db->sql($sql);
    $refres = $db->getResult();
    if (!empty($refres)) {
        $c_referred_by = $refres[0]['referred_by'];
        
        $sql = "SELECT referred_by FROM users WHERE refer_code = '$c_referred_by' AND refer_code != ''";
        $db->sql($sql);
        $refres2 = $db->getResult();
        if (!empty($refres2)) {
            $d_referred_by = $refres2[0]['referred_by'];
            
            $updateSql = "UPDATE users SET c_referred_by = '$c_referred_by', d_referred_by = '$d_referred_by' WHERE referred_by = '$referred_by'";
            $db->sql($updateSql);
        }
    }
}

$response['success'] = true;
$response['message'] = "c_referred_by and d_referred_by updated successfully";
echo json_encode($response);
?>

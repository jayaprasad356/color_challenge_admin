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

$user_id = (isset($_POST['user_id']) && !empty($_POST['user_id'])) ? $db->escapeString($_POST['user_id']) : '';
            
$sql = "SELECT * FROM settings WHERE id=1";
$db->sql($sql);
$res= $db->getResult();
$num = $db->numRows($res);

if ($num >= 1){
    if($user_id != ''){
        $sql = "SELECT * FROM users WHERE id=$user_id";
        $db->sql($sql);
        $ures= $db->getResult();
        if($ures[0]['earn'] != 0){
            $res[0]['refer_coins'] = 10;
        }

    }

    $response['success'] = true;
    $response['message'] = "Settings Listed Successfully";
    $response['data'] = $res;
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "Data Not found";
    print_r(json_encode($response));

}

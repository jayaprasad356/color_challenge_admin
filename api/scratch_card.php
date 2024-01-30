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

if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User Id is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);


$sql = "SELECT * FROM scratch_cards WHERE user_id = $user_id ";
$db->sql($sql);
$scratch_cards = $db->getResult();

if (empty($scratch_cards)) {
    $response['success'] = false;
    $response['message'] = "User not found";
    print_r(json_encode($response));
    return false;
}

$sql = "SELECT * FROM scratch_cards WHERE user_id = $user_id AND status = 1";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if($num>=1){
    $response['success'] = true;
    $response['message'] = "Scratch Card listed successfully";
    $response['data'] = $res;
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "Not Found";
    print_r(json_encode($response));

}

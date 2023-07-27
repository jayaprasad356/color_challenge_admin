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
    $response['message'] = "User ID is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['balance'])) {
    $response['success'] = false;
    $response['message'] = "Balance is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);
$balance = $db->escapeString($_POST['balance']);

$sql = "UPDATE users SET balance= balance + $balance WHERE id=$user_id";
$db->sql($sql);
$response['success'] = true;
$response['message'] = "Balance Added";
print_r(json_encode($response));


?>
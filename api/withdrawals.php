<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Asia/Kolkata');


include_once('../includes/crud.php');

$db = new Database();
$db->connect();



if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User ID is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['amount'])) {
    $response['success'] = false;
    $response['message'] = "Amount is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['type'])) {
    $response['success'] = false;
    $response['message'] = "Type is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);
$type = $db->escapeString($_POST['type']);
$amount = $db->escapeString($_POST['amount']);
$datetime = date('Y-m-d H:i:s');

$sql = "SELECT * FROM settings WHERE id=1";
$db->sql($sql);
$result = $db->getResult();
$min_withdrawal=$result[0]['min_withdrawal'];
$sql = "SELECT * FROM users WHERE id='$user_id'";
$db->sql($sql);
$res = $db->getResult();
$balance=$res[0]['balance'];
if($amount >= $min_withdrawal && $amount <= $balance)
{
    $sql = "INSERT INTO withdrawals (`user_id`,`amount`,`type`,`datetime`) VALUES ('$user_id','$amount','$type','$datetime')";
    $db->sql($sql);
    $res = $db->getResult();
    $response['success'] = true;
    $response['message'] = "Withdrawal Requested Successfully";
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "Insufficient Balance";
    print_r(json_encode($response));
}

?>
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
if (empty($_POST['mobile'])) {
    $response['success'] = false;
    $response['message'] = "Mobile is Empty";
    print_r(json_encode($response));
    return false;
}
$mobile = $db->escapeString($_POST['mobile']);
$device_id = (isset($_POST['device_id']) && !empty($_POST['device_id'])) ? $db->escapeString($_POST['device_id']) : "";


$sql = "SELECT * FROM users WHERE mobile = '$mobile'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num != 1){
    $response['success'] = true;
    $response['user_registered'] = false;
    $response['message'] = "User Not Logged In";
    print_r(json_encode($response));
    return false;

}
$user_device_id = $res[0]['device_id'];
$status = $res[0]['status'];
if($user_device_id != '' && $user_device_id != $device_id){
    $response['success'] = false;
    $response['user_registered'] = false;
    $response['message'] = "This User is Already Logged with another device,you cannot login with different device";
    print_r(json_encode($response));
    return false;

}
if($status == 2){
    $response['success'] = false;
    $response['user_registered'] = false;
    $response['message'] = "You are Blocked";
    print_r(json_encode($response));
    return false;

}
$sql = "UPDATE users SET device_id='$device_id' WHERE id=" . $mobile;
$db->sql($sql);
$response['success'] = true;
$response['user_registered'] = true;
$response['message'] = "Logged In Successfully";
$response['data'] = $res;
print_r(json_encode($response));

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

if (empty($_POST['name'])) {
    $response['success'] = false;
    $response['message'] = "Name is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['mobile'])) {
    $response['success'] = false;
    $response['message'] = "Mobilenumber is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['device_id'])) {
    $response['success'] = false;
    $response['message'] = "Device Id is Empty";
    print_r(json_encode($response));
    return false;
}

$name = $db->escapeString($_POST['name']);
$mobile = $db->escapeString($_POST['mobile']);
$referred_by = (isset($_POST['referred_by']) && !empty($_POST['referred_by'])) ? $db->escapeString($_POST['referred_by']) : "";
$device_id = $db->escapeString($_POST['device_id']);

$sql = "SELECT id FROM users WHERE device_id='$device_id'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num >= 1) {
    $response['success'] = false;
    $response['message'] ="User Already Registered with this device kindly register with new device";
    print_r(json_encode($response));
    return false;
}

$sql = "SELECT * FROM users WHERE mobile='$mobile'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num >= 1) {
    $response['success'] = false;
    $response['message'] ="Mobile Number Already Exists";
    print_r(json_encode($response));
    return false;
} else {
    $datetime = date('Y-m-d H:i:s');
    $sql = "INSERT INTO users (`name`,`mobile`,`referred_by`,`device_id`,`last_updated`) VALUES ('$name','$mobile','$referred_by','$device_id','$datetime')";
    $db->sql($sql);
    $sql = "SELECT * FROM users WHERE mobile = '$mobile'";
    $db->sql($sql);
    $res = $db->getResult();
    $user_id = $res[0]['id'];

    $support_id = '';

    if (empty($referred_by)) {
        $refer_code = MAIN_REFER . $user_id;
    } else {
            $admincode = substr($referred_by, 0, -5);
            $sql = "SELECT refer_code FROM admin WHERE refer_code='$admincode'";
            $db->sql($sql);
            $result = $db->getResult();
            $num = $db->numRows($result);
            if($num>=1){
                $refer_code = substr($referred_by, 0, -5) . $user_id;
            }
            else{
                $refer_code = MAIN_REFER . $user_id;
            }
    }

    $sql_query = "UPDATE users SET refer_code='$refer_code' WHERE id =  $user_id";
    $db->sql($sql_query);

    $sql = "SELECT * FROM settings";
    $db->sql($sql);
    $setres = $db->getResult();

    $sql = "SELECT * FROM users WHERE mobile = '$mobile'";
    $db->sql($sql);
    $res = $db->getResult();

    $response['success'] = true;
    $response['message'] = "Successfully Registered";
    $response['data'] = $res;
    $response['settings'] = $setres;
    print_r(json_encode($response));
}
?>

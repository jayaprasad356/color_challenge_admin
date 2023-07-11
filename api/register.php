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


if (empty($_POST['mobile'])) {
    $response['success'] = false;
    $response['message'] = "Mobile is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['name'])) {
    $response['success'] = false;
    $response['message'] = "Name is Empty";
    print_r(json_encode($response));
    return false;
}
$mobile = $db->escapeString($_POST['mobile']);
$name = $db->escapeString($_POST['name']);
$datetime = date('Y-m-d H:i:s');
$referred_by = (isset($_POST['referred_by']) && !empty($_POST['referred_by'])) ? $db->escapeString($_POST['referred_by']) : "";
$device_id = (isset($_POST['device_id']) && !empty($_POST['device_id'])) ? $db->escapeString($_POST['device_id']) : "";


$sql = "SELECT device_id FROM users WHERE device_id = '$device_id'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num >= 1){
    $response['success'] = false;
    $response['message'] = "You are Already Registered with this device, please register with new device";
    print_r(json_encode($response));
    return false;
}

$sql = "SELECT * FROM users WHERE mobile = '$mobile'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num >= 1){
    $response['success'] = false;
    $response['message'] = "You are Already Registered";
    print_r(json_encode($response));
}
else{
    do {
        $random_number = mt_rand(10000,99999);
        $sql = "SELECT * FROM users WHERE refer_code = $random_number";
        $db->sql($sql);
        $res = $db->getResult();
        if(!$res) {
            break;
        }
    } while(1);

    $refer_code = $random_number;
    $sql = "SELECT * FROM settings WHERE id =1";
    $db->sql($sql);
    $result = $db->getResult();
    $coins=$result[0]['register_coins'];
    $refer_coins=$result[0]['refer_coins'];
    if(empty($referred_by)){
        

    }
    else{
        $sql = "SELECT * FROM users WHERE refer_code = $referred_by";
        $db->sql($sql);
        $ures= $db->getResult();
        $num = $db->numRows($res);
        if ($num == 1){
            $refer_coins = $ures[0]['refer_coins'];
        }
        $sql = "UPDATE users SET total_referrals = total_referrals + 1,coins = coins + $refer_coins WHERE refer_code = '$referred_by'";
        $db->sql($sql);
        
    }

    $currentdate = date('Y-m-d');
    $user_refer_coins = REFER_COINS;
    $min_withdrawal = MIN_WITHDRAWAL;

    $sql = "INSERT INTO users (`mobile`,`name`,`referred_by`,`upi`,`refer_code`,`coins`,`joined_date`,`datetime`,`refer_coins`,`min_withdrawal`,`device_id`) VALUES ('$mobile','$name','$referred_by','','$refer_code','$coins','$currentdate','$datetime',$user_refer_coins,$min_withdrawal,'$device_id')";
    $db->sql($sql);
   
    $sql = "SELECT * FROM users WHERE mobile = '$mobile'";
    $db->sql($sql);
    $res = $db->getResult();
    $response['success'] = true;
    $response['message'] = "User Registered Successfully";
    $response['data'] = $res;
    print_r(json_encode($response));


}



?>
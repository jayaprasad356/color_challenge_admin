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
    $response['message'] = " User Id is Empty";
    print_r(json_encode($response));
    return false;
}

if (empty($_POST['color_id'])) {
    $response['success'] = false;
    $response['message'] = "color_id is Empty";
    print_r(json_encode($response));
    return false;
}

if (empty($_POST['coins'])) {
    $response['success'] = false;
    $response['message'] = "Coins is Empty";
    print_r(json_encode($response));
    return false;
}
$user_id=$db->escapeString($_POST['user_id']);
$color_id = $db->escapeString($_POST['color_id']);
$coins = $db->escapeString($_POST['coins']);

if ($coins % 5 != 0 || $coins != intval($coins)) {
    $response['success'] = false;
    $response['message'] = "Coins must be a multiple of 5";
    print_r(json_encode($response));
    return false;
}


// Get the current date and time
$date = new DateTime('now');

// Round off to the nearest hour
$date->modify('+' . (60 - $date->format('i')) . ' minutes');
$date->setTime($date->format('H'), 0, 0);

// Format the date and time as a string
$date_string = $date->format('Y-m-d H:i:s');
$datetime = date('Y-m-d H:i:s');

$sql = "SELECT coins,balance,earn FROM users WHERE  id='$user_id'";
$db->sql($sql);
$res = $db->getResult();
$user_coins=$res[0]['coins'];
$earn=$res[0]['earn'];
$balance=$res[0]['balance'];


$sql = "SELECT * FROM settings";
$db->sql($sql);
$set = $db->getResult();
$challenge_status=$set[0]['challenge_status'];


if($challenge_status == 0){
    $response['success'] = false;
    $response['message'] = "Challenge is out of time now...Please try again after some time";
    print_r(json_encode($response));
    return false;
}

if($earn == 0 && $balance >= 50){
    $response['success'] = false;
    $response['message'] = "Please Withdrawal Your Amount then Challenge";
    print_r(json_encode($response));
    return false;
}

if($earn != 0 && $balance >= 250){
    $response['success'] = false;
    $response['message'] = "Please Withdrawal Your Amount then Challenge";
    print_r(json_encode($response));
    return false;
}

$sql = "SELECT id FROM challenges WHERE user_id = $user_id AND datetime = '$date_string'";
$db->sql($sql);
$cres = $db->getResult();
$cnum = $db->numRows($cres);
if($earn != 0 && $cnum >= 3){
    $response['success'] = false;
    $response['message'] = "You Exceed Challenge Limit so,try another challenge";
    print_r(json_encode($response));
    return false;
}
if ($coins <= $user_coins) {
    $sql = "UPDATE users SET coins =coins - $coins  WHERE id=$user_id";
    $db->sql($sql);
    $sql = "INSERT INTO challenges (`user_id`,`color_id`,`coins`,`status`,`datetime`)  VALUES ('$user_id','$color_id','$coins',0,'$date_string')";
    $db->sql($sql);
    $res = $db->getResult();
    $response['success'] = true;
    $response['message'] = "Color Challenged Successfully";
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "Insufficient Coins";
    print_r(json_encode($response));

}



?>

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
$datetime = date('Y-m-d H:i:s');


if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User ID is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['coins'])) {
    $response['success'] = false;
    $response['message'] = "Coins is Empty";
    print_r(json_encode($response));
    return false;
}
$enable = 0;
if ($enable == 0) {
    $response['success'] = false;
    $response['message'] = "Task is Disabled Currently";
    print_r(json_encode($response));
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);
$coins = $db->escapeString($_POST['coins']);

$sql = "SELECT coins FROM users WHERE id = '$user_id'";
$db->sql($sql);
$res = $db->getResult();
$balance_coins = $res[0]['coins'];
if($balance_coins >= $coins){
    $type = 'find_color_debit';
    $sql_query = "INSERT INTO transactions (user_id,type,coins,datetime)VALUES('$user_id','$type','$coins','$datetime')";
    $db->sql($sql_query);
    $sql = "UPDATE users SET coins= coins - $coins WHERE id=$user_id";
    $db->sql($sql);
    $sql = "SELECT coins FROM users WHERE id = '$user_id'";
    $db->sql($sql);
    $res = $db->getResult();
    $coins = $res[0]['coins'];
    $response['success'] = true;
    $response['coins'] = $coins;
    $response['message'] = "You have ".$coins." coins balance";
    print_r(json_encode($response));
}
else{

    $response['success'] = false;
    $response['message'] = "You have not suffcient coins";
    print_r(json_encode($response));
}



?>
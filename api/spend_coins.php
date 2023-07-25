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
if (empty($_POST['coins'])) {
    $response['success'] = false;
    $response['message'] = "Coins is Empty";
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
    $sql = "UPDATE users SET coins= coins - $coins WHERE id=$user_id";
    $db->sql($sql);
    $sql = "SELECT coins FROM users WHERE id = '$user_id'";
    $db->sql($sql);
    $res = $db->getResult();
    $response['success'] = true;
    $response['message'] = "You have ".$coins." coins balance";
    print_r(json_encode($response));
}
else{

    $response['success'] = false;
    $response['message'] = "You have not suffcient coins";
    print_r(json_encode($response));
}



?>
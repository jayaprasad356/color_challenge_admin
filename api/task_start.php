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
$datetime = date('Y-m-d H:i:s');
$currentdate = date('Y-m-d');
$user_id = $db->escapeString($_POST['user_id']);

$sql = "SELECT * FROM `users_task` WHERE user_id=$user_id AND DATE(datetime) = '$currentdate'";
$db->sql($sql);
$result = $db->getResult();
$num = $db->numRows($res);
if ($num >= 2){
    $response['success'] = false;
    $response['message'] = "You Reached Limit";
    print_r(json_encode($response));
    return false;

}

$sql = "SELECT * FROM `tasks` WHERE id=1";
$db->sql($sql);
$result = $db->getResult();
$start_coins=$result[0]['start_coins'];

$sql = "SELECT coins FROM users WHERE id = '$user_id'";
$db->sql($sql);
$res = $db->getResult();
$coins=$res[0]['coins'];
if($coins >= $start_coins ) {

    $sql = "UPDATE users SET coins= coins - $start_coins WHERE id=$user_id";
    $db->sql($sql);

    $sql_query = "INSERT INTO users_task (user_id,task_id,datetime)VALUES($user_id,1,'$datetime')";
    $db->sql($sql_query);


    $response['success'] = true;
    $response['message'] = "Task Started";
    print_r(json_encode($response));

}
else {
    $response['success'] = false;
    $response['message'] = "You have not sufficient coins";
    print_r(json_encode($response));
    return false;

}




?>
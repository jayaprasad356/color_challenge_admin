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

$user_id = $db->escapeString($_POST['user_id']);
$coins = $db->escapeString($_POST['coins']);
$datetime = date('Y-m-d H:i:s');
$date = date('Y-m-d');



$sql = "SELECT id FROM daily_bonus WHERE user_id = $user_id AND DATE(datetime) = '$date'";
$db->sql($sql);
$result = $db->getResult();
$num = $db->numRows($result);
if ($num >= 1){
    $response['success'] = false;
    $response['message'] = "Sorry,You Claimed Today Bonus";
    print_r(json_encode($response));

}
else{
    $sql = "INSERT INTO daily_bonus (`user_id`,`coins`,`datetime`) VALUES ($user_id,$coins,'$datetime')";
    $db->sql($sql);

    $sql = "UPDATE users SET coins= coins + $coins WHERE id=$user_id";
    $db->sql($sql);
    $sql = "SELECT coins FROM users WHERE id = '$user_id'";
    $db->sql($sql);
    $res = $db->getResult();
    $response['success'] = true;
    $response['message'] = "Bonus Added Successfully";
    print_r(json_encode($response));

}

?>
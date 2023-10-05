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
    $response['message'] = "User Id is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['ads'])) {
    $response['success'] = false;
    $response['message'] = "Ads is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['sync_unique_id'])) {
    $response['success'] = false;
    $response['message'] = "Sync Unique Id is Empty";
    print_r(json_encode($response));
    return false;
}


$user_id = $db->escapeString($_POST['user_id']);
$ads = $db->escapeString($_POST['ads']);
$sync_unique_id = $db->escapeString($_POST['sync_unique_id']);

$datetime = date('Y-m-d H:i:s');
$type = 'watch_ads';
$ad_cost = $ads * 0.125;

$sql = "INSERT INTO transactions (`user_id`,`ads`,`amount`,`datetime`,`type`,`sync_unique_id`)VALUES('$user_id','$ads','$ad_cost','$datetime','$type','$sync_unique_id')";
$db->sql($sql);


$sql = "UPDATE users SET today_ads = today_ads + $ads,total_ads = total_ads + $ads,balance = balance + $ad_cost WHERE id=" . $user_id;
$db->sql($sql);

$response['success'] = true;
$response['message'] = "Transaction updated successfully";
echo json_encode($response);


?>
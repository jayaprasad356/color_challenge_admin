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

$t_sync_unique_id = '';
$sql = "SELECT sync_unique_id FROM transactions WHERE user_id = $user_id AND type = '$type' ORDER BY datetime DESC LIMIT 1 ";
$db->sql($sql);
$tres = $db->getResult();
$num = $db->numRows($tres);
if ($num >= 1) {
    $t_sync_unique_id = $tres[0]['sync_unique_id'];


}

if(($sync_unique_id != $t_sync_unique_id) || $t_sync_unique_id == ''){
    $sql = "INSERT INTO transactions (`user_id`,`codes`,`amount`,`datetime`,`type`,`sync_unique_id`)VALUES('$user_id','$codes','$amount','$datetime','$type','$sync_unique_id')";
    $db->sql($sql);
    $res = $db->getResult();

    $sql = "UPDATE `users` SET  `today_codes` = today_codes + $codes,`total_codes` = total_codes + $codes,`earn` = earn + $amount,`balance` = balance + $amount,`last_updated` = '$datetime' WHERE `id` = $user_id";
    $db->sql($sql);



}


$sql = "INSERT INTO transactions (`user_id`,`ads`,`amount`,`datetime`,`type`,`sync_unique_id`)VALUES('$user_id','$ads','$ad_cost','$datetime','$type','$sync_unique_id')";
$db->sql($sql);


$sql = "UPDATE users SET today_ads = today_ads + $ads,total_ads = total_ads + $ads,balance = balance + $ad_cost WHERE id=" . $user_id;
$db->sql($sql);

$sql = "SELECT * FROM users WHERE id = $user_id ";
$db->sql($sql);
$res = $db->getResult();

$response['success'] = true;
$response['message'] = "Transaction updated successfully";
$response['data'] = $res;
echo json_encode($response);


?>
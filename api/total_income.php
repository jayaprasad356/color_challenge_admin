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
    $response['message'] = "User Id is Empty";
    echo json_encode($response);
    return;
}

$user_id = $db->escapeString($_POST['user_id']);

$sql = "SELECT * FROM users WHERE id = $user_id";
$db->sql($sql);
$res= $db->getResult();
$num = $db->numRows($res);

if (empty($res)) {
    $response['success'] = false;
    $response['message'] = "User not found";
    echo json_encode($response);
    return;
}

$today_ads_status = $res[0]['today_ads_status'];

if ($today_ads_status != 1) {
    $response['success'] = false;
    $response['message'] = "You already claimed Today's Income";
    echo json_encode($response);
    return;
}

if ($num >= 1){
    $ID = $user[0]['id'];
    $total_referrals = $user[0]['total_referrals'];

    if ($total_referrals >= 5) {
        $ads = 900;
        $amount = 75;
    } else {
        $ads = 600;
        $amount = 50;
    }

    $datetime = date('Y-m-d H:i:s');
    $type = 'ad_bonus';

    $sql = "INSERT INTO transactions (`user_id`, `ads`, `amount`, `datetime`, `type`) VALUES ('$ID', '$ads', '$amount', '$datetime', '$type')";
    $db->sql($sql);
    $res = $db->getResult();

    $sql = "UPDATE `users` SET `today_ads` = today_ads + $ads, `total_ads` = total_ads + $ads, `earn` = earn + $amount, `balance` = balance + $amount, `today_ads_status` = 0 WHERE `id` = $ID";
    $db->sql($sql);
    $result = $db->getResult();

    $response['success'] = true;
    $response['message'] = "User's income added successfully";
}

echo json_encode($response);
?>

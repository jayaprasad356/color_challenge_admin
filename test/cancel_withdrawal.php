<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Asia/Kolkata');
include_once('../includes/custom-functions.php');
$fn = new custom_functions;
include_once('../includes/crud.php');

$db = new Database();
$db->connect();
$currentdate = date('Y-m-d');
$datetime = date('Y-m-d H:i:s');
$sql = "SELECT w.id AS w_id,w.amount AS amount,u.id AS user_id FROM `withdrawals`w,`users`u WHERE w.user_id = u.id AND DATE(datetime) = '2023-12-01'  AND w.status = 0  AND  u.plan = 'A1' AND u.status = 1 AND last_today_ads < 1200 AND today_ads < 1200 AND total_referrals < 5  AND u.worked_days > 1 ORDER BY `u`.`worked_days` DESC;";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num >= 1) {
    foreach ($res as $row) {
        $w_id = $row['w_id'];
        $amount = $row['amount'];
        $user_id = $row['user_id'];
        $sql = "UPDATE withdrawals SET status=2 WHERE id = $w_id";
        $db->sql($sql);
        $sql = "UPDATE users SET balance= balance + $amount WHERE id = $user_id";
        $db->sql($sql);

    }
    $response['success'] = true;
    $response['message'] = "balance added";
    
    print_r(json_encode($response));

}else{
    $response['success'] = false;
    $response['message'] = "No Results Found";
    print_r(json_encode($response));

}




?>
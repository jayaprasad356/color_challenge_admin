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
$sql = "SELECT w.id AS w_id,u.worked_days AS worked_days,u.total_referrals AS total_referrals FROM `withdrawals`w,`users`u WHERE w.user_id = u.id AND w.status = 2 AND u.total_referrals < 2 AND u.worked_days > 5 AND u.plan = 'A1' AND u.today_ads < 1200 AND DATE(w.datetime) = '2023-11-24'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num >= 1) {

    foreach ($res as $row) {
        $w_id = $row['w_id'];
        $worked_days = $row['worked_days'];
        $total_referrals = $row['total_referrals'];
        if(($worked_days > 5 && $total_referrals < 1) || ($worked_days > 11 && $total_referrals < 2)){
            $sql = "UPDATE withdrawals SET status=2 WHERE id = $w_id";
            $db->sql($sql);
            $sql = "SELECT * FROM `withdrawals` WHERE id = $w_id ";
            $db->sql($sql);
            $res = $db->getResult();
            $user_id= $res[0]['user_id'];
            $amount= $res[0]['amount'];
    
            $sql = "UPDATE users SET balance= balance + $amount WHERE id = $user_id";
            $db->sql($sql);

        }



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
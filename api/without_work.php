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
$currentdate = date('Y-m-d');

$sql = "SELECT * FROM leaves WHERE date = '$currentdate'";
$db->sql($sql);
$resl = $db->getResult();
$lnum = $db->numRows($resl);
$enable = 1;
if ($lnum >= 1) {

    return false;

}
$sql = "SELECT * FROM users WHERE status = 1 AND without_work = 1 AND plan = 'A1U' AND total_ads < 36000 AND whatsapp_status = 0";
$db->sql($sql);
$res= $db->getResult();
$num = $db->numRows($res);
if ($num >= 1){
    
    foreach ($res as $row) {
        $ID = $row['id'];
        $total_referrals = $row['total_referrals'];
        $total_ads = $row['total_ads'];
        $bal_ads = 36000 - $total_ads;

        if($total_referrals >= 15){
            $ads = 6000;
            $amount = 500;

        }
        else if($total_referrals >= 10){
            $ads = 1800;
            $amount = 150;

        }
        else if($total_referrals >= 5){
            $ads = 900;
            $amount = 75;

        }else{
            $ads = 600;
            $amount = 50;
        }

        if($bal_ads < 6000){
            $ads = $bal_ads;
            $amount = $bal_ads * 0.085;
            
        }
        $datetime = date('Y-m-d H:i:s');
        $type = 'ad_bonus';


        $sql = "INSERT INTO transactions (`user_id`,`ads`,`amount`,`datetime`,`type`)VALUES('$ID','$ads','$amount','$datetime','$type')";
        $db->sql($sql);
        $res = $db->getResult();
    
        $sql = "UPDATE `users` SET  `today_ads` = today_ads + $ads,`total_ads` = total_ads + $ads,`earn` = earn + $amount,`balance` = balance + $amount WHERE `id` = $ID";
        $db->sql($sql);
        $result = $db->getResult();

    }
    $response['success'] = true;
    $response['message'] = "Ad bonus  added Successfully";
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "Users Not found";
    print_r(json_encode($response));

}
?>
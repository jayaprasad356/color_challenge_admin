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


$sql = "SELECT * FROM users WHERE status = 1 AND without_work = 1 AND plan = 'A1U' ";
$db->sql($sql);
$res= $db->getResult();
$num = $db->numRows($res);
if ($num >= 1){
    
    foreach ($res as $row) {
        $ID = $row['id'];
        $datetime = date('Y-m-d H:i:s');
        $type = 'ad_bonus';
        $ads = 600;
        $amount = 50;

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
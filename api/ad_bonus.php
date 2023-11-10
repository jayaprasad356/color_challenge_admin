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


$sql = "SELECT referred_by FROM `users` WHERE joined_date = '2023-11-03' AND status = 1 AND  plan = 'A1' AND referred_by != ''";
$db->sql($sql);
$res= $db->getResult();
$num = $db->numRows($res);
if ($num >= 1){
    
    foreach ($res as $row) {
        $referred_by = $row['referred_by'];
        $sql = "SELECT id FROM users WHERE refer_code = '$referred_by'";
        $db->sql($sql);
        $res= $db->getResult();
        $num = $db->numRows($res);
        if ($num == 1){
            $ID = $res[0]['id'];
            $type = 'ad_bonus';
            $ads = 300;
            $per_code_cost = 0.125;
            $amount = $ads * $per_code_cost;

            $sql = "INSERT INTO transactions (`user_id`,`ads`,`amount`,`datetime`,`type`)VALUES('$ID','$ads','$amount','$datetime','$type')";
            $db->sql($sql);
            $res = $db->getResult();
        
            $sql = "UPDATE `users` SET  `today_ads` = today_ads - $ads,`total_ads` = total_ads - $ads,`earn` = earn - $amount,`balance` = balance - $amount WHERE `id` = $ID";
            $db->sql($sql);
        }

    }
    $response['success'] = true;
    $response['message'] = "Ad bonus   Successfully";
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "Users Not found";
    print_r(json_encode($response));

}
?>
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

$currentdate = date('Y-m-d');
$user_id = $db->escapeString($_POST['user_id']);
$ads = $db->escapeString($_POST['ads']);
$sync_unique_id = $db->escapeString($_POST['sync_unique_id']);
$datetime = date('Y-m-d H:i:s');
$type = 'watch_ads';
$ad_cost = $ads * 0.125;
$t_sync_unique_id = '';
$sql = "SELECT * FROM settings";
$db->sql($sql);
$settings = $db->getResult();
$watch_ad_status = $settings[0]['watch_ad_status'];

$sql = "SELECT * FROM leaves WHERE date = '$date'";
$db->sql($sql);
$resl = $db->getResult();
$lnum = $db->numRows($resl);
$enable = 1;
if ($lnum >= 1) {
    $enable = 0;

}
if ($enable == 0) {
    $response['success'] = false;
    $response['message'] = "Holiday, Come Back Tomorrow";
    print_r(json_encode($response));
    return false;
}

if ( $watch_ad_status == 0) {
    $response['success'] = false;
    $response['message'] = "Watch Ad is disable right now";
    print_r(json_encode($response));
    return false;
} 

$sql = "SELECT COUNT(id) AS count  FROM transactions WHERE user_id = $user_id AND DATE(datetime) = '$currentdate' AND type = '$type'";
$db->sql($sql);
$tres = $db->getResult();
$t_count = $tres[0]['count'];
if ($t_count >= 10) {
    $response['success'] = false;
    $response['message'] = "You Reached Daily Sync Limit";
    print_r(json_encode($response));
    return false;
}

$sql = "SELECT sync_unique_id,datetime FROM transactions WHERE user_id = $user_id AND type = '$type' ORDER BY datetime DESC LIMIT 1 ";
$db->sql($sql);
$tres = $db->getResult();
$num = $db->numRows($tres);
$code_min_sync_time = 45;
if ($num >= 1) {
    $t_sync_unique_id = $tres[0]['sync_unique_id'];
    $dt1 = $tres[0]['datetime'];
    $date1 = new DateTime($dt1);
    $date2 = new DateTime($datetime);

    $diff = $date1->diff($date2);
    $totalMinutes = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
    $dfi = $code_min_sync_time - $totalMinutes;
    if($totalMinutes < $code_min_sync_time && $user_id != 8469 && $user_id != 8472 ){
        $response['success'] = false;
        $response['message'] = "Cannot Sync Right Now, Try again after ".$dfi." mins";
        print_r(json_encode($response));
        return false;

    }


}

if($ads == '120'){
    if(($sync_unique_id != $t_sync_unique_id) || $t_sync_unique_id == ''){
        $sql = "INSERT INTO transactions (`user_id`,`ads`,`amount`,`datetime`,`type`,`sync_unique_id`)VALUES('$user_id','$ads','$ad_cost','$datetime','$type','$sync_unique_id')";
        $db->sql($sql);

        $sql = "UPDATE users SET today_ads = today_ads + $ads,total_ads = total_ads + $ads,balance = balance + $ad_cost WHERE id=" . $user_id;
        $db->sql($sql);
        $message = "Sync Updated successfully";
    
    
    
    }else{
        $message = "You cannot Sync without watching ads";


    }
    

}else{
    $message = "You cannot sync above 120 ads";

}




$sql = "SELECT * FROM users WHERE id = $user_id ";
$db->sql($sql);
$res = $db->getResult();

$response['success'] = true;
$response['message'] = $message;
$response['data'] = $res;
echo json_encode($response);


?>
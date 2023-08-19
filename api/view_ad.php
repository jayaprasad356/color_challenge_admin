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
    $response['message'] = " User Id is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);
$datetime = date('Y-m-d H:i:s');

$sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num == 1) {
    $status = $res[0]['status'];
    $total_ads = $res[0]['total_ads'];
    $today_ads = $res[0]['today_ads'];
    $today_ads = $res[0]['today_ads'];
    $enable = 1;
    if ($status == 0 && $enable == 0) {
        $response['success'] = false;
        $response['message'] = "Currently Disabled";
        print_r(json_encode($response));
        return false;
    }
    if ($status == 0 && $total_ads >= 4) {
        $response['success'] = false;
        $response['message'] = "Your Free Trial Completed,Purchase Plan and Continue";
        print_r(json_encode($response));
        return false;
    }
    if ($status == 1 && $today_ads >= 10) {
        $response['success'] = false;
        $response['message'] = "You Completed today ads";
        print_r(json_encode($response));
        return false;
    }
    $time_left = 0;
    $sql = "SELECT * FROM ads_trans WHERE user_id = $user_id ORDER BY id DESC LIMIT 1";
    $db->sql($sql);
    $res = $db->getResult();
    $num = $db->numRows($res);
    if ($num >= 1){
        
        $end_time = $res[0]['end_time'];

        $start_datetime = new DateTime($datetime);
        $end_datetime = new DateTime($end_time);
        $diff = $start_datetime->diff($end_datetime);
        
    
        if($datetime < $end_time){
            $time_left = $diff->s + ($diff->i * 60) + ($diff->h * 3600) + ($diff->days * 86400);
            $response['success'] = false;
            $response['message'] = "Pls wait for next Ad....";
            $response['time_left'] = $time_left;
            print_r(json_encode($response));
            return false;


        }
        

    }

    $join = "";
    if($status == 1){
        $join = ",premium_wallet = premium_wallet + 12";
    }

    $endtime = date('Y-m-d H:i:s', strtotime($datetime) + 60);
    $sql = "INSERT INTO ads_trans (`user_id`,`ad_count`,`start_time`,`end_time`) VALUES ($user_id,1,'$datetime','$endtime')";
    $db->sql($sql);

    $sql = "UPDATE users SET today_ads = today_ads + 1,total_ads = total_ads + 1,basic_wallet = basic_wallet + 3 $join WHERE id=" . $user_id;
    $db->sql($sql);
    $sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
    $db->sql($sql);
    $res = $db->getResult();
    $response['success'] = true;
    $response['message'] = "Ad is Started";
    $response['time_left'] = $time_left;
    $response['data'] = $res;

}
else{
    $response['success'] = false;
    $response['message'] = "User Not Found";
}

print_r(json_encode($response));




?>

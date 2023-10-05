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
$date = date('Y-m-d');
$datetime = date('Y-m-d H:i:s');
$dayOfWeek = date('w', strtotime($datetime));
$sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
$sql = "SELECT * FROM leaves WHERE date = '$date'";
$db->sql($sql);
$resl = $db->getResult();
$lnum = $db->numRows($resl);
$enable = 1;
if ($lnum >= 1) {
    $enable = 0;

}
if ($num == 1) {
    $status = $res[0]['status'];
    $plan = $res[0]['plan'];
    $total_ads = $res[0]['total_ads'];
    $today_ads = $res[0]['today_ads'];
    $total_referrals = $res[0]['total_referrals'];

    $ads_limit = 10;

    if($user_id == 4401 || $user_id == 5061 || $user_id == 4407 || $user_id == 4713){
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
                $time_left = $diff->s + ($diff->i * 27) + ($diff->h * 3600) + ($diff->days * 86400);
                $response['success'] = false;
                $response['message'] = "Pls wait for next Ad....";
                $response['time_left'] = $time_left;
                print_r(json_encode($response));
                return false;
    
    
            }
            
    
        }
        $ad_cost = 0.125;
        $endtime = date('Y-m-d H:i:s', strtotime($datetime) + 27);
        $sql = "INSERT INTO ads_trans (`user_id`,`ad_count`,`start_time`,`end_time`) VALUES ($user_id,1,'$datetime','$endtime')";
        $db->sql($sql);
    
        $sql = "UPDATE users SET today_ads = today_ads + 1,total_ads = total_ads + 1,basic_wallet = basic_wallet + $ad_cost  WHERE id=" . $user_id;
        $db->sql($sql);
        $sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
        $db->sql($sql);
        $res = $db->getResult();
        $response['success'] = true;
        $response['message'] = "Ad is Started";
        $response['time_left'] = $time_left;
        $response['data'] = $res;
    

    }else{
        if ($enable == 0 && $status == 1) {
            $response['success'] = false;
            $response['message'] = "Holiday,Come Back Tomorrow";
            print_r(json_encode($response));
            return false;
        } 
        // if ($status == 0) {
        //     $response['success'] = false;
        //     $response['message'] = "Your Free Trial Completed,Purchase Plan and Continue";
        //     print_r(json_encode($response));
        //     return false;
        // }
        if ($today_ads >= $ads_limit) {
            $response['success'] = false;
            $response['message'] = "You Completed today ads";
            print_r(json_encode($response));
            return false;
        }
        if ($status == 1 && $total_ads >= 3600 && $plan == 'A2') {
            $response['success'] = false;
            $response['message'] = "You Completed total ads";
            print_r(json_encode($response));
            return false;
        }
        if ($status == 1 && $total_ads >= 1400 && $plan == 'A1') {
            $response['success'] = false;
            $response['message'] = "Expired";
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
        if($status == 1 &&  $plan == 'A1'){
            $join = ",premium_wallet = premium_wallet + 12";
        }
        else if($status == 1 &&  $plan == 'A2'){
            $join = ",premium_wallet = premium_wallet + 18";
    
        }
    
        if($status == 0){
            $ad_cost = 0.20;
    
        }else{
            if($plan == 'A1'){
                $ad_cost = 3;
        
            }else{
                $ad_cost = 2;
        
        
            }
    
    
        }
    
    
    
        $endtime = date('Y-m-d H:i:s', strtotime($datetime) + 60);
        $sql = "INSERT INTO ads_trans (`user_id`,`ad_count`,`start_time`,`end_time`) VALUES ($user_id,1,'$datetime','$endtime')";
        $db->sql($sql);
    
        $sql = "UPDATE users SET today_ads = today_ads + 1,total_ads = total_ads + 1,basic_wallet = basic_wallet + $ad_cost $join WHERE id=" . $user_id;
        $db->sql($sql);
        $sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
        $db->sql($sql);
        $res = $db->getResult();
        $response['success'] = true;
        $response['message'] = "Ad is Started";
        $response['time_left'] = $time_left;
        $response['data'] = $res;

    }



}
else{
    $response['success'] = false;
    $response['message'] = "User Not Found";
}

print_r(json_encode($response));




?>

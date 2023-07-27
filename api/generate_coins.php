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
    $response['message'] = "User ID is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['type'])) {
    $response['success'] = false;
    $response['message'] = "Type is Empty";
    print_r(json_encode($response));
    return false;
}


$user_id = $db->escapeString($_POST['user_id']);
$type = $db->escapeString($_POST['type']);


$sql = "SELECT * FROM users WHERE id = $user_id";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num == 1){
    $datetime = date('Y-m-d H:i:s');
   
    $coin_count = 1;

    if($type == 'generate'){
        // if (!isTimeBetweenMorningAndEvening($datetime)) {
        //     $response['success'] = false;
        //     $response['message'] = "Pls generate coin between morning 8:00 AM and evening 6:00 PM.";
        //     print_r(json_encode($response));
        //     return false;
        // }
    
        $sql = "SELECT * FROM generate_coins WHERE user_id = $user_id ORDER BY id DESC LIMIT 1";
        $db->sql($sql);
        $res = $db->getResult();
        $num = $db->numRows($res);
        if ($num >= 1){
            
            $end_time = $res[0]['end_time'];
        
            if($datetime > $end_time){
                $coin_count = $res[0]['coin_count'] + 1;


                $genertecode = true;
    
            }else{
                $response['success'] = false;
                $response['message'] = "Pls wait while Coin Generating...";
                print_r(json_encode($response));
                return false;
    
            }
            
    
        }else{
            $coin_count = 1;
            $genertecode = true;
    
        }
        $endtime = date('Y-m-d H:i:s', strtotime($datetime) + 600);
    
    
        $sql = "INSERT INTO generate_coins (`user_id`,`coin_count`,`start_time`,`end_time`) VALUES ($user_id,$coin_count,'$datetime','$endtime')";
        $db->sql($sql);
    
    
        $time_left = 600;
        $response['success'] = true;
        $response['message'] = "Coin Generate Started";
        $response['coin_count'] = $coin_count;
        $response['max_coin'] = 100;
        $response['time_left'] = $time_left;
        $response['refer_amount'] = 200;
        print_r(json_encode($response));
    
    }
    else{
        $sql = "SELECT * FROM generate_coins WHERE user_id = $user_id ORDER BY id DESC LIMIT 1";
        $db->sql($sql);
        $res = $db->getResult();
        $num = $db->numRows($res);
        if ($num >= 1){
            $coin_count = $res[0]['coin_count'];
            $end_time = $res[0]['end_time'];

            $start_datetime = new DateTime($datetime);
            $end_datetime = new DateTime($end_time);
            $interval = $start_datetime->diff($end_datetime);
            $seconds_difference = $interval->s;
            if($datetime > $end_time){
                $time_left = 600;

            }else{
                $time_left = $interval->s + $interval->i * 60 + $interval->h * 3600;
            }
            

            
        }else{
            $coin_count = 0;
            $time_left = 600;
            
        }

        $response['success'] = true;
        $response['message'] = "Coin Status";
        $response['coin_count'] = $coin_count;
        $response['max_coin'] = 100;
        $response['time_left'] = $time_left;
        $response['refer_amount'] = 200;
        print_r(json_encode($response));

    }


}
else{
    $response['success'] = false;
    $response['message'] = "User Not Exist";
    print_r(json_encode($response));

}
function isTimeBetweenMorningAndEvening($dateTimeStr) {
    $morningStart = new DateTime('08:00:00');
    $eveningEnd = new DateTime('18:00:00');

    // Convert the input datetime string to a DateTime object
    $dateTime = new DateTime($dateTimeStr);

    // Get the time component of the DateTime object
    $timeOfDay = $dateTime->format('H:i:s');

    // Compare with the morning and evening time ranges
    return ($timeOfDay >= $morningStart->format('H:i:s')) && ($timeOfDay <= $eveningEnd->format('H:i:s'));
}

?>
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

if (empty($_POST['name'])) {
    $response['success'] = false;
    $response['message'] = "Name is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['mobile'])) {
    $response['success'] = false;
    $response['message'] = "Mobilenumber is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['device_id'])) {
    $response['success'] = false;
    $response['message'] = "Device Id is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['age'])) {
    $response['success'] = false;
    $response['message'] = "Age is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['gender'])) {
    $response['success'] = false;
    $response['message'] = "Gender is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['city'])) {
    $response['success'] = false;
    $response['message'] = "City is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['support_lan'])) {
    $response['success'] = false;
    $response['message'] = "Support Languages is Empty";
    print_r(json_encode($response));
    return false;
}

if (empty($_POST['email'])) {
    $response['success'] = false;
    $response['message'] = "Email is Empty";
    print_r(json_encode($response));
    return false;
}



$name = $db->escapeString($_POST['name']);
$mobile = $db->escapeString($_POST['mobile']);
$referred_by = (isset($_POST['referred_by']) && !empty($_POST['referred_by'])) ? $db->escapeString($_POST['referred_by']) : "";
$device_id = $db->escapeString($_POST['device_id']);
$age = $db->escapeString($_POST['age']);
$gender = $db->escapeString($_POST['gender']);
$city = $db->escapeString($_POST['city']);
$support_lan = $db->escapeString($_POST['support_lan']);
$deaf = (isset($_POST['deaf']) && !empty($_POST['deaf'])) ? $db->escapeString($_POST['deaf']) : 0;
$email = $db->escapeString($_POST['email']);

// $sql = "SELECT id FROM users WHERE device_id='$device_id'";
// $db->sql($sql);
// $res = $db->getResult();
// $num = $db->numRows($res);
// if ($num >= 1) {
//     $response['success'] = false;
//     $response['message'] ="User Already Registered with this device kindly register with new device";
//     print_r(json_encode($response));
//     return false;
// }

$sql = "SELECT * FROM users WHERE mobile='$mobile'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num >= 1) {
    $response['success'] = false;
    $response['message'] ="Mobile Number Already Exists";
    print_r(json_encode($response));
    return false;
} else {
    $datetime = date('Y-m-d H:i:s');
    $currentdate = date('Y-m-d');
    $balance = 0;

    $min_withdrawal = MIN_WITHDRAWAL;

    if($referred_by == 'ALAGUKUTTY'){
        $balance = 15;
    }

    $sql = "INSERT INTO users (`mobile`,`name`,`referred_by`,`account_num`,`holder_name`,`bank`,`branch`,`ifsc`,`joined_date`,`registered_date`,`min_withdrawal`,`device_id`,`age`,`city`,`gender`,`support_lan`,`deaf`,`email`,`balance`) VALUES ('$mobile','$name','$referred_by','','','','','','$currentdate','$datetime',$min_withdrawal,'$device_id','$age','$city','$gender','$support_lan',$deaf,'$email',$balance)";
    $db->sql($sql);
    $sql = "SELECT * FROM users WHERE mobile = '$mobile'";
    $db->sql($sql);
    $res = $db->getResult();
    $user_id = $res[0]['id'];

    $support_id = '';

    $branch_id = '1';
    if (empty($referred_by)) {
        $refer_code = MAIN_REFER . $user_id;
    } else {
        if (strlen($referred_by) < 3) {
            $refer_code = MAIN_REFER . $user_id;

        }
        else{
            $refershot = substr($referred_by, 0, 3);
            $sql = "SELECT short_code,id FROM branches WHERE short_code = '$refershot'";
            $db->sql($sql);
            $ares = $db->getResult();
            $num = $db->numRows($ares);
            if ($num >= 1) {
                $refer_code_db = $ares[0]['short_code'];
                $refer_code = $refer_code_db . $user_id;
                $branch_id = $ares[0]['id'];

            }else{
                $refer_code = MAIN_REFER . $user_id;

            }

            $sql = "SELECT support_id FROM users WHERE refer_code = '$referred_by'";
            $db->sql($sql);
            $refres = $db->getResult();
            $num = $db->numRows($refres);
            if ($num == 1 && $referred_by != '') {
                $support_id = $refres[0]['support_id'];

            }

            
        }
    }

    $sql = "SELECT id FROM `users` WHERE status = 1 AND refer_code = '$referred_by'";
    $db->sql($sql);
    $refres = $db->getResult();
    $num = $db->numRows($refres);
    $unknown = 1;
    if ($num >= 1){
        $r_id = $refres[0]['id'];
        $unknown = 0;
        $type = 'team_bonus';
        $amount = 5;
        $sql = "INSERT INTO transactions (`user_id`,`amount`,`datetime`,`type`)VALUES('$r_id','$amount','$datetime','$type')";
        $db->sql($sql);
        $res = $db->getResult();
    
        $sql = "UPDATE `users` SET  `earn` = earn + $amount,`balance` = balance + $amount WHERE `id` = $r_id";
        $db->sql($sql);

    }
    $sql_query = "UPDATE users SET refer_code='$refer_code',branch_id = $branch_id,support_id = 1,unknown = $unknown WHERE id =  $user_id";
    $db->sql($sql_query);


    $sql = "SELECT * FROM settings";
    $db->sql($sql);
    $setres = $db->getResult();

    $sql = "SELECT * FROM users WHERE mobile = '$mobile'";
    $db->sql($sql);
    $res = $db->getResult();

    $response['success'] = true;
    $response['message'] = "Successfully Registered";
    $response['data'] = $res;
    $response['settings'] = $setres;
    print_r(json_encode($response));
}
?>

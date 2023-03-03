<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include_once('../includes/crud.php');

$db = new Database();
$db->connect();



$mobile = (isset($_POST['mobile']) && !empty($_POST['mobile'])) ? $db->escapeString($_POST['mobile']) : "";
$email = (isset($_POST['email']) && !empty($_POST['email'])) ? $db->escapeString($_POST['email']) : "";
$referred_by = (isset($_POST['referred_by']) && !empty($_POST['referred_by'])) ? $db->escapeString($_POST['referred_by']) : "";
$sql = "SELECT * FROM users WHERE mobile = '$mobile' OR email='$email'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num >= 1){
    $response['success'] = false;
    $response['message'] = "You are Already Registered";
    print_r(json_encode($response));
}
else{
    do {
        $random_number = mt_rand(10000,99999);
        $sql = "SELECT * FROM users WHERE refer_code = $random_number";
        $db->sql($sql);
        $res = $db->getResult();
        if(!$res) {
            break;
        }
    } while(1);
    if(empty($referred_by)){
        $refer_code = MAIN_REFER . $random_number;

    }
    else{
        $admincode = substr($referred_by, 0, -5);
        $sql = "SELECT refer_code FROM admin WHERE refer_code='$admincode' OR refer_code='$referred_by'";
        $db->sql($sql);
        $result = $db->getResult();
        $num = $db->numRows($result);
        if($num>=1){
            $admincode = $result[0]['refer_code'];
            $refer_code = $admincode . $random_number;
        }
        else{
            $refer_code = MAIN_REFER . $random_number;
        }
    }
    $sql = "SELECT register_points FROM settings WHERE id =1";
    $db->sql($sql);
    $result = $db->getResult();
    $coins=$result[0]['register_points'];
    $currentdate = date('Y-m-d');
    if(isset($_POST['mobile'])){
        $sql = "INSERT INTO users (`mobile`,`referred_by`,`upi`,`refer_code`,`coins`,`joined_date`) VALUES ('$mobile','$referred_by','','$refer_code','$coins','$currentdate')";
        $db->sql($sql);
    }
    else{
        $sql = "INSERT INTO users (`email`,`referred_by`,`upi`,`refer_code`,`coins`,`joined_date`) VALUES ('$email','$referred_by','','$refer_code','$coins','$currentdate')";
        $db->sql($sql);
    }
   
    $sql = "SELECT * FROM users WHERE mobile = '$mobile'";
    $db->sql($sql);
    $res = $db->getResult();
    $response['success'] = true;
    $response['message'] = "User Registered Successfully";
    $response['data'] = $res;
    print_r(json_encode($response));


}



?>
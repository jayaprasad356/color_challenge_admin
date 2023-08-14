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


if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = " User Id is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['wallet_type'])) {
    $response['success'] = false;
    $response['message'] = " Wallet Type is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id=$db->escapeString($_POST['user_id']);
$wallet_type = $db->escapeString($_POST['wallet_type']);

$sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);


if ($num == 1) {
    $basic_wallet = $res[0]['basic_wallet'];
    $premium_wallet = $res[0]['premium_wallet'];
    $current_refers = $res[0]['current_refers'];
    $today_ads = $res[0]['today_ads'];
    $target_refers = $res[0]['target_refers'];

    if($wallet_type == 'basic_wallet'){
        if ($basic_wallet < 30) {
            $response['success'] = false;
            $response['message'] = "Minimum ₹30 to add balance";
            print_r(json_encode($response));
            return false;
        }
        $sql = "UPDATE users SET balance= balance + basic_wallet,basic_wallet = 0 WHERE id=" . $user_id;
        $db->sql($sql);

    }
    if($wallet_type == 'premium_wallet'){
        if ($current_refers < $target_refers) {
            $response['success'] = false;
            $response['message'] = "Minimum ".$target_refers." refers to add balance";
            print_r(json_encode($response));
            return false;
        }
        if ($premium_wallet < 120) {
            $response['success'] = false;
            $response['message'] = "Minimum ₹120 to add balance";
            print_r(json_encode($response));
            return false;
        }
        $sql = "UPDATE users SET balance= balance + premium_wallet,premium_wallet = 0 WHERE id=" . $user_id;
        $db->sql($sql);
    
    }

    $sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
    $db->sql($sql);
    $res = $db->getResult();
    $response['success'] = true;
    $response['message'] = "Added to Main Balance Successfully";
    $response['data'] = $res;



}
else{
    $response['success'] = false;
    $response['message'] = "User Not Found";
}

print_r(json_encode($response));




?>

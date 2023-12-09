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
include_once('../includes/custom-functions.php');
$fn = new custom_functions;
$db = new Database();
$db->connect();

if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User ID is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['amount'])) {
    $response['success'] = false;
    $response['message'] = "Amount is Empty";
    print_r(json_encode($response));
    return false;
}
$date = date('Y-m-d');


$user_id = $db->escapeString($_POST['user_id']);
$amount = $db->escapeString($_POST['amount']);
$datetime = date('Y-m-d H:i:s');
$dayOfWeek = date('w', strtotime($datetime));


$sql = "SELECT * FROM settings WHERE id=1";
$db->sql($sql);
$result = $db->getResult();
$min_withdrawal = $result[0]['min_withdrawal'];

$sql = "SELECT * FROM users WHERE id='$user_id'";
$db->sql($sql);
$res = $db->getResult();
$balance = $res[0]['balance'];
$account_num = $res[0]['account_num'];
$earn = $res[0]['earn'];
$min_withdrawal = $res[0]['min_withdrawal'];
$withdrawal_status = $res[0]['withdrawal_status'];
$status = $res[0]['status'];
$total_ads = $res[0]['total_ads'];
$worked_days = $res[0]['worked_days'];
$total_referrals = $res[0]['total_referrals'];
$missed_days = $res[0]['missed_days'];
$old_plan = $res[0]['old_plan'];
$plan = $res[0]['plan'];
$blocked = $res[0]['blocked'];
$ads_10th_day = $res[0]['ads_10th_day'];
$performance = $res[0]['performance'];
$project_type = $res[0]['project_type'];
$joined_date = $res[0]['joined_date'];
$target_ads = 12000;
$percentage = 70;
$result = 8400;

if ($amount >= $min_withdrawal) {
    if ($amount <= $balance) {
        if ($account_num == '') {
            $response['success'] = false;
            $response['message'] = "Please Update Your Bank details";
            print_r(json_encode($response));
            return false;
        } else {
            if ($amount > 500 ) {
                $response['success'] = false;
                $response['message'] = "Maximum Withdrawal ₹500";
                print_r(json_encode($response));
                return false;
            }
            $sql = "SELECT id FROM withdrawals WHERE user_id = $user_id AND DATE(datetime) = '$date'";
            $db->sql($sql);
            $res= $db->getResult();
            $num = $db->numRows($res);

            if ($num >= 1){
                $response['success'] = false;
                $response['message'] = "You Already Requested to Withdrawal pls wait...";
                print_r(json_encode($response));
                return false;

            }
            

            $sql = "INSERT INTO withdrawals (`user_id`,`amount`,`balance`,`status`,`datetime`) VALUES ('$user_id','$amount',$balance,0,'$datetime')";
            $db->sql($sql);
            $sql = "UPDATE users SET balance=balance-'$amount' WHERE id='$user_id'";
            $db->sql($sql);

            $response['success'] = true;
            $response['message'] = "Withdrawal Requested Successfully.";
            print_r(json_encode($response));
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Insufficient Balance";
        print_r(json_encode($response));
    }
} else {
    $response['success'] = false;
    $response['message'] = "Minimum Withdrawal Amount is $min_withdrawal";
    print_r(json_encode($response));
}
?>

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
if (empty($_POST['amount'])) {
    $response['success'] = false;
    $response['message'] = "Amount is Empty";
    print_r(json_encode($response));
    return false;
}


function isBetween10AMand6PM() {
    $currentHour = date('H'); // Get the current hour in 24-hour format

    // Convert the time strings to timestamps for comparison
    $startTimestamp = strtotime('10:00:00');
    $endTimestamp = strtotime('18:00:00');

    // Check if the current hour is after 9 AM and before 10 PM
    return ($currentHour >= date('H', $startTimestamp)) && ($currentHour < date('H', $endTimestamp));
}
if (!isBetween9AMand6PM()) {
    $response['success'] = false;
    $response['message'] = "Withdrawal time morning 9AM to 6PM";
    print_r(json_encode($response)); 
    return false;
}
$user_id = $db->escapeString($_POST['user_id']);
$amount = $db->escapeString($_POST['amount']);
$datetime = date('Y-m-d H:i:s');

$sql = "SELECT * FROM settings WHERE id=1";
$db->sql($sql);
$result = $db->getResult();
$min_withdrawal=$result[0]['min_withdrawal'];
$sql = "SELECT * FROM users WHERE id='$user_id'";
$db->sql($sql);
$res = $db->getResult();
$balance=$res[0]['balance'];
$account_num=$res[0]['account_num'];
$earn=$res[0]['earn'];
$min_withdrawal=$res[0]['min_withdrawal'];


if($amount >= $min_withdrawal){
    if($amount <= $balance){
        if($account_num == ''){
            $response['success'] = false;
            $response['message'] = "Please Update Your Bank details";
            print_r(json_encode($response));
            return false;
        }
        else{
            $sql = "INSERT INTO withdrawals (`user_id`,`amount`,`status`,`datetime`) VALUES ('$user_id','$amount',0,'$datetime')";
            $db->sql($sql);
            $sql="UPDATE users SET balance=balance-'$amount',earn=earn+'$amount' WHERE id='$user_id'";
            $db->sql($sql);

            $response['success'] = true;
            $response['message'] = "Withdrawal Requested Successfully";
            print_r(json_encode($response));
    
        }
       
    }else{
        $response['success'] = false;
        $response['message'] = "Insufficient Balance";
        print_r(json_encode($response));
    }
}
else{
        $response['success'] = false;
        $response['message'] = "Minimum Withdrawal Amount is $min_withdrawal";
        print_r(json_encode($response));
}

?>